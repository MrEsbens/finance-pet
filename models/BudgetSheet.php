<?php

namespace app\models;

use yii\db\ActiveRecord;

class BudgetSheet extends ActiveRecord
{
    public int $id;
    public int $user_id;
    public string $name;
    public string $created_at;
    public string $updated_at;
    public static function tableName()
    {
        return '{{budget_sheets}}';
    }
}