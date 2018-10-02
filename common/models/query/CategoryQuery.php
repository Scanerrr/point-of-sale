<?php

namespace common\models\query;

use common\models\Category;

/**
 * This is the ActiveQuery class for [[\common\models\Category]].
 *
 * @see \common\models\Category
 */
class   CategoryQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => Category::STATUS_ACTIVE]);
    }

    /**
     * if $id === null then getting all root categories
     * @param mixed $id
     * @return CategoryQuery
     */
    public function forParent($id = null)
    {
        return $this->andWhere(['parent_id' => $id]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Category[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Category|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
