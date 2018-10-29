<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_method".
 *
 * @property int $id
 * @property int $type_id
 * @property string $name
 *
 * @property Order[] $orders
 * @property bool $isCash
 */
class PaymentMethod extends \yii\db\ActiveRecord
{
    const TYPE_CASH = 0;
    const TYPE_CREDIT_CARD = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_id'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['type_id'], 'in', 'range' => [self::TYPE_CASH, self::TYPE_CREDIT_CARD]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['payment_method_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function getIsCash()
    {
        return $this->type_id === self::TYPE_CASH;
    }

    public static function getTypeIdById(int $id): int
    {
        return self::find()->select('type_id')->where(['id' => $id])->scalar();
    }
}
