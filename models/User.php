<?php

namespace app\models;

use Yii;


class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';


    public static function tableName()
    {
        return 'user';
    }


    public function rules()
    {
        return [
            [['role'], 'default', 'value' => 'user'],
            [['username', 'password', 'full_name', 'phone', 'email'], 'required'],
            [['username', 'full_name', 'email'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 255, 'min' => 8],
            ['username', 'string', 'min' => 6, 'max' => 100],
            [['phone'], 'string', 'max' => 50],
            [['username'], 'unique'],
            ['email', 'email'],
            ['username', 'match', 'pattern' => '/^[A-z0-9]\w*$/i'],
            ['full_name', 'match', 'pattern' => '/^[А-яЁё -]*$/u'],
            ['phone', 'match', 'pattern' => '/^8?\(\d{3}\)\d{3}\-\d{2}\-\d{2}$/'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Логин',
            'password' => 'Пароль',
            'full_name' => 'ФИО',
            'phone' => 'Телефон',
            'email' => 'Адрес электронной почты',
        ];
    }


    public function getApplications()
    {
        return $this->hasMany(Application::class, ['user_id' => 'id']);
    }


    public static function optsRole()
    {
        return [
            self::ROLE_ADMIN => 'admin',
            self::ROLE_USER => 'user',
        ];
    }


    public function displayRole()
    {
        return self::optsRole()[$this->role];
    }


    public function isRoleAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function setRoleToAdmin()
    {
        $this->role = self::ROLE_ADMIN;
    }


    public function isRoleUser()
    {
        return $this->role === self::ROLE_USER;
    }

    public function setRoleToUser()
    {
        $this->role = self::ROLE_USER;
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function beforeSave($insert) {
        $this->password = md5($this->password);
        return parent::beforeSave($insert);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }


    public function getId()
    {
        return $this->id;
    }


    public function getAuthKey()
    {
        return null;
    }


    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }
}
