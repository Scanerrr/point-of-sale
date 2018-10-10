<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\LocationWorkHistory]].
 *
 * @see \common\models\LocationWorkHistory
 */
class LocationWorkHistoryQuery extends \yii\db\ActiveQuery
{
    public function forEvent(int $event)
    {
        return $this->andWhere(['event' => $event]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\LocationWorkHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\LocationWorkHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
