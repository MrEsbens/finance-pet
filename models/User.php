<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{

    public static function tableName(): string
    {
        return '{{users}}';
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static  function  findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }
}
