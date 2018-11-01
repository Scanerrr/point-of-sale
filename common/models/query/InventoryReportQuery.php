<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\InventoryReport]].
 *
 * @see \common\models\InventoryReport
 */
class InventoryReportQuery extends \yii\db\ActiveQuery
{
    /**
     * @param int $id
     * @return InventoryReportQuery
     */
    public function forUser(int $id)
    {
        return $this->andWhere(['user_id' => $id]);
    }

    /**
     * @param int $locationId
     * @param int $productId
     * @return InventoryReportQuery
     */
    public function forInventory(int $locationId, int $productId)
    {
        return $this->forLocation($locationId)->forProduct($productId);
    }

    public function forLocation(int $id)
    {
        return $this->andWhere(['location_id' => $id]);
    }

    public function forProduct(int $id)
    {
        return $this->andWhere(['product_id' => $id]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\InventoryReport[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\InventoryReport|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
