<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_payment".
 *
 * @property int $id
 * @property int $order_id
 * @property int $method_id
 * @property string $details
 * @property string $amount
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Order $order
 * @property PaymentMethod $method
 */
class OrderPayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'method_id'], 'required'],
            [['order_id', 'method_id'], 'integer'],
            [['details'], 'string'],
            [['amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
            [['method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::class, 'targetAttribute' => ['method_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'method_id' => 'Method ID',
            'details' => 'Details',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMethod()
    {
        return $this->hasOne(PaymentMethod::class, ['id' => 'method_id']);
    }
}
