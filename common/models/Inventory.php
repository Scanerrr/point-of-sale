<?php

namespace common\models;

use common\models\query\InventoryQuery;
use Yii;

/**
 * This is the model class for table "inventory".
 *
 * @property int $id
 * @property int $location_id
 * @property int $product_id
 * @property int $quantity
 *
 * @property Location $location
 * @property Product $product
 */
class Inventory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_id', 'product_id'], 'required'],
            [['location_id', 'product_id', 'quantity'], 'integer'],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::class, 'targetAttribute' => ['location_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
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
            'product_id' => 'Product',
            'quantity' => 'Quantity',
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
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * {@inheritdoc}
     * @return InventoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InventoryQuery(get_called_class());
    }
}
