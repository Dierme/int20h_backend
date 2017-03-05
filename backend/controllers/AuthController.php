<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 3/4/2017
 * Time: 12:23 PM
 */

namespace backend\controllers;


use common\models\SignupForm;
use common\models\User;
use common\models\Vkgroups;
use common\models\VkProfile;
use common\models\VkuserHasFriends;
use common\models\VkuserHasVkgroups;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\MethodNotAllowedHttpException;
use yii\web\BadRequestHttpException;
use common\components\VKClient;
use yii\web\ServerErrorHttpException;


class AuthController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'auth-vk' => ['post'],
                    'create' => ['post'],
                    'friends' => ['get'],
                ],
            ]
        ];
    }

    /**
     * @param \yii\base\Action $event
     * @return bool
     * @throws MethodNotAllowedHttpException
     * @throws NotFoundHttpException
     */
    public function beforeAction($event)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $action = $event->id; //action name
        if (isset($this->actions[$action])) {
            $verbs = $this->actions[$action];
        } else {
            throw new NotFoundHttpException('Method not found');
        }
        $verb = Yii::$app->getRequest()->getMethod();

        $allowed = array_map('strtoupper', $verbs);

        if (!in_array($verb, $allowed)) {
            throw new MethodNotAllowedHttpException('Method is not allowed');
        }

        return true;
    }


    public function actionAuthVk()
    {
        $vkClient = new VKClient();
        $post = Yii::$app->request->post();
        if (!isset($post['code'])) {

            if (!isset($post['scope'])) {
                throw new BadRequestHttpException('scope param is missing');
            }

            $authorize_url = $vkClient->getAuthUrl($post['scope']);

            return [
                'success' => true,
                'authUri' => $authorize_url
            ];

        } else {

            $accessToken = $vkClient->getAccessToken($post['code']);

            if ($accessToken['access_token']) {

                $vkProfile = VkProfile::findByUID($accessToken['user_id']);


                if (is_null($vkProfile)) {
                    $userProfile = $vkClient->getUserProfile();
                    $userGroups = $vkClient->getUserGroups();
                    $userFriends = $vkClient->getUserFriends();

                    //sign up basic user stub
                    $username = $userProfile['first_name'] . $userProfile['last_name'];
                    $signUp = new SignupForm();
                    $signUp->username = $username;
                    $signUp->api_token = $accessToken['access_token'];
                    $user = $signUp->signUpVkUser();

                    if (!isset($user->id)) {
                        throw new ServerErrorHttpException('Failed to register user in system');
                    }


                    //create user VK profile
                    $vkParams = [
                        'name' => $userProfile['first_name'],
                        'surname' => $userProfile['last_name'],
                        'user_id' => $user->id,
                        'vk_uid' => $userProfile['uid'],
                        'api_token' => $accessToken['access_token']
                    ];
                    $userVkProfile = new VkProfile();
                    $userVkProfile->setAttributes($vkParams);
                    $userVkProfile->save();


                    //save user VK groups
                    foreach ($userGroups as $gid => $groupName) {
                        $vkGroup = Vkgroups::findByGid($gid);
                        if (is_null($vkGroup)) {
                            $vkGroup = new Vkgroups();
                            $vkGroup->setAttribute('gid', $gid);
                            $vkGroup->setAttribute('group_name', $groupName);
                            $vkGroup->save();
                        }
                        if (!isset($vkGroup->id)) {
                            throw new ServerErrorHttpException('Failed to save group in system');
                        }

                        $userHasGroupQuery = VkuserHasVkgroups::find()->where([
                            'vk_group_id' => $vkGroup->id,
                            'vk_profile_id' => $userVkProfile->id
                        ]);
                        if (!$userHasGroupQuery->exists()) {
                            $userHasGroup = new VkuserHasVkgroups();
                            $userHasGroup->setAttribute('vk_group_id', $vkGroup->id);
                            $userHasGroup->setAttribute('vk_profile_id', $userVkProfile->id);
                            $userHasGroup->save();
                        }
                    }


                    //save user VK friends, if they are registered
                    foreach ($userFriends as $friendUid) {
                        $friendVkProfile = VkProfile::findByUID($friendUid);

                        if (!is_null($friendVkProfile)) {

                            $userHasFriendQuery = VkuserHasFriends::find()->where([
                                'vk_user_id' => $userVkProfile->id,
                                'vk_friend_id' => $friendVkProfile->id
                            ]);

                            if (!$userHasFriendQuery->exists()) {
                                $userHasFriend = new VkuserHasFriends();
                                $userHasFriend->setAttribute('vk_user_id', $userVkProfile->id);
                                $userHasFriend->setAttribute('vk_friend_id', $friendVkProfile->id);
                                $userHasFriend->save();
                            }
                        }
                    }
                }
                else{
                    $user = User::findOne($vkProfile->user_id);
                    $user->api_token = $accessToken['access_token'];
                    $user->save(false);

                    $userFriends = $vkClient->getUserFriends();

                    foreach ($userFriends as $friendUid) {
                        $friendVkProfile = VkProfile::findByUID($friendUid);

                        if (!is_null($friendVkProfile)) {

                            $userHasFriendQuery = VkuserHasFriends::find()->where([
                                'vk_user_id' => $vkProfile->id,
                                'vk_friend_id' => $friendVkProfile->id
                            ]);

                            if (!$userHasFriendQuery->exists()) {
                                $userHasFriend = new VkuserHasFriends();
                                $userHasFriend->setAttribute('vk_user_id', $vkProfile->id);
                                $userHasFriend->setAttribute('vk_friend_id', $friendVkProfile->id);
                                $userHasFriend->save();
                            }
                        }
                    }
                }

                return [
                    'success' => true,
                    'accessToken' => $accessToken['access_token']
                ];
            } else {
                throw new BadRequestHttpException('Failed to sign in to VK');
            }
        }
    }

    public function actionFriends()
    {
        $get = Yii::$app->request->get();
        if (!isset($get['access_token'])) {
            throw new BadRequestHttpException('access_token param is missing');
        }

        $vkClient = new VKClient($get['access_token']);

        return $vkClient->getUserGroups();
    }


}