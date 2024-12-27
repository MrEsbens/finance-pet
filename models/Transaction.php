<?php

namespace app\models;

use yii\db\ActiveRecord;

class Transaction extends ActiveRecord{
    public static function tableName(){
        return '{{transactions}}';
    }
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }
}