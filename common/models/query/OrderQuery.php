<?php

namespace common\models\query;

use common\models\Order;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[\common\models\Order]].
 *
 * @see \common\models\Order
 */
class OrderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    public function complete()
    {
        return $this->forStatus(Order::STATUS_COMPLETE);
    }

    /**
     * @param int $id Status id
     * @return OrderQuery
     */
    public function forStatus(int $id)
    {
        return $this->andWhere(['status' => $id]);
    }

    public function forLocation(int $id)
    {
        return $this->andWhere(['location_id' => $id]);
    }

    public function forDateRange($from, $to)
    {
        // using STR_TO_DATE allow to use index
        return $this->andWhere([
            'between',
            'created_at',
            new Expression('STR_TO_DATE("' . $from . '", "%Y-%m-%d %H:%i:%s")'),
            new Expression('STR_TO_DATE("' . date('Y-m-d 23:59:59', strtotime($to)) . '", "%Y-%m-%d %H:%i:%s")')
        ]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Order[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Order|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
