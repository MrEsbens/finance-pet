<?php

namespace app\controllers;

use app\models\LoginForm;
use yii\web\Controller;
use app\models\User;
use Yii;

class LoginController extends Controller{
    public function actionIndex(){
        if(!Yii::$app->user->isGuest){
            return $this->goHome();
        }
        $model = new LoginForm();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $user = $model->getUser();
            if ($user)
            if($user){
                $model->login();
                return $this->goHome();
            }
        }
        return $this->render('login', ['model' => $model]);
    }
    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->goHome();
    }
}