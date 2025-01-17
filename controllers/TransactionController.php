<?php

namespace app\controllers;

use app\models\Category;
use app\models\enums\RecurringExpencePeriod;
use Yii;
use yii\web\Controller;
use app\models\Transaction;
use app\models\CreateTransaction;
use app\models\CreateRecurringExpenseForm;
use DateInterval;
use DateTime;
use yii\web\NotFoundHttpException;
use yii\db\Exception;

class TransactionController extends Controller
{
    public function actionShow()
    {
        if(empty(Yii::$app->request->get('date'))) {
            $date = sprintf('%04d-%02d-%02d', Yii::$app->request->get('year'), Yii::$app->request->get('month'), Yii::$app->request->get('day'));
        } else {
            $date = Yii::$app->request->get('date');
        }

        $query = Transaction::find()->where(['transaction_date' => $date, 'sheet_id' => Yii::$app->request->get('sheet_id')])->all();
        if($query) {
            foreach($query as $item){
                $transaction[$item->id]['data'] = $item;
                $transaction[$item->id]['category'] = Category::findOne($item->category_id);
            }
        } else {
            $transaction = [];
        }

        return $this->render('show-transactions', [
            'sheet_id' => Yii::$app->request->get('sheet_id'),
            'date' => $date,
            'transactions' => $transaction
        ]);
    }
    public function actionCreate()
    {
        $model = new CreateTransaction();
        $categories = Category::findAll(['user_id' => Yii::$app->user->id]);
        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = new Transaction();
            $transaction->sheet_id = $model->sheet_id;
            $transaction->category_id = $model->category_id;
            $transaction->amount = floatval($model->amount);
            $transaction->transaction_date = $model->transaction_date;
            $transaction->description = $model->description;
            $transaction->created_at = date('Y-m-d H:i:s', time());
            $transaction->updated_at = date('Y-m-d H:i:s', time());

            if($transaction->save()) {
                return $this->redirect(['transaction/show', 'date' =>  Yii::$app->request->get('date'), 'sheet_id' => Yii::$app->request->get('sheet_id')]);
            } else {
                throw new NotFoundHttpException('Запись не найдена');
            }
        }

        return $this->render('create-transaction', 
            ['transaction' => $model, 
            'action' => 'create', 
            'date' =>  Yii::$app->request->get('date'), 
            'sheet_id' => Yii::$app->request->get('sheet_id'), 
            'categories' => $categories]);
    }
    public function actionUpdate()
    {
        $model = new CreateTransaction();
        $transaction = Transaction::findOne(Yii::$app->request->get('id'));
        $model->sheet_id = $transaction->sheet_id;
        $model->category_id = $transaction->category_id;
        $model->amount = $transaction->amount;
        $model->transaction_date = $transaction->transaction_date;
        $model->description = $transaction->description;
        $categories = Category::findAll(['user_id' => Yii::$app->user->id]);
        $date = Yii::$app->request->get('date');

        if($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction->sheet_id = $model->sheet_id;
            $transaction->category_id = $model->category_id;
            $transaction->amount = floatval($model->amount);
            $transaction->transaction_date = $model->transaction_date;
            $transaction->description = $model->description;
            $transaction->updated_at = date('Y-m-d H:i:s', time());

            if($transaction->save()) {
                return $this->redirect(['transaction/show', 'date' => $date, 'sheet_id' => Yii::$app->request->get('sheet_id')]);
            } else {
                throw new NotFoundHttpException('Запись не найдена');
            }
        }

        return  $this->render('create-transaction', ['transaction' => $model, 'action' => 'update', 'date' => $date, 'sheet_id' => $model->sheet_id, 'categories' => $categories]);
    }
    public function actionDelete()
    {
        $transaction = Transaction::findOne(Yii::$app->request->get('id'));
        if($transaction->delete()) {
            return $this->redirect(['transaction/show', 'date'=>Yii::$app->request->get('date') ]);
        } else {
            throw new NotFoundHttpException('Запись не найдена');
        }
    }
    public function actionCreateRecurringExpense() 
    {
        $model = new CreateRecurringExpenseForm();
        $categories = Category::findAll(['user_id' => Yii::$app->user->id]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $date = strtotime($model->transaction_date);
                $record = new Transaction();
                $record->sheet_id = $model->sheet_id;
                $record->category_id = $model->category_id;
                $record->amount = floatval($model->amount);
                $record->transaction_date = date('Y-m-d H:i:s', $date);
                $record->description = $model->description;
                $record->created_at = date('Y-m-d H:i:s');
                $record->updated_at = date('Y-m-d H:i:s');

                if (!$record->save()) {
                    throw new Exception('Не удалось сохранить первую транзакцию: ' . json_encode($record->errors));
                }

                for ($i = 1; $i < intval($model->quantity); $i++) {
                    $newRecord = new Transaction();
                    $newRecord->sheet_id = $model->sheet_id;
                    $newRecord->category_id = $model->category_id;
                    $newRecord->amount = floatval($model->amount);

                    switch ($model->period) {
                        case RecurringExpencePeriod::DAILY->value:
                            $newRecord->transaction_date = date('Y-m-d H:i:s', strtotime($record->transaction_date . "+$i days"));
                            break;
                        case RecurringExpencePeriod::WEEKLY->value:
                            $newRecord->transaction_date = date('Y-m-d H:i:s', strtotime($record->transaction_date . "+" . $i*6 . " days"));
                            break;
                        case RecurringExpencePeriod::MONTHLY->value:
                            $newRecord->transaction_date = date('Y-m-d H:i:s', strtotime($record->transaction_date . "+$i months"));
                            break;
                        case RecurringExpencePeriod::YEARLY->value:
                            $newRecord->transaction_date = date('Y-m-d H:i:s', strtotime($record->transaction_date . "+$i years"));
                            break;
                    }

                    $newRecord->description = $model->description;
                    $newRecord->created_at = date('Y-m-d H:i:s');
                    $newRecord->updated_at = date('Y-m-d H:i:s');

                    if (!$newRecord->save()) {
                        throw new Exception('Ошибка при сохранении повторяющейся транзакции: ' . json_encode($newRecord->errors));
                    }
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                return "Transaction ended with error and has been rollbacked. Message: " . $e->getMessage();
            }

            return $this->redirect(['budget-sheets/show', 'id' => $model->sheet_id]);
        }

        return $this->render('create-recurring-expense', 
            ['model' => $model,
            'categories' => $categories,
            'sheet_id' => Yii::$app->request->get('sheet_id')
            ]);
    }    
}