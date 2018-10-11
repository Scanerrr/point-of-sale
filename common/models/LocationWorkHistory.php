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
 * @property int $event_id 1-opened, 2-closed, 3-clock-in, 4-clock-out
 * @property string $created_at
 *
 * @property Location $location
 * @property User $user
 */
class LocationWorkHistory extends \yii\db\ActiveRecord
{
    const EVENT_OPENED = 1;
    const EVENT_CLOSED = 2;
    const EVENT_WORKING = 3;
    const EVENT_NOT_WORKING = 4;

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
            [['location_id', 'user_id', 'event_id'], 'required'],
            [['location_id', 'user_id', 'event_id'], 'integer'],
            [['created_at'], 'safe'],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::class, 'targetAttribute' => ['location_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
//            [['event_id'], 'in', 'range' => [self::EVENT_OPENED, self::EVENT_CLOSED]],
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
            'event_id' => 'Event',
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

    /**
     * @param int $locationId
     * @param int $userId
     * @param bool $value
     * @param string $event
     * @return array
     */
    public static function saveHistory(int $locationId, int $userId, bool $value, string $event): array
    {
        $locationHistory = new self();
        $locationHistory->location_id = $locationId;
        $locationHistory->user_id = $userId;
        switch (strtolower($event)) {
            case 'location':
                $eventId = $value ? self::EVENT_OPENED : self::EVENT_CLOSED;
                break;
            case 'user':
            case 'employee':
                $eventId = $value ? self::EVENT_WORKING : self::EVENT_NOT_WORKING;
                break;
            default:
                return ['Event not found'];
        }
        $locationHistory->event_id = $eventId;

        $error = [];

        if (!$locationHistory->save()) $error = $locationHistory->getErrors();

        return $error;
    }
}
