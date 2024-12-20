<?php

namespace app\model;

use yii\db\ActiveRecord;

class Category extends ActiveRecord
{
    public int $id;
    public int $user_id;
    public string $name;
    public string $created_at;
    public string $updated_at;
    public static function tableName()
    {
        return  '{{categories}}';
    }
}