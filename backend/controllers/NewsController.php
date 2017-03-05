<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 3/5/2017
 * Time: 12:45 AM
 */

namespace backend\controllers;

use common\components\Recommendations;
use common\models\ActivityTracking;
use common\models\Category;
use common\models\News;
use common\models\Tags;
use common\models\User;
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
                    'get-by-category' => ['get'],
                    'view' => ['get']
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

        $news = News::find()->where(['category_id' => $category->id])->asArray()->all();

        if (!empty($get['access_token'])) {
            $recommend = new Recommendations($get['access_token']);
            $recommend->scoreCategoriesByGroups();
            $recommend->scoreCategoriesByActivity();
            $friendSawNews = $recommend->scoreCategoriesByFriendsActivity();
            $categoriesScore =  $recommend->categoryScore;

            foreach ($news as $key => $new){
                if(isset($friendSawNews[$new['id']])){
                    $news[$key]['friends'] = $friendSawNews[$new['id']];
                }
                $news[$key]['score'] = $categoriesScore[$new['category_id']];
            }

            usort($news, function($a, $b) {
                return $b['score'] <=> $a['score'];
            });

            return $news;
        }

        return $news;
    }


    public function actionView()
    {
        $get = \Yii::$app->request->get();

        if (empty($get['id'])) {
            throw new BadRequestHttpException('id param is missing');
        }

        if (empty($get['access_token'])) {
            throw new BadRequestHttpException('access_token param is missing');
        }


        $user = User::findByAccessToken($get['access_token']);

        if(is_null($user)){
            throw new BadRequestHttpException('user not found');
        }

        $news = News::findOne($get['id']);

        $activity = new ActivityTracking();
        $activity->user_id = $user->id;
        $activity->news_id = $news->id;
        $activity->save();

        if (is_null($news)) {
            throw new BadRequestHttpException('News is not found');
        }

        return $news;
    }

    public function actionAll()
    {
        $get = \Yii::$app->request->get();
        $news = News::find()->asArray()->all();

        if (!empty($get['access_token'])) {
            $recommend = new Recommendations($get['access_token']);
            $recommend->scoreCategoriesByGroups();
            $recommend->scoreCategoriesByActivity();
            $friendSawNews = $recommend->scoreCategoriesByFriendsActivity();
            $categoriesScore =  $recommend->categoryScore;


            foreach ($news as $key => $new){
                if(isset($friendSawNews[$new['id']])){
                    $news[$key]['friends'] = $friendSawNews[$new['id']];
                }
                $news[$key]['score'] = $categoriesScore[$new['category_id']];
            }

            usort($news, function($a, $b) {
                return $b['score'] <=> $a['score'];
            });

            return $news;
        }


        return $news;
    }
}
