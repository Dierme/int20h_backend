<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\UserSignupForm;

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
                    'index' => ['get'],
                ],
            ]
        ];
    }

    /**
     * @param \yii\base\Action $event
     * @return bool
     */
    public function beforeAction($event)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $action = $event->id; //action name
        if (isset($this->actions[$action])) {
            $verbs = $this->actions[$action];
        } else {
            $this->setHeader(500);
            echo json_encode(array('status' => 0, 'error_code' => 500, 'message' => 'Routing for method not specified'), JSON_PRETTY_PRINT);
            exit;
        }
        $verb = Yii::$app->getRequest()->getMethod();

        $allowed = array_map('strtoupper', $verbs);

        if (!in_array($verb, $allowed)) {

            $this->setHeader(400);
            echo json_encode(array('status' => 0, 'error_code' => 400, 'message' => 'Method not allowed'), JSON_PRETTY_PRINT);
            exit;
        }

        return true;
    }

    public function actionIndex()
    {
        $users = [
            '0' => ['name' => 'testUser0', 'role'=>'admin'],
            '1' => ['name' => 'testUser1', 'role'=>'admin']
        ];

        return $users;
    }
}
