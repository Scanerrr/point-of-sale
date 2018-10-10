<?php

namespace common\models;

use Yii;
use common\models\query\LocationUserQuery;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "location_user".
 *
 * @property int $id
 * @property int $location_id
 * @property int $user_id
 *
 * @property Location $location
 * @property User $user
 */
class LocationUser extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_id', 'user_id'], 'required'],
            [['location_id', 'user_id'], 'integer'],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::class, 'targetAttribute' => ['location_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['location_id', 'user_id'], 'unique', 'targetAttribute' => ['location_id', 'user_id']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'location_id' => 'Location',
            'user_id' => 'User',
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
     * @return LocationUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LocationUserQuery(get_called_class());
    }

    /**
     * save relations between location and user
     *
     * @param int $locationID
     * @param array $employees
     * @return bool
     */
    public static function makeRelation(int $locationID, array $employees)
    {
        if (!$employees || !$locationID) return false;
        foreach ($employees as $employee) {
            $locationUser = new self();
            $locationUser->location_id = $locationID;
            $locationUser->user_id = $employee;
            $locationUser->save();
        }
        return true;
    }
}
