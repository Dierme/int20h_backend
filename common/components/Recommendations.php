<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 3/4/2017
 * Time: 1:34 PM
 */

namespace common\components;

use common\models\ActivityTracking;
use common\models\Category;
use common\models\Tags;
use common\models\User;
use common\models\VkuserHasFriends;
use Yii;
use common\models\VkProfile;
use yii\helpers\ArrayHelper;

class Recommendations
{
    CONST ACTIVITY_MAX = 12;
    CONST ACTIVITY_DELIMITER = 2;

    private $client;

    private $user;

    private $userGroups;

    public $tagsScore;

    public $categoryScore;

    public function __construct($accessToken)
    {


        $this->user = User::findByAccessToken($accessToken);

        $this->client = $this->resolveClient($accessToken);

        $this->userGroups = $this->client->getUserGroups();

        $this->tagsScore = $this->initTagsScore(Tags::find()->all());

        $this->categoryScore = $this->initCategoryScore(Category::find()->all());
    }

    public function scoreCategoriesByGroups()
    {
        $tags = Tags::find()->all();

        foreach ($this->userGroups as $gid => $groupName) {
            foreach ($tags as $tag) {
                $keywords = explode(',', $tag->keywords);
                foreach ($keywords as $keyword) {
                    if (strpos($groupName, $keyword)) {
                        $categories = $tag->getCategoryHasTags()->all();
                        foreach ($categories as $category) {
                            $this->categoryScore[$category->category_id] += 1;
                        }
                        $this->tagsScore[$tag->id] += 1;
                        break;
                    }
                }
            }
        }
    }

    public function scoreCategoriesByActivity()
    {
        $categoryScore = array();

        $activity = ActivityTracking::find()
            ->where(['user_id' => $this->user->id])
            ->all();

        foreach ($activity as $instance) {
            $news = $instance->getNews()->one();
            if (empty($categoryScore[$news->category_id])) {
                $categoryScore[$news->category_id] = 0;
                $categoryScore[$news->category_id] += 1;
            } else {
                if ($categoryScore[$news->category_id] < self::ACTIVITY_MAX) {
                    $categoryScore[$news->category_id] += 1;
                }
            }
        }

        foreach ($categoryScore as $categoryId => $score) {
            $this->categoryScore[$categoryId] += (int)($score / self::ACTIVITY_DELIMITER);
        }
    }


    public function scoreCategoriesByFriendsActivity()
    {
        $categoryScore = array();
        $vkProfile = VkProfile::find()->where(['user_id' => $this->user->id])->one();
        $friends = VkuserHasFriends::find()
            ->where(['vk_user_id' => $vkProfile->id])
            ->all();

        $friendsProfileIds = ArrayHelper::getColumn($friends, 'vk_friend_id');

        $vkFriendsProfiles = array();
        foreach ($friendsProfileIds as $vkId){
            $vkFriendsProfiles[] = VkProfile::findOne($vkId);
        }

        $friends = array();
        foreach ($vkFriendsProfiles as $vkProfile) {
            $friends[] = User::findOne($vkProfile->user_id);
        }
        $friendsIds = ArrayHelper::getColumn($friends, 'id');

        $activity = ActivityTracking::find()
            ->where(['user_id' => $friendsIds])
            ->all();

        foreach ($activity as $instance) {
            $news = $instance->getNews()->one();
            if (empty($categoryScore[$news->category_id])) {
                $categoryScore[$news->category_id] = 0;
                $categoryScore[$news->category_id] += 1;
            } else {
                if ($categoryScore[$news->category_id] < self::ACTIVITY_MAX) {
                    $categoryScore[$news->category_id] += 1;
                }
            }
        }

        foreach ($categoryScore as $categoryId => $score) {
            $this->categoryScore[$categoryId] += (int)($score / self::ACTIVITY_DELIMITER);
        }
    }

    private function initCategoryScore($categories)
    {
        $categoryScore = array();
        foreach ($categories as $category) {
            $categoryScore[$category->id] = 0;
        }
        return $categoryScore;
    }

    private function initTagsScore($tags)
    {
        $tagsScore = array();
        foreach ($tags as $tag) {
            $tagsScore[$tag->id] = 0;
        }

        return $tagsScore;
    }

    private function resolveClient($accessToken)
    {
        return new VKClient($accessToken);
    }
}