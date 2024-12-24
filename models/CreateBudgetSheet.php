<?php

namespace app\models;

use Yii;
use yii\base\Model;

class CreateBudgetSheet extends Model{
    public $name;
    public function rules(){
        return [
            [['name'], 'required'],
        ];
    }
}