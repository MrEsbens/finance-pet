<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\enums\TransactionType;

class Transaction extends ActiveRecord
{
    public int $id;
    public int $sheet_id;
    public int $category_id;
    public float $amount;
    public string $type;
    public TransactionType $transaction_date;
    public string $description;
    public string $created_at;
    public string $updated_at;

    public static function tableName()
    {
        return '{{transaction}}';
    }
}