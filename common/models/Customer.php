<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $phone
 * @property string $gender
 * @property string $email
 * @property int $added_by
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $address
 * @property string $zip
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $addedBy
 * @property Order[] $orders
 */
class Customer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender'], 'string'],
            [['added_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['firstname', 'lastname', 'phone', 'email', 'country', 'state', 'city', 'address'], 'string', 'max' => 255],
            [['zip'], 'string', 'max' => 5],
            [['added_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['added_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'phone' => 'Phone',
            'gender' => 'Gender',
            'email' => 'Email',
            'added_by' => 'Added By',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'address' => 'Address',
            'zip' => 'Zip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddedBy()
    {
        return $this->hasOne(User::class, ['id' => 'added_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['customer_id' => 'id']);
    }
}
