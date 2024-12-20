<?php

namespace app\models;
use yii\base\Model;

class RegistrationForm extends Model {

    public $login;
    public $password;
    public $firstName;
    public $lastName;
    public $email;
    public $phone;

    // Валидация параметров с формы
    public function rules() {
        return [[
            ['login', 'password', 'firstName', 'lastName', 'email'], 'required'],
            ['email', 'email'],
            ['password', 'string', 'min' => 10]
        ];
    }

}