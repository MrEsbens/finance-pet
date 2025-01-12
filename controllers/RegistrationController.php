<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use app\models\RegistrationForm;
use app\models\User;

class RegistrationController extends Controller{
    public function actionIndex(){
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new RegistrationForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = new User();
            $user->username = $model->username;
            $user->email = $model->email;
            $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            $user->auth_key = Yii::$app->getSecurity()->generateRandomString();
            $user->created_at = date('Y-m-d H:i:s', time());
            $user->updated_at = date('Y-m-d H:i:s', time());
            if($user->save()){
                Yii::$app->user->login($user);
                return $this->goHome();
            }
        }
        return $this->render('registration', ['model' => $model]);
    }
}