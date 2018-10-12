<?php

namespace common\models\query;

use common\models\Location;

/**
 * This is the ActiveQuery class for [[\common\models\Location]].
 *
 * @see \common\models\Location
 */
class LocationQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => Location::STATUS_ACTIVE]);
    }

    public function isOpen()
    {
        return $this->active()->andWhere(['is_open' => Location::STATUS_ACTIVE]);
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
