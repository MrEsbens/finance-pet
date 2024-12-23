<?php

namespace app\model;

use yii\db\ActiveRecord;

class Category extends ActiveRecord{
    public static function tableName(){
        return  '{{categories}}';
    }
}