<?php
/**
 * Created by PhpStorm.
 * User: kalim_000
 * Date: 2/23/2017
 * Time: 2:38 PM
 */

namespace frontend\models;

use common\models\SignupForm;
use common\models\User;

class UserSignupForm extends SignupForm
{
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save(false);

            // the following three lines were added:
            $auth = \Yii::$app->authManager;
            $userRole = $auth->getRole(User::ROLE_USER);
            $auth->assign($userRole, $user->getId());

            return $user;
        }

        return null;
    }
}