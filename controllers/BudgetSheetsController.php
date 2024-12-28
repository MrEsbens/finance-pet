<?php
namespace app\controllers;

use app\models\Category;
use app\models\enums\CategoryType;
use yii\web\Controller;
use app\models\BudgetSheet;
use app\models\CreateBudgetSheet;
use Yii;
use yii\web\NotFoundHttpException;
use app\models\Transaction;

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
        $model = new CreateBudgetSheet();
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
        $model = new CreateBudgetSheet();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $budget_sheet = BudgetSheet::findOne(Yii::$app->request->post('CreateBudgetSheet')['id']);
            if($budget_sheet) {
                $budget_sheet->name = $model->name;
                $budget_sheet->updated_at = date('Y-m-d H:i:s', time());

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
        $budget_sheet = BudgetSheet::findOne(Yii::$app->request->get('id'));
        if($budget_sheet) {
            if($budget_sheet->delete()){
                $this->redirect(['budget-sheets/index']);
            }
        }else{
            throw new NotFoundHttpException('Budget sheet not found.');
        }
    }
    public function actionShow(){
        $budget_sheet = BudgetSheet::findOne(Yii::$app->request->get('id'));
        $currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
        $currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        $daysInMonth = date('t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
        $expenses = []; // Здесь должны быть ваши данные о расходах
        $incomes = [];  // Здесь должны быть ваши данные о доходах
        $query = Transaction::find()
            ->where(['>=', 'transaction_date', $currentYear.'-'.$currentMonth.'-01'])
            ->andWhere(['<=', 'transaction_date', $currentYear.'-'.$currentMonth.'-'.$daysInMonth])
            ->andWhere(['sheet_id' => $budget_sheet->id])
            ->all();
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $incomes[$day] = 0;
            $expenses[$day] = 0;
        }
        foreach ($query as $res) {
            $category = Category::findOne($res->category_id);
            if ($category->type == CategoryType::Income->value) {
                $incomes[date('j', strtotime($res->transaction_date))] += $res->amount;
            }else{
                $expenses[date('j', strtotime($res->transaction_date))] += $res->amount;
            }
        }
        return $this->render('sheet-show', ['budget_sheet'=>$budget_sheet,
            'currentMonth'=>$currentMonth,
            'currentYear'=>$currentYear,
            'daysInMonth'=>$daysInMonth,
            'expenses'=>$expenses,
            'incomes'=>$incomes]);
    }
}