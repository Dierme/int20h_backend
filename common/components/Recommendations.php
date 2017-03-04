<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 3/4/2017
 * Time: 1:34 PM
 */

namespace common\components;

use Yii;
use common\models\VkProfile;

class Recommendations
{
    private $identity;

    public function __construct()
    {
        $this->identity = Yii::$app->user->identity;
    }



}