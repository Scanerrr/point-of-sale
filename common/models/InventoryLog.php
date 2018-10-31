<?php

namespace common\models;

use Yii;
use yii\db\{ActiveQuery, ActiveRecord};
use common\models\query\InventoryLogQuery;

/**
 * This is the model class for table "inventory_log".
 *
 * @property int $id
 * @property int $location_id
 * @property int $product_id
 * @property int $user_id
 * @property int $quantity
 * @property string $comment
 * @property string $created_at
 *
 * @property Inventory $inventory
 * @property Location $location
 * @property Product $product
 * @property User $user
 */
class InventoryLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_id', 'product_id'], 'required'],
            [['location_id', 'product_id', 'user_id', 'quantity'], 'integer'],
            [['created_at'], 'safe'],
            [['comment'], 'string'],
            [['location_id', 'product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Inventory::class, 'targetAttribute' => ['location_id' => 'location_id', 'product_id' => 'product_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'product_id' => 'Product ID',
            'user_id' => 'User ID',
            'quantity' => 'Quantity',
            'comment' => 'Comment',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventory()
    {
        return $this->hasOne(Inventory::class, ['location_id' => 'location_id', 'product_id' => 'product_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::class, ['id' => 'location_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return InventoryLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InventoryLogQuery(get_called_class());
    }
}
