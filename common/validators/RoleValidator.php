<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 2/23/2017
 * Time: 2:20 PM
 */

namespace common\validators;

use yii\validators\Validator;
use common\models\User;
use common\models\AuthAssignment;

class RoleValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $user = User::findByUsername($model->username);

        if(empty($user->id)){
            return false;
        }

        if(User::isAdmin($user->id)){
            return true;
        }

        $roleQuery = AuthAssignment::find()->where([
            'user_id'=>$user->id,
            'item_name'=>$model::TYPE
        ]);

        if(!$roleQuery->exists()){
            $model->addError($attribute, 'You do not have a permission to login.');
        }

        return true;
    }
}