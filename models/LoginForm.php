<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['username', 'validateStatus'],
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword']
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        return User::findByUsername($this->username);
    }

    /**
     *Validates the user status
     *
     */
    public function validateStatus()
    {
        $user = $this->getUser();
        $status = $user->status;
        if ($status == User::STATUS_BLOCKED) {
            $this->addError('status', 'Your account has been blocked');
            Yii::$app->session->setFlash('danger', 'Your account has been blocked');
        } elseif ($status == User::STATUS_WAIT) {
            $this->addError('status', 'Your account is waiting to be confirmed. Check your e-mail');
            Yii::$app->session->setFlash('danger', 'Your account is waiting to be confirmed. Check your e-mail');
            $user->sendConfirmEmail();
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 60 : 0);
        }
        return false;
    }
}
