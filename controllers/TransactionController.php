<?php

namespace app\controllers;

use app\components\services\CategoriesService;
use app\components\services\TransactionsService;
use app\components\services\UserService;
use Yii;
use yii\web\Controller;
use app\models\CreateTransaction;
use app\models\CreateRecurringTransactionForm;
use app\models\User;
use yii\web\NotFoundHttpException;

class TransactionController extends Controller
{
    private TransactionsService $transactionsService;
    private CategoriesService $categoriesService;
    private UserService $userService;

    public function __construct(
        string $id,
        $module,
        TransactionsService $transactionsService,
        CategoriesService $categoriesService,
        UserService $userService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->transactionsService = $transactionsService;
        $this->categoriesService = $categoriesService;
        $this->userService = $userService;
    }
    public function actionShow()
    {
        if($this->userService->isGuest()) {
            return $this->goHome();
        }

        $sheetId = (int) Yii::$app->request->get('sheet_id');
        $date = Yii::$app->request->get('date') ?: sprintf(
            '%04d-%02d-%02d',
            Yii::$app->request->get('year'),
            Yii::$app->request->get('month'),
            Yii::$app->request->get('day')
        );

        $transactions = $this->transactionsService->findByDate($sheetId, $date);

        return $this->render('show-transactions', [
            'sheetId' => $sheetId,
            'date' => $date,
            'transactions' => $transactions,
        ]);
    }
    public function actionCreate()
    {
        $transactionForm = new CreateTransaction();
        $categories = $this->categoriesService->getAllUserCategories();

        if ($transactionForm->load(Yii::$app->request->post()) && $transactionForm->validate()) {
            if ($this->transactionsService->createTransaction($transactionForm)) {
                return $this->redirect([
                    'transaction/show',
                    'date' => Yii::$app->request->get('date'),
                    'sheet_id' => Yii::$app->request->get('sheet_id'),
                ]);
            }
        }

        return $this->render('create-transaction', [
            'transactionForm' => $transactionForm,
            'action' => 'create',
            'date' => Yii::$app->request->get('date'),
            'sheetId' => Yii::$app->request->get('sheet_id'),
            'categories' => $categories,
        ]);
    }
    public function actionUpdate()
    {
        $transactionForm = $this->transactionsService->bindCreateTransactionForm(Yii::$app->request->get('id'));
        $categories = $this->categoriesService->getAllUserCategories();
        $transactionDate = Yii::$app->request->get('date');

        if ($transactionForm->load(Yii::$app->request->post()) && $transactionForm->validate()) {
            if ($this->transactionsService->updateTransaction(Yii::$app->request->get('id'), $transactionForm)) {
                return $this->redirect(['transaction/show', 'date' => $transactionDate, 'sheet_id' => Yii::$app->request->get('sheet_id')]);
            } else {
                throw new NotFoundHttpException('Transaction not found');
            }
        }

        return $this->render('create-transaction', [
            'transactionForm' => $transactionForm,
            'action' => 'update',
            'date' => $transactionDate,
            'sheetId' => $transactionForm->sheet_id,
            'categories' => $categories
        ]);
    }
    public function actionDelete()
    {
        if($this->transactionsService->deleteTransaction(Yii::$app->request->get('id'))) {
            return $this->redirect(['transaction/show', 'date'=>Yii::$app->request->get('date'), 'sheet_id' => Yii::$app->request->get('sheet_id')]);
        } else {
            throw new NotFoundHttpException('Запись не найдена');
        }
    }
    public function actionCreateRecurringTransactions()
    {
        $recurringTransactionForm = new CreateRecurringTransactionForm();
        $categories = $this->categoriesService->getAllUserCategories();

        if ($recurringTransactionForm->load(Yii::$app->request->post()) && $recurringTransactionForm->validate()) {
            $transactionResult = $this->transactionsService->createRecurringTransaction($recurringTransactionForm);

            if ($transactionResult === true) {
                return $this->redirect(['budget-sheets/show', 'id' => $recurringTransactionForm->sheet_id]);
            }
        }

        return $this->render('create-recurring-expense', [
            'model' => $recurringTransactionForm,
            'categories' => $categories,
            'sheetId' => Yii::$app->request->get('sheet_id'),
        ]);
    }
}