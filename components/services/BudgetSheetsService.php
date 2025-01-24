<?php

namespace app\components\services;

use app\models\BudgetSheet;
use Yii;
use app\models\CreateBudgetSheet;
use app\models\Category;
use app\models\enums\CategoryType;
use app\models\Transaction;
use Yii\web\NotFoundHttpException;

class BudgetSheetsService
{
    /**
     * Returns all budget sheets for a given user.
     *
     * @param int|null $userId The ID of the user to retrieve budget sheets for. If not provided, the current user will be used.
     * @return BudgetSheet[] An array of budget sheets for the given user.
     */
    public function getBudgetSheets(?int $userId = null): array
    {
        $userId = $userId ?? Yii::$app->user->id;

        return BudgetSheet::findAll(['user_id' => $userId]);
    }

    /**
     * Retrieves a budget sheet by its ID.
     *
     * @param int $budgetSheetId The ID of the budget sheet to retrieve.
     * @return BudgetSheet|null The budget sheet model if found, or null if not found.
     */
    public function getBudgetSheetById(int $budgetSheetId): ?BudgetSheet
    {
        return BudgetSheet::findOne(['id' => $budgetSheetId]);
    }

    /**
     * Binds a budget sheet to a form model based on its ID.
     *
     * @param int $budgetSheetId The ID of the budget sheet to bind.
     * @return CreateBudgetSheet The form model populated with the budget sheet data.
     * @throws NotFoundHttpException If the budget sheet is not found.
     */
    public function bindCreateBudgetSheetForm(int $budgetSheetId): CreateBudgetSheet
    {
        $budgetSheet = $this->getBudgetSheetById($budgetSheetId);
        if ($budgetSheet) {
            $model = new CreateBudgetSheet();
            $model->name = $budgetSheet->name;
            return $model;
        } else {
            throw new NotFoundHttpException('Budget sheet not found.');
        }
    }

    /**
     * Creates a new budget sheet.
     *
     * @param CreateBudgetSheet $model The model containing the budget sheet data.
     * @return bool Whether the creation was successful.
     */
    public function createBudgetSheet(CreateBudgetSheet $model): bool
    {
        $budgetSheet = new BudgetSheet();
        $budgetSheet->user_id = Yii::$app->user->id;
        $budgetSheet->name = $model->name;
        $budgetSheet->created_at = date('Y-m-d H:i:s', time());
        $budgetSheet->updated_at = date('Y-m-d H:i:s', time());
        return $budgetSheet->save();
    }

    /**
     * Updates the specified budget sheet with new data.
     *
     * @param int $budgetSheetId The ID of the budget sheet to update.
     * @param CreateBudgetSheet $model The model containing the updated budget sheet data.
     * @return bool Whether the update was successful.
     * @throws NotFoundHttpException If the budget sheet with the given ID does not exist.
     */
    public function updateBudgetSheet(int $budgetSheetId, CreateBudgetSheet $model): bool
    {
        $budgetSheet = $this->getBudgetSheetById($budgetSheetId);
        if ($budgetSheet) {
            $budgetSheet->name = $model->name;
            $budgetSheet->updated_at = date('Y-m-d H:i:s', time());
            return $budgetSheet->save();
        } else {
            throw new NotFoundHttpException('Budget sheet not found.');
        }
    }

    /**
     * Deletes a budget sheet with the given ID.
     *
     * @param int $budgetSheetId The ID of the budget sheet to delete.
     * @return bool Whether the deletion was successful.
     * @throws NotFoundHttpException If the budget sheet with the given ID does not exist.
     */
    public function deleteBudgetSheet(int $budgetSheetId): bool
    {
        $budgetSheet = $this->getBudgetSheetById($budgetSheetId);
        if ($budgetSheet) {
            return $budgetSheet->delete();
        } else {
            throw new NotFoundHttpException('Budget sheet not found.');
        }
    }

    /**
     * Retrieves data for a budget sheet view.
     *
     * @param int $budgetSheetId The ID of the budget sheet to retrieve data for.
     * @return array An array with the following keys:
     *  - `budgetSheet`: The budget sheet model.
     *  - `currentMonth`: The current month, which can be overridden by the `month` GET parameter.
     *  - `currentYear`: The current year, which can be overridden by the `year` GET parameter.
     *  - `daysInMonth`: The number of days in the current month.
     *  - `expenses`: An array of expenses for each day in the current month.
     *  - `incomes`: An array of incomes for each day in the current month.
     */
    public function showBudgetSheet(int $budgetSheetId): array
    {
        $budgetSheet = $this->getBudgetSheetById($budgetSheetId);
        $budgetSheet = $this->getBudgetSheetById(Yii::$app->request->get('id'));
        $currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
        $currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        $daysInMonth = date('t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
        $expenses = [];
        $incomes = [];
        $query = Transaction::find()
            ->where(['>=', 'transaction_date', $currentYear.'-'.$currentMonth.'-01'])
            ->andWhere(['<=', 'transaction_date', $currentYear.'-'.$currentMonth.'-'.$daysInMonth])
            ->andWhere(['sheet_id' => $budgetSheet->id])
            ->all();

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $incomes[$day] = 0;
            $expenses[$day] = 0;
        }

        foreach ($query as $res) {
            $category = Category::findOne($res->category_id);
            if ($category->type == CategoryType::Income->value) {
                $incomes[date('j', strtotime($res->transaction_date))] += $res->amount;
            } else {
                $expenses[date('j', strtotime($res->transaction_date))] += $res->amount;
            }
        }
        return compact('budgetSheet', 'currentMonth', 'currentYear', 'daysInMonth', 'expenses', 'incomes');
    }
}