<?php

namespace app\models;

use yii\db\ActiveRecord;

class BudgetSheet extends ActiveRecord
{
    public static function tableName()
    {
        return '{{budget_sheets}}';
    }
}