<?php

namespace app\controllers;

use app\components\services\UserService;
use yii\web\Controller;
use Yii;
use app\models\LoginForm;


class LoginController extends Controller
{
    private UserService $userService;

    public function __construct(
        string $id,
        $module,
        UserService $userService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->userService = $userService;
    }
    public function actionIndex()
    {
        if(!$this->userService->isGuest()) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($this->userService->login($model)) {
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Некорректный логин или пароль.');
            }
        }

        return $this->render('login', ['model' => $model]);
    }
    public function actionLogout()
    {
        $this->userService->logout();
        return $this->goHome();
    }
}