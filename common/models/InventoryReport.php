<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\models\query\InventoryReportQuery;

/**
 * This is the model class for table "inventory_report".
 *
 * @property int $id
 * @property int $location_id
 * @property int $product_id
 * @property int $user_id
 * @property int $reason_id 0 - damaged, 1 - lost, 2 - tester, 3 - other
 * @property int $quantity
 * @property string $comment
 * @property string $created_at
 *
 * @property Inventory $inventory
 * @property Location $location
 * @property Product $product
 * @property User $user
 */
class InventoryReport extends ActiveRecord
{
    const REASON_DAMAGED = 0;
    const REASON_LOST = 1;
    const REASON_TESTER = 2;
    const REASON_OTHER = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_id', 'product_id'], 'required'],
            [['location_id', 'product_id', 'user_id', 'reason_id', 'quantity'], 'integer'],
            [['created_at'], 'safe'],
            [['comment'], 'string', 'max' => 255],
            [['comment'], 'default', 'value' => ''],
            [['location_id', 'product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Inventory::class, 'targetAttribute' => ['location_id' => 'location_id', 'product_id' => 'product_id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::class, 'targetAttribute' => ['location_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
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
            'location_id' => 'Location',
            'product_id' => 'Product',
            'user_id' => 'User',
            'reason_id' => 'Reason',
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return InventoryReportQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InventoryReportQuery(get_called_class());
    }

    public static function reasonList(): array
    {
        return [
            self::REASON_DAMAGED => 'Damaged',
            self::REASON_LOST => 'Lost',
            self::REASON_TESTER => 'Tester',
            self::REASON_OTHER => 'Other',
        ];
    }

    public static function reasonName(int $reason): string
    {
        return ArrayHelper::getValue(self::reasonList(), $reason);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $log = new InventoryLog();
        $log->location_id = $this->location_id;
        $log->product_id = $this->product_id;
        $log->user_id = $this->user_id;
        $log->quantity = $this->quantity;
        $log->comment = $this::reasonName($this->reason_id);
        $log->save();

        parent::afterSave($insert, $changedAttributes);
    }
}
