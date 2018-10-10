<?php

namespace common\models\search;

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
            ->with(['product', 'product.category'])
            ->joinWith(['product', 'product.category']);

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
                'asc' => ['category.name' => SORT_ASC],
                'desc' => ['category.name' => SORT_DESC],
            ],
            'product' => [
                'asc' => ['product.name' => SORT_ASC],
                'desc' => ['product.name' => SORT_DESC],
            ],
            'barcode' => [
                'asc' => ['product.barcode' => SORT_ASC],
                'desc' => ['product.barcode' => SORT_DESC],
            ],
            'size' => [
                'asc' => ['product.size' => SORT_ASC],
                'desc' => ['product.size' => SORT_DESC],
            ],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'inventory.id' => $this->id,
            'location_id' => $this->location_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
        ]);

        $query->andFilterWhere(['like', 'product.name', $this->product])
            ->andFilterWhere(['like', 'product.barcode', $this->barcode])
            ->andFilterWhere(['like', 'product.size', $this->size])
            ->andFilterWhere(['like', 'category.name', $this->category]);

        return $dataProvider;
    }
}
