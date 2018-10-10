<?php

namespace common\models;

use common\models\query\LocationWorkHistoryQuery;
use Yii;

/**
 * This is the model class for table "location_work_history".
 *
 * @property int $id
 * @property int $location_id
 * @property int $user_id
 * @property int $event 0-opened, 1-closed
 * @property string $created_at
 *
 * @property Location $location
 * @property User $user
 */
class LocationWorkHistory extends \yii\db\ActiveRecord
{
    const EVENT_OPENED = 0;
    const EVENT_CLOSED = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location_work_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_id', 'user_id', 'event'], 'required'],
            [['location_id', 'user_id', 'event'], 'integer'],
            [['created_at'], 'safe'],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::class, 'targetAttribute' => ['location_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['event'], 'in', 'range' => [self::EVENT_OPENED, self::EVENT_CLOSED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'location_id' => 'Location ID',
            'user_id' => 'User ID',
            'event' => 'Event',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::class, ['id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return LocationWorkHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LocationWorkHistoryQuery(get_called_class());
    }
}
