<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 3/5/2017
 * Time: 12:45 AM
 */

namespace backend\controllers;

use common\models\Category;
use common\models\News;
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
class NewsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-featured' => ['get'],
                    'all' => ['get'],
                    'get-by-category' => ['get']
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

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionGetFeatured()
    {
        $featuredNews = News::find()->where(['featured' => 1])->all();

        return $featuredNews;
    }

    public function actionGetByCategory()
    {
        $get = \Yii::$app->request->get();

        if (empty($get['category'])) {
            throw new BadRequestHttpException('categoty param is missing');
        }

        $categoryQuery = Category::find()->where(['name' => $get['category']]);

        if (!$categoryQuery->exists()) {
            throw new BadRequestHttpException('Specified category not found');
        }

        $category = $categoryQuery->one();

        $news = News::find()->where(['category_id' => $category->id])->all();

        return $news;
    }

    public function actionAll()
    {
        return News::find()->all();
    }
}