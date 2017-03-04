<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 3/5/2017
 * Time: 1:18 AM
 */

namespace backend\controllers;


namespace backend\controllers;

use common\models\Category;
use common\models\News;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;

/**
 * UserController implements the CRUD actions for user model.
 */
class CategoryController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'all' => ['get'],
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


    public function actionAll()
    {
        return Category::find()->all();
    }
}
