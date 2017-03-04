<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 2/28/2017
 * Time: 7:08 PM
 */

namespace common\validators;

use yii\validators\Validator;

class StrtolowerUTF8 extends Validator
{
    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @return bool
     * @throws ValidatorException
     */
    public function validateAttribute($model, $attribute)
    {
        $model->$attribute = $string = mb_convert_case($model->$attribute, MB_CASE_LOWER, "UTF-8");
    }
}