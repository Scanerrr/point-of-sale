<?php

namespace common\models;

use common\models\query\OrderQuery;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "order".
 *
 * @property int $id
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
 * @property OrderPayment[] $orderPayments
 * @property OrderProduct[] $orderProducts
 */
class Order extends \yii\db\ActiveRecord
{
    const STATUS_REFUND = -1;
    const STATUS_DELETED = 0;
    const STATUS_NEW = 1;
    const STATUS_PENDING = 2;
    const STATUS_COMPLETE = 3;

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
            [['status', 'location_id', 'employee_id', 'customer_id'], 'integer'],
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
            'status' => 'Status',
            'location_id' => 'Location',
            'employee_id' => 'Employee',
            'customer_id' => 'Customer',
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
    public function getOrderPayments()
    {
        return $this->hasMany(OrderPayment::class, ['order_id' => 'id']);
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

    public static function statusList(): array
    {
        return [
            self::STATUS_DELETED => 'Deleted',
            self::STATUS_NEW => 'New',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_COMPLETE => 'Complete',
        ];
    }

    public static function statusName(int $status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }
}
