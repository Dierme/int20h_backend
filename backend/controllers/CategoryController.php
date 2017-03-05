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
use common\models\Tags;
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
                    'tags' => ['get'],
                    'all' => ['get'],
                    'view' => ['get'],
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

    public function actionTags()
    {

        $get = \Yii::$app->request->get();

        if (empty($get['category_id'])) {
            throw new BadRequestHttpException('category_id param is missing');
        }

        $category = Category::findOne($get['category_id']);

        if (is_null($category)) {
            throw new BadRequestHttpException('category is not found');
        }

        $tags = Tags::find()->joinWith(['categoryHasTags'])
            ->where(['category_has_tags.category_id' => $category->id])
            ->all();


        return $tags;
    }

    public function actionView()
    {
        $get = \Yii::$app->request->get();

        if (empty($get['id'])) {
            throw new BadRequestHttpException('id param is missing');
        }

        $category = Category::findOne($get['id']);

        if (is_null($category)) {
            throw new BadRequestHttpException('category is not found');
        }

        return $category;
    }

    public function actionAll()
    {
        return Category::find()->all();
    }
}
