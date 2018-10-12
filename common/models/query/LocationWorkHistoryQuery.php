<?php

namespace common\models\query;

use common\models\LocationWorkHistory;

/**
 * This is the ActiveQuery class for [[\common\models\LocationWorkHistory]].
 *
 * @see \common\models\LocationWorkHistory
 */
class LocationWorkHistoryQuery extends \yii\db\ActiveQuery
{
    /**
     * @param int|array $event
     * @return LocationWorkHistoryQuery
     */
    public function forEvent($event): LocationWorkHistoryQuery
    {
        return $this->andWhere(['event_id' => $event]);
    }

    /**
     * @return LocationWorkHistoryQuery
     */
    public function forLocation(): LocationWorkHistoryQuery
    {
        return $this->forEvent([LocationWorkHistory::EVENT_OPENED, LocationWorkHistory::EVENT_CLOSED]);
    }

    /**
     * @return LocationWorkHistoryQuery
     */
    public function forWork(): LocationWorkHistoryQuery
    {
        return $this->forEvent([LocationWorkHistory::EVENT_WORKING, LocationWorkHistory::EVENT_NOT_WORKING]);
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
