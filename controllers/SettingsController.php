<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\UserSettingsForm;
use yii\db\Exception;

class SettingsController extends Controller
{
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = User::findOne(Yii::$app->user->getId());
        $model = new UserSettingsForm();
        $model->username = $user->username;
        $model->email = $user->email;
        $model->change_password = false;

        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->username = $model->username;
            $user->email = $model->email;
            $user->updated_at = date('Y-m-d H:i:s', time());

            if ($model->change_password) {
                $user->password_hash = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            }

            if($user->update()) {
                return $this->goHome();
            } else {
                throw new Exception('Не удалось внести изменения');
            }
        }

        return $this->render('settings', ['model' => $model]);
    }
}