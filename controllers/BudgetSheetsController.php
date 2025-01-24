<?php
namespace app\controllers;

use app\components\services\BudgetSheetsService;
use app\components\services\UserService;
use yii\web\Controller;
use app\models\CreateBudgetSheet;
use Yii;
use yii\db\Exception;

class BudgetSheetsController extends Controller
{
    private BudgetSheetsService $budgetSheetsService;
    private UserService $userService;

    public function __construct(
        $id,
        $module,
        BudgetSheetsService $budgetSheetsService,
        UserService $userService,
        array $config = []
    ) {
        $this->budgetSheetsService = $budgetSheetsService;
        $this->userService = $userService;
        parent::__construct($id, $module, $config);
    }
    public function actionIndex()
    {
        if($this->userService->isGuest()) {
            return $this->goHome();
        }

        $budget_sheets = $this->budgetSheetsService->getBudgetSheets();

        return $this->render('budget-sheets', ['budget_sheets'=>$budget_sheets]);
    }
    public function actionCreate()
    {
        $model = new CreateBudgetSheet();

        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            if($this->budgetSheetsService->createBudgetSheet($model)) {
                $this->redirect(['budget-sheets/index']);
            } else {
                throw new Exception('Failed to save budget sheet.');
            }
        }

        return $this->render('create-budget-sheet', ['budget_sheet' => $model, 'action'=>'create']);
    }

    public function actionUpdate()
    {
        $budgetSheetId = Yii::$app->request->get('id');
        $model = $this->budgetSheetsService->bindCreateBudgetSheetForm($budgetSheetId);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($this->budgetSheetsService->updateBudgetSheet($budgetSheetId, $model)) {
                $this->redirect(['budget-sheets/index']);
            } else {
                throw new Exception('Failed to update budget sheet.');
            }
        }

        return $this->render('create-budget-sheet', ['budget_sheet' => $model, 'action' => 'update']);
    }
    public function actionDelete()
    {
        $budgetSheetId = Yii::$app->request->get('id');

        if($this->budgetSheetsService->deleteBudgetSheet($budgetSheetId)) {
            $this->redirect(['budget-sheets/index']);
        } else {
            throw new Exception('Failed to delete budget sheet.');
        }
    }
    public function actionShow()
    {
        [
            'budgetSheet' => $budgetSheet,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'daysInMonth' => $daysInMonth,
            'expenses' => $expenses,
            'incomes' => $incomes
        ] = $this->budgetSheetsService->showBudgetSheet(Yii::$app->request->get('id'));

        return $this->render('sheet-show', ['budgetSheet'=>$budgetSheet,
            'currentMonth'=>$currentMonth,
            'currentYear'=>$currentYear,
            'daysInMonth'=>$daysInMonth,
            'expenses'=>$expenses,
            'incomes'=>$incomes]);
    }
}