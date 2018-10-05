<?php

namespace common\models;

use common\models\query\OrderQuery;
use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $invoice
 * @property int $status
 * @property int $location_id
 * @property int $employee_id
 * @property int $customer_id
 * @property string $total_tax
 * @property string $total
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Customer $customer
 * @property User $employee
 * @property Location $location
 * @property OrderProduct[] $orderProducts
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice', 'status', 'location_id', 'employee_id', 'customer_id'], 'integer'],
            [['status', 'location_id', 'employee_id'], 'required'],
            [['total_tax', 'total'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['employee_id' => 'id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice' => 'Invoice',
            'status' => 'Status',
            'location_id' => 'Location ID',
            'employee_id' => 'Employee ID',
            'customer_id' => 'Customer ID',
            'total_tax' => 'Total Tax',
            'total' => 'Total',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(User::class, ['id' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::class, ['order_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderQuery(get_called_class());
    }
}
