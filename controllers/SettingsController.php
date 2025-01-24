<?php
namespace app\controllers;

use app\components\services\SettingsService;
use app\components\services\UserService;
use Yii;
use yii\web\Controller;
use yii\db\Exception;

class SettingsController extends Controller
{
    private UserService $userService;
    private SettingsService $settingsService;

    public function __construct(
        string $id, 
        $module, 
        UserService $userService, 
        SettingsService $settingsService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->userService = $userService;
        $this->settingsService = $settingsService;
    }
    public function actionIndex()
    {
        if($this->userService->isGuest()) { 
            return $this->goHome();
        }

        $settingsForm = $this->settingsService->bindSettingsForm();

        if ($settingsForm->load(Yii::$app->request->post()) && $settingsForm->validate()) {
            if ($this->settingsService->updateSettings($settingsForm)) {
                return $this->goHome();
            } else {
                throw new Exception('Failed to update user settings');
            }
        }

        return $this->render('settings', ['model' => $settingsForm]);
    }
}