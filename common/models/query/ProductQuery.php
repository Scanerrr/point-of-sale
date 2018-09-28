<?php

namespace common\models\query;

use common\models\Category;
use common\models\Product;

/**
 * This is the ActiveQuery class for [[\common\models\Product]].
 *
 * @see \common\models\Product
 */
class ProductQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['status' => Product::STATUS_ACTIVE]);
    }

    public function forCategory(int $id)
    {
        $ids = [$id];
        $childrenIds = [$id];
        while ($childrenIds = Category::find()->active()->forParent($childrenIds)->select('id')->column()) {
            $ids = array_merge($ids, $childrenIds);
        }
        return $this->andWhere(['category_id' => array_unique($ids)]);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Product[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Product|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
