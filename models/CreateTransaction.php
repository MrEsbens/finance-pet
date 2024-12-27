<?php

namespace app\models;
use yii\base\Model;

class CreateTransaction extends Model{
    public $sheet_id;
    public $category_id;
    public $amount;
    public $transaction_date;
    public $description;
    public function rules(){
        return [
            [['sheet_id','category_id', 'amount', 'transaction_date'], 'required'],
            ['description', 'safe'],
        ];
    }
}