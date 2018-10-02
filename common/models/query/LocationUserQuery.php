<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\LocationUser]].
 *
 * @see \common\models\LocationUser
 */
class LocationUserQuery extends \yii\db\ActiveQuery
{
    public function forUser(int $user_id)
    {
        return $this->andWhere(['user_id' => $user_id]);
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
