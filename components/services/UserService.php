<?php

namespace app\components\services;

use app\models\User;
use Yii;
use app\models\LoginForm;
use app\models\RegistrationForm;

class UserService
{
    public function getCurrentUser(): User
    {
        return User::findOne(Yii::$app->user->getId());
    }
    public function login(LoginForm $loginForm): bool
    {
        $user = $loginForm->getUser();
        return $user ? $loginForm->login() : false;
    }

    public function logout(): void
    {
        Yii::$app->user->logout();
    }

    public function isGuest(): bool
    {
        return Yii::$app->user->isGuest;
    }

    public function register(RegistrationForm $registrationForm): bool
    {
        $newUser = new User([
            'username' => $registrationForm->username,
            'email' => $registrationForm->email,
            'password_hash' => Yii::$app->security->generatePasswordHash($registrationForm->password),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        if ($newUser->save()) {
            Yii::$app->user->login($newUser);
            return true;
        }

        return false;
    }
}