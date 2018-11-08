<?php
/**
 * Created by PhpStorm.
 * User: vyach
 * Date: 07.11.2018
 * Time: 22:56
 */

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
        ];
    }

    /**
     * @return User|bool
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($user->save() && $user->sendConfirmEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email to confirm the registration.');
                return true;
            }
        }
        return false;
    }

    /**
     * @return User
     * @throws \yii\base\Exception
     */
    public function getUser()
    {
        $model = new User();
        $model->username = $this->username;
        $model->email = $this->email;
        $model->setPassword($this->password);
        return $model;
    }
}