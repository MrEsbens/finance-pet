<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\BudgetSheet;
use app\models\CreateBudgetSheet;
use Yii;
use yii\web\NotFoundHttpException;

class BudgetSheetsController extends Controller{
    public function actionIndex(){
        if(Yii::$app->user->isGuest){
            return $this->goHome();
        }
        $budget_sheets = BudgetSheet::findAll([
            'user_id' => Yii::$app->user->id,
        ]);
        return $this->render('budget-sheets', ['budget_sheets'=>$budget_sheets]);
    }
    public function actionCreate(){
        $model = new createBudgetSheet();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $budget_sheet = new BudgetSheet();
            $budget_sheet->user_id = Yii::$app->user->id;
            $budget_sheet->name = $model->name;
            $budget_sheet->created_at = date('Y-m-d H:i:s', time());
            $budget_sheet->updated_at = date('Y-m-d H:i:s', time());
            if($budget_sheet->save()){
             $this->redirect(['budget-sheets/index']);
            }
        }
        return $this->render('create-budget-sheet', ['budget_sheet' => $model, 'action'=>'create']);
    }
    public function actionUpdate(){
        $model = new createBudgetSheet();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $budget_sheet = BudgetSheet::findOne(Yii::$app->request->post('CreateBudgetSheet')['id']);
            if($budget_sheet) {
                $budget_sheet->name = $model->name;

                if($budget_sheet->save()){
                    $this->redirect(['budget-sheets/index']);
                }
            }else{
                throw new NotFoundHttpException('Budget sheet not found.');
            }
        }
        return $this->render('create-budget-sheet', ['budget_sheet' => $model, 'action' => 'update']);
    }
    public function actionDelete(){
        $model = new createBudgetSheet();
        $budget_sheet = BudgetSheet::findOne(Yii::$app->request->get('id'));
        if($budget_sheet) {
            if($budget_sheet->delete()){
                $this->redirect(['budget-sheets/index']);
            }
        }else{
            throw new NotFoundHttpException('Budget sheet not found.');
        }
    }

}