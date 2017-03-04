<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 3/4/2017
 * Time: 12:23 PM
 */

namespace backend\controllers;


use common\models\VkProfile;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\MethodNotAllowedHttpException;
use yii\web\BadRequestHttpException;
use common\components\VKClient;


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

                    return $userGroups;
                    //TODO: create user sving to DB
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

        return $vkClient->getUserFriends();
    }


}