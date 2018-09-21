<?php

namespace common\models;

use common\models\query\LocationQuery;
use Yii;

/**
 * This is the model class for table "location".
 *
 * @property int $id
 * @property string $prefix
 * @property string $name
 * @property int $region_id
 * @property string $email
 * @property string $phone
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $address
 * @property string $zip
 * @property string $tax_rate
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Region $region
 * @property LocationUser[] $locationUsers
 */
class Location extends \yii\db\ActiveRecord
{

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['prefix', 'name', 'region_id', 'email', 'country', 'state'], 'required'],
            [['region_id', 'status'], 'integer'],
            [['tax_rate'], 'number', 'min' => 0],
            ['tax_rate', 'default', 'value' => 0],
            [['created_at', 'updated_at'], 'safe'],
            [['prefix', 'phone', 'country', 'state', 'city', 'address'], 'string', 'max' => 255],
            [['name', 'email'], 'string', 'max' => 64],
            [['zip'], 'string', 'max' => 5],
            [['prefix'], 'unique'],
            [['email'], 'unique'],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Region::className(), 'targetAttribute' => ['region_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prefix' => 'Prefix',
            'name' => 'Name',
            'region_id' => 'Region ID',
            'email' => 'Email',
            'phone' => 'Phone',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'address' => 'Address',
            'zip' => 'Zip',
            'tax_rate' => 'Tax Rate',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationUsers()
    {
        return $this->hasMany(LocationUser::className(), ['location_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return LocationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LocationQuery(get_called_class());
    }
}
