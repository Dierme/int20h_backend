<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 2/24/2017
 * Time: 2:39 PM
 */

namespace common\validators;

use yii\validators\Validator;
use common\exceptions\ValidatorException;

class StatusValidator extends Validator
{
    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @return bool
     * @throws ValidatorException
     */
    public function validateAttribute($model, $attribute)
    {
        if(!isset($model::$statusDictionary)){
            $msg = 'static attribute $statusDictionary must be set to use '.self::className();
            throw new ValidatorException($msg);
        }
        $statusDictionary = $model::$statusDictionary;

        if(empty($statusDictionary[$model->$attribute])){
            $model->addError($attribute, 'Status is not valid');
            return false;
        }

        return true;
    }

}