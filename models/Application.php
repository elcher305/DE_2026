<?php

namespace app\models;

use Yii;


class Application extends \yii\db\ActiveRecord
{


    const STATUS_NEW = 'new';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_REJECTED = 'rejected';


    public static function tableName()
    {
        return 'application';
    }


    public function rules()
    {
        return [
            [['rejection_reason'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'new'],
            [['user_id', 'car_number', 'description'], 'required'],
            [['user_id'], 'integer'],
            [['description', 'status', 'rejection_reason'], 'string'],
            [['car_number'], 'string', 'max' => 20],
            [['user_id', 'status'], 'safe'],
            [['description', 'rejection_reason'], 'string'],
            ['car_number', 'match', 'pattern' => '/^[A-z0-9]\w*$/i',
                'message' => 'Введите корректный госномер '],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_number' => 'Госномер автомобиля',
            'description' => 'Описание нарушения',
            'status' => 'Статус',
            'rejection_reason' => 'Причина отклонения',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    public static function optsStatus()
    {
        return [
            self::STATUS_NEW => 'new',
            self::STATUS_CONFIRMED => 'confirmed',
            self::STATUS_REJECTED => 'rejected',
        ];
    }

    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    public function isStatusNew()
    {
        return $this->status === self::STATUS_NEW;
    }

    public function setStatusToNew()
    {
        $this->status = self::STATUS_NEW;
    }

    public function isStatusConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function setStatusToConfirmed()
    {
        $this->status = self::STATUS_CONFIRMED;
    }

    public function isStatusRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function setStatusToRejected()
    {
        $this->status = self::STATUS_REJECTED;
    }

    public function getStatusLabel()
    {
        $labels = [
            'new' => 'Новое',
            'confirmed' => 'Подтверждено',
            'rejected' => 'Отклонено',
        ];
        return $labels[$this->status] ?? $this->status;
    }
}
