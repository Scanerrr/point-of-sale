<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\InventoryLog]].
 *
 * @see \common\models\InventoryLog
 */
class InventoryLogQuery extends \yii\db\ActiveQuery
{
    /**
     * @param int $userId
     * @return InventoryLogQuery
     */
    public function forUser(int $userId)
    {
        return $this->andWhere(['user_id' => $userId]);
    }

    /**
     * @param int $inventoryId
     * @return InventoryLogQuery
     */
    public function forInventory(int $inventoryId)
    {
        return $this->andWhere(['inventory_id' => $inventoryId]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\InventoryLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\InventoryLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
