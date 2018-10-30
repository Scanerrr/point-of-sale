<?php

namespace common\models;

use Yii;
use yii\db\{ActiveQuery, ActiveRecord};
use common\models\query\InventoryLogQuery;

/**
 * This is the model class for table "inventory_log".
 *
 * @property int $id
 * @property int $inventory_id
 * @property int $user_id
 * @property int $update
 * @property string $created_at
 *
 * @property Inventory $inventory
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
            [['inventory_id'], 'required'],
            [['inventory_id', 'user_id', 'update'], 'integer'],
            [['created_at'], 'safe'],
            [['inventory_id'], 'exist', 'skipOnError' => true, 'targetClass' => Inventory::class, 'targetAttribute' => ['inventory_id' => 'id']],
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
            'inventory_id' => 'Inventory ID',
            'user_id' => 'User ID',
            'update' => 'Update',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getInventory()
    {
        return $this->hasOne(Inventory::class, ['id' => 'inventory_id']);
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
