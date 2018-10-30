<?php

namespace common\models;

use Yii;
use yii\db\{ActiveQuery, ActiveRecord};

/**
 * This is the model class for table "user_commission".
 *
 * @property int $order_id
 * @property int $user_id
 * @property int $commission_type
 * @property string $commission_value
 *
 * @property Order $order
 * @property User $user
 */
class UserCommission extends ActiveRecord
{
    const COMMISSION_TYPE_FLAT = 0;
    const COMMISSION_TYPE_PRODUCT = 1;
    const COMMISSION_TYPE_FLAT_PRODUCT = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_commission';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id', 'commission_type'], 'required'],
            [['order_id', 'user_id', 'commission_type'], 'integer'],
            [['commission_value'], 'number'],
            [['order_id', 'user_id'], 'unique', 'targetAttribute' => ['order_id', 'user_id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'user_id' => 'User ID',
            'commission_type' => 'Commission Type',
            'commission_value' => 'Commission Value',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
