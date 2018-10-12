<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\LocationUser]].
 *
 * @see \common\models\LocationUser
 */
class LocationUserQuery extends \yii\db\ActiveQuery
{
    /**
     * @param int $userId
     * @return LocationUserQuery
     */
    public function forUser(int $userId)
    {
        return $this->andWhere(['user_id' => $userId]);
    }

    /**
     * @param int $locationId
     * @return LocationUserQuery
     */
    public function forLocation(int $locationId)
    {
        return $this->andWhere(['location_id' => $locationId]);
    }

    /**
     * @return LocationUserQuery
     */
    public function isWorking()
    {
        return $this->andWhere(['is_working' => 1]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Location[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Location|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
