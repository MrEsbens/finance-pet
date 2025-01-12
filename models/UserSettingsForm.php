<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\validators\PasswordValidator;

class UserSettingsForm extends Model{
    public $username;
    public $email;
    public $change_password;
    public $old_password;
    public $password;
    public $password_repeat;
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            ['email', 'email'],
            ['change_password', 'boolean'],
            [['old_password', 'password', 'password_repeat'], 'required', 'when' => function($model) {
                return $model->change_password;
            }, 'whenClient' => "function (attribute, value) {
                return $('#change-password-checkbox').is(':checked');
            }"],
            ['old_password', 'validatePassword', 'when' => function($model) {
                return $model->change_password;
            }, 'whenClient' => "function (attribute, value) {
                return $('#change-password-checkbox').is(':checked');
            }"],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'when' => function($model) {
                return $model->change_password;
            }, 'whenClient' => "function (attribute, value) {
                return $('#change-password-checkbox').is(':checked');
            }"],
            ['password', PasswordValidator::class, 'when' => function($model) {
                return $model->change_password;
            }, 'whenClient' => "function (attribute, value) {
                return $('#change-password-checkbox').is(':checked');
            }"],
        ];
    }
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = User::findOne(Yii::$app->user->getId());
            if (Yii::$app->getSecurity()->validatePassword($this->old_password, $user->getPasswordHash())) {
                return true;
            } else {
                $this->addError($attribute, 'Пароль введен неверно.');
            }
        }
        return false;
    }
}