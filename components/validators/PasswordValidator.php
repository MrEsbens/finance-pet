<?php
namespace app\components\validators;

use yii\validators\Validator;

class PasswordValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $password = $model->$attribute;

        // Проверка длины пароля
        if (strlen($password) < 8 || strlen($password) > 20) {
            $this->addError($model, $attribute, 'Пароль должен содержать от 8 до 20 символов.');
            return;
        }

        // Проверка наличия заглавных букв
        if (!preg_match('/[A-Z]/', $password)) {
            $this->addError($model, $attribute, 'Пароль должен содержать хотя бы одну заглавную букву.');
            return;
        }

        // Проверка наличия цифр
        if (!preg_match('/[0-9]/', $password)) {
            $this->addError($model, $attribute, 'Пароль должен содержать хотя бы одну цифру.');
        }
    }
}