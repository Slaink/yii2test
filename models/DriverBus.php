<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "drivers_buses".
 *
 * @property int $driver_id
 * @property int $bus_id
 *
 * @property Bus $bus
 * @property Driver $driver
 */
class DriverBus extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'drivers_buses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['driver_id', 'bus_id'], 'required'],
            [['driver_id', 'bus_id'], 'integer'],
            [['driver_id', 'bus_id'], 'unique', 'targetAttribute' => ['driver_id', 'bus_id']],
            [['bus_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bus::class, 'targetAttribute' => ['bus_id' => 'id']],
            [['driver_id'], 'exist', 'skipOnError' => true, 'targetClass' => Driver::class, 'targetAttribute' => ['driver_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'driver_id' => Yii::t('app', 'Driver ID'),
            'bus_id' => Yii::t('app', 'Bus ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBus()
    {
        return $this->hasOne(Bus::class, ['id' => 'bus_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDriver()
    {
        return $this->hasOne(Driver::class, ['id' => 'driver_id']);
    }
}
