<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\enums\TransactionType;

class Transaction extends ActiveRecord{
    public static function tableName(){
        return '{{transaction}}';
    }
}