<?php

namespace app\components\services;

use app\models\CreateRecurringTransactionForm;
use app\models\CreateTransaction;
use app\models\Transaction;
use yii\db\Exception;
use Yii\web\NotFoundHttpException;
use Yii;
use app\models\enums\RecurringExpencePeriod;

class TransactionsService
{
    private CategoriesService $categoriesService;

    public function __construct(
        CategoriesService $categoriesService
    ) {
        $this->categoriesService = $categoriesService;
    }

    /**
     * Finds all transactions with given date.
     *
     * @param int $sheetId
     * @param string $date
     * @return array
     */
    public function findByDate(int $sheetId, string $date): array
    {
        $transactions = [];

        $query = Transaction::find()
            ->where(['transaction_date' => $date, 'sheet_id' => $sheetId])
            ->all();

        if ($query) {
            foreach ($query as $transaction) {
                $transactions[$transaction->id] = [
                    'data' => $transaction,
                    'category' => $this->categoriesService->getCategoryById($transaction->category_id),
                ];
            }
        }

        return $transactions;
    }

    /**
     * Creates a new transaction record in the database.
     *
     * @param CreateTransaction $transactionData The model containing transaction details.
     * @return bool Whether the transaction was successfully saved.
     */
    public function createTransaction(CreateTransaction $transactionData): bool
    {
        $transaction = new Transaction();
        $transaction->sheet_id = $transactionData->sheet_id;
        $transaction->category_id = $transactionData->category_id;
        $transaction->amount = (float) $transactionData->amount;
        $transaction->transaction_date = $transactionData->transaction_date;
        $transaction->description = $transactionData->description;
        $transaction->created_at = date('Y-m-d H:i:s');
        $transaction->updated_at = date('Y-m-d H:i:s');

        return $transaction->save();
    }


    /**
     * Updates an existing transaction record in the database.
     *
     * @param int $id The ID of the transaction to update.
     * @param CreateTransaction $model The model containing new transaction details.
     * @return bool Whether the transaction was successfully updated.
     * @throws NotFoundHttpException If the transaction is not found.
     */
    public function updateTransaction(int $id, CreateTransaction $model): bool
    {
        $transaction = $this->getTransactionById($id);
        if ($transaction) {
            $transaction->sheet_id = $model->sheet_id;
            $transaction->category_id = $model->category_id;
            $transaction->amount = (float) $model->amount;
            $transaction->transaction_date = $model->transaction_date;
            $transaction->description = $model->description;
            $transaction->updated_at = date('Y-m-d H:i:s');
        } else {
            throw new NotFoundHttpException('Transaction not found.');
        }

        return $transaction->save();
    }

    /**
     * Deletes an existing transaction record from the database.
     *
     * @param int $transactionId The ID of the transaction to delete.
     * @return bool Whether the transaction was successfully deleted.
     */
    public function deleteTransaction(int $transactionId): bool
    {
        return Transaction::findOne($transactionId)->delete();
    }

    /**
     * Returns a CreateTransaction model populated with the data from the specified transaction ID.
     *
     * @param int $transactionId The ID of the transaction to bind.
     * @return CreateTransaction The model populated with the transaction data.
     */
    public function bindCreateTransactionForm(int $transactionId): CreateTransaction
    {
        $transaction = $this->getTransactionById($transactionId);
        
        if($transaction) {
            $model = new CreateTransaction();
            $model->sheet_id = $transaction->sheet_id;
            $model->category_id = $transaction->category_id;
            $model->amount = $transaction->amount;
            $model->transaction_date = $transaction->transaction_date;
            $model->description = $transaction->description;
        } else {
            throw new NotFoundHttpException('Transaction not found.');
        }
        
        return $model;
    }

    /**
     * Creates a recurring transaction record in the database.
     *
     * @param CreateRecurringTransactionForm $model The model containing transaction details.
     * @return bool|Exception Whether the transaction was successfully saved. If the transaction ended with error, the method will return an Exception object with the error message.
     */
    public function createRecurringTransaction(CreateRecurringTransactionForm $model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $startDate = strtotime($model->transaction_date);
            $record = new Transaction();
            $record->sheet_id = $model->sheet_id;
            $record->category_id = $model->category_id;
            $record->amount = floatval($model->amount);
            $record->transaction_date = date('Y-m-d H:i:s', $startDate);
            $record->description = $model->description;
            $record->created_at = date('Y-m-d H:i:s');
            $record->updated_at = date('Y-m-d H:i:s');

            if (!$record->save()) {
                throw new Exception('Failed to save the first transaction: ' . json_encode($record->getErrors()));
            }

            for ($i = 1; $i < $model->quantity; $i++) {
                $newRecord = new Transaction();
                $newRecord->sheet_id = $model->sheet_id;
                $newRecord->category_id = $model->category_id;
                $newRecord->amount = floatval($model->amount);
                $newRecord->transaction_date = date('Y-m-d H:i:s', $this->getNewDate($startDate, $model->period, $i));
                $newRecord->description = $model->description;
                $newRecord->created_at = date('Y-m-d H:i:s');
                $newRecord->updated_at = date('Y-m-d H:i:s');

                if (!$newRecord->save()) {
                    throw new Exception('Failed to save recurring transaction: ' . json_encode($newRecord->getErrors()));
                }
            }
            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            return $exception;
        }

        return true;
    }

    private function getNewDate(int $startDate, string $period, int $i): int
    {
        switch ($period) {
            case RecurringExpencePeriod::DAILY->value:
                return strtotime(date('Y-m-d H:i:s', $startDate) . "+$i days");
            case RecurringExpencePeriod::WEEKLY->value:
                return strtotime(date('Y-m-d H:i:s', $startDate) . "+$i*6 days");
            case RecurringExpencePeriod::MONTHLY->value:
                return strtotime(date('Y-m-d H:i:s', $startDate) . "+$i months");
            case RecurringExpencePeriod::YEARLY->value:
                return strtotime(date('Y-m-d H:i:s', $startDate) . "+$i years");
        }
    }

    public function getTransactionById(int $transactionId): ?Transaction
    {
        return Transaction::findOne($transactionId);
    }
}
