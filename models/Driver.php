<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "drivers".
 *
 * @property int $id
 * @property string $summary
 * @property string $photo
 * @property string $birthday
 * @property int $is_active
 *
 * @property Bus[] $buses
 */
class Driver extends ActiveRecord
{
    /**
     * Водитель может ехать не более 8 часов в сутки
     */
    const MAX_HOURS_DRIVE_PER_DAY = 8;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'drivers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['summary', 'birthday'], 'required'],
            [['summary', 'birthday'], 'unique', 'targetAttribute' => ['summary', 'birthday']],
            [['birthday'], 'safe'],
            [['is_active'], 'integer'],
            [['summary'], 'string', 'max' => 255],
            [
                ['photo'],
                'file',
                'extensions' => 'png, jpg, jpeg, gif, bmp',
                'maxFiles' => 1
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'summary' => Yii::t('app', 'Summary'),
            'photo' => Yii::t('app', 'Photo'),
            'birthday' => Yii::t('app', 'Birthday'),
            'is_active' => Yii::t('app', 'Is Active'),
            'buses' => Yii::t('app', 'Buses'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuses()
    {
        return $this->hasMany(Bus::class, ['id' => 'bus_id'])->viaTable('drivers_buses', ['driver_id' => 'id'])->orderBy(['avg_speed' => SORT_DESC]);
    }

    /**
     * @return array
     */
    public function getBusesIds()
    {
        return ArrayHelper::getColumn($this->buses, 'id');
    }

    /**
     * @return string
     */
    public function getAge()
    {
        $now = new \DateTime();
        $birth_date = \DateTime::createFromFormat('Y-m-d', $this->birthday);
        $diff = $birth_date->diff($now);

        return $diff->format('%y');
    }

    /**
     * @return string
     */
    public function getBusesInfo()
    {
        $content = '';
        foreach ($this->buses as $bus) {
            $content .= '<p>' . $bus->title . ' - ' . $bus->avg_speed . ' ' . Yii::t('app', 'km/h') . '</p>';
        }

        return $content;
    }

    /**
     * Возвращает минимальное количество дней, за которое водитель сможет проехать расстояние
     * @param $distance
     *
     * @return float|int
     */
    public function getDistanceTime($distance)
    {
        if (!$this->buses) {
            return 0;
        }
        $distance = $distance / 1000; //переводим в км
        $bus = reset($this->buses);

        return ceil($distance / $bus->avg_speed / self::MAX_HOURS_DRIVE_PER_DAY);
    }
}
