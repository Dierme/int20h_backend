<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 3/4/2017
 * Time: 1:34 PM
 */

namespace common\components;

use common\models\Category;
use common\models\Tags;
use Yii;
use common\models\VkProfile;

class Recommendations
{
    private $client;

    private $userGroups;

    public $tagsScore;

    public $categoryScore;

    public function __construct($accessToken)
    {
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
                        foreach ($categories as $category){
                            $this->categoryScore[$category->category_id] += 1;
                        }
                        $this->tagsScore[$tag->id] += 1;
                        break;
                    }
                }
            }
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