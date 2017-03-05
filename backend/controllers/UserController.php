<?php

namespace backend\controllers;

use Yii;
use common\components\VKClient;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;

/**
 * UserController implements the CRUD actions for user model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-profile' => ['get'],
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

    public function actionGetProfile()
    {
        $get = \Yii::$app->request->get();
        if (!isset($get['access_token'])) {
            throw new BadRequestHttpException('access_token param is missing');
        }
        $vkClient = new VKClient($get['access_token']);
        $profile = $vkClient->getUserProfile();
        return $profile;
    }


    public function actionCreate()
    {
        $response = [
            'success'   =>  true,
            'message'   =>  'User created'
        ];

        return $response;
    }
}
