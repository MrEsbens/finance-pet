<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface{

    public static function tableName(): string{
        return '{{users}}';
    }

    public static function findIdentity($id){
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return static::findOne(['access_token' => $token]);
    }

    public function getId(): string{
        return $this->id;
    }

    public function getAuthKey(): string{
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool{
        return $this->auth_key === $authKey;
    }
}
