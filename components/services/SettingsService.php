<?php

namespace app\components\services;

use app\models\UserSettingsForm;
use Yii;

class SettingsService
{
    private UserService $userService;
    public function __construct(UserService $userService) 
    {
        $this->userService = $userService;
    }
    public function bindSettingsForm(): UserSettingsForm
    {
        $user = $this->userService->getCurrentUser();
        $settingsForm = new UserSettingsForm();
        $settingsForm->username = $user->username;
        $settingsForm->email = $user->email;
        $settingsForm->change_password = false;
        return $settingsForm;
    }

    public function updateSettings(UserSettingsForm $settingsForm)
    {
        $user = $this->userService->getCurrentUser();
        $user->username = $settingsForm->username;
        $user->email = $settingsForm->email;
        $user->updated_at = date('Y-m-d H:i:s', time());

        if($settingsForm->change_password) {
            $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($settingsForm->password);
        }

        return $user->save();
    }
}