<?php

namespace app\models;

use Yii;
use yii\base\Model;

class CreateCategory extends Model{
    public $name;
    public $type;
    public function rules(){
        return [
            [['name', 'type'], 'required'],
        ];
    }
}