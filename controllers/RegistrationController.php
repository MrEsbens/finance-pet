<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use app\models\RegistrationForm;
use app\components\services\UserService;
use Yii\db\Exception;

class RegistrationController extends Controller
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
        if (!$this->userService->isGuest()) {
            return $this->goHome();
        }

        $model = new RegistrationForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($this->userService->register($model)) {
                return $this->goHome();
            } else {
                throw new Exception('Failed to register user'); 
            }
        } 

        return $this->render('registration', ['model' => $model]);
    }
}