<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    private $_user = null;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $this->getUser();
            if ($this->_user) {
                if (Yii::$app->getSecurity()->validatePassword($this->password, $this->_user->getPasswordHash())) {
                    return true;
                } else {
                    $this->addError($attribute, 'Некорректное имя пользователя или пароль.');
                }
            } else {
                $this->addError($attribute, 'Некорректное имя пользователя или пароль.');
            }  
        }
        return false;
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        return Yii::$app->user->login($this->getUser());
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        } else {
            return $this->_user;
        }
    }
}
