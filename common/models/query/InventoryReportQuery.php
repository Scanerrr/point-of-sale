<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\InventoryReport]].
 *
 * @see \common\models\InventoryReport
 */
class InventoryReportQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
