<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "buses".
 *
 * @property int $id
 * @property string $title
 * @property int $avg_speed
 *
 * @property Driver[] $drivers
 */
class Bus extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'buses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['avg_speed'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['title'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Bus title'),
            'avg_speed' => Yii::t('app', 'Avg Speed'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDrivers()
    {
        return $this->hasMany(Driver::class, ['id' => 'driver_id'])->viaTable('drivers_buses', ['bus_id' => 'id']);
    }

    public static function getList()
    {
        $models = self::find()->all();

        return ArrayHelper::map($models, 'id', 'title');
    }
}
