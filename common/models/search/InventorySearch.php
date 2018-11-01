<?php

namespace common\models\search;

use common\models\Category;
use common\models\Product;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Inventory;

/**
 * InventorySearch represents the model behind the search form of `common\models\Inventory`.
 */
class InventorySearch extends Inventory
{
    public $category;
    public $barcode;
    public $product;
    public $size;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'location_id', 'product_id', 'quantity'], 'integer'],
            [['product', 'category', 'barcode', 'size'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Inventory::find()
            ->forLocation($params['id'])
            ->with(['product', Product::tableName() . '.category'])
            ->joinWith(['product', Product::tableName() . '.category']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->sort->attributes = [
            'category' => [
                'asc' => [Category::tableName() . '.name' => SORT_ASC],
                'desc' => [Category::tableName() . '.name' => SORT_DESC],
            ],
            'product' => [
                'asc' => [Product::tableName() . '.name' => SORT_ASC],
                'desc' => [Product::tableName() . '.name' => SORT_DESC],
            ],
            'barcode' => [
                'asc' => [Product::tableName() . '.barcode' => SORT_ASC],
                'desc' => [Product::tableName() . '.barcode' => SORT_DESC],
            ],
            'size' => [
                'asc' => [Product::tableName() . '.size' => SORT_ASC],
                'desc' => [Product::tableName() . '.size' => SORT_DESC],
            ],
            'quantity' => [
                'asc' => ['quantity' => SORT_ASC],
                'desc' => ['quantity' => SORT_DESC],
            ],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            Inventory::tableName() . '.id' => $this->id,
            'location_id' => $this->location_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
        ]);

        $query->andFilterWhere(['like', Product::tableName() . '.name', $this->product])
            ->andFilterWhere(['like', Product::tableName() . '.barcode', $this->barcode])
            ->andFilterWhere(['like', Product::tableName() . '.size', $this->size])
            ->andFilterWhere(['like', Category::tableName() . '.name', $this->category]);

        return $dataProvider;
    }
}
