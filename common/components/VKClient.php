<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 3/4/2017
 * Time: 12:30 PM
 */

namespace common\components;

use VK\VK;
use Yii;

class VKClient
{
    private $apiClient;

    private $params;

    /**
     * VKClient constructor.
     * @param null $accessToken
     */
    public function __construct($accessToken = null)
    {
        $this->params = Yii::$app->params['vk'];

        $this->apiClient = $this->resolveClient($accessToken);
    }

    /**
     * @param $accessToken
     * @return VK
     */
    private function resolveClient($accessToken)
    {
        if (is_null($accessToken)) {
            return new VK($this->params['appId'], $this->params['apiSecret']);
        } else {
            return new VK($this->params['appId'], $this->params['apiSecret'], $accessToken);
        }
    }

    /**
     * @param $method
     * @param array $params
     * @return mixed
     */
    private function query($method, array $params = [])
    {
        return $this->apiClient->api($method, $params);
    }

    /**
     * @param $scope
     * @return string
     */
    public function getAuthUrl($scope)
    {
        return $this->apiClient->getAuthorizeUrl($scope, $this->params['callbackUrl']);
    }

    /**
     * @param $code
     * @return array
     */
    public function getAccessToken($code)
    {
        return $this->apiClient->getAccessToken($code, $this->params['callbackUrl']);
    }

    /**
     * @param bool $userID
     * @return mixed
     */
    public function getUserProfile($userID = false)
    {
        if($userID === false){
            $profile = $this->query('users.get', []);
        }
        else{
            $profile = $this->query('users.get', [
                'uid'   =>  $userID
            ]);
        }

        return $profile['response'][0];
    }

    /**
     * @param bool $userID
     * @return mixed
     */
    public function getUserFriends($userID = false)
    {
        if($userID === false){
            $friends = $this->query('friends.get');
        }
        else{
            $friends = $this->query('friends.get', ['uid'=>$userID]);
        }

        return $friends['response'];

    }

    /**
     * @param bool $userID
     * @return array
     */
    public function getUserGroups($userID = false)
    {
        if($userID === false) {
            $groups = $this->query('groups.get', [
                'extended' => '1'
            ]);
        }
        else{
            $groups = $this->query('groups.get', [
                'uid'   =>  $userID,
                'extended' => '1'
            ]);
        }
        $groupNames = array();

        foreach ($groups['response'] as $group) {
            if (!isset($group['name']) || !isset($group['gid'])) {
                continue;
            }
            $groupNames[$group['gid']] = $group['name'];
        }

        return $groupNames;
    }

}