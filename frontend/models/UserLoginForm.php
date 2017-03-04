<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 2/23/2017
 * Time: 2:25 PM
 */

namespace frontend\models;

use common\validators\RoleValidator;
use common\models\LoginForm;

class UserLoginForm extends LoginForm
{
    const TYPE = 'user';

    public function rules()
    {
        $userRules = [
            ['username', RoleValidator::className()],
        ];

        return array_merge($userRules, parent::rules());
    }
}