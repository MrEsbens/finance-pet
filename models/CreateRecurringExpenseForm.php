<?php
namespace app\models;

use yii\base\Model;

class CreateRecurringExpenseForm extends Model
{
    public $sheet_id;
    public $category_id;
    public $amount;
    public $transaction_date;
    public $description;
    public $period;
    public $quantity;
    public function rules()
    {
        return [
            [['sheet_id','category_id', 'amount', 'transaction_date', 'period', 'quantity'], 'required'],
            ['description', 'safe'],
        ];
    }
}