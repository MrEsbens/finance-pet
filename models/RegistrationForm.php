<?php

namespace app\models;
use yii\base\Model;
use app\components\validators\PasswordValidator;

class RegistrationForm extends Model {

    public $username;
    public $email;
    public $password;

    // Валидация параметров с формы
    public function rules(): array {
        return [
            [['username', 'password', 'email'], 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username'],
            ['email', 'email'],
            ['password', PasswordValidator::class],
        ];
    }

}