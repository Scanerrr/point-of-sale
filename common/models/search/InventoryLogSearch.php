<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\{InventoryLog, Product};

/**
 * InventoryLogSearch represents the model behind the search form of `common\models\InventoryLog`.
 */
class InventoryLogSearch extends InventoryLog
{
    public $barcode;
    public $product;
    public $size;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'location_id', 'product_id', 'user_id', 'quantity'], 'integer'],
            [['created_at'], 'safe'],
            [['product', 'comment', 'barcode', 'size'], 'string']
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
        $query = InventoryLog::find()
            ->forLocation($params['id'])
            ->with(['product', 'user'])
            ->joinWith(['product']);

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
            'created_at' => [
                'asc' => ['created_at' => SORT_ASC],
                'desc' => ['created_at' => SORT_DESC],
            ],
            'comment' => [
                'asc' => ['comment' => SORT_ASC],
                'desc' => ['comment' => SORT_DESC],
            ],
            'quantity' => [
                'asc' => ['quantity' => SORT_ASC],
                'desc' => ['quantity' => SORT_DESC],
            ],
            'user_id' => [
                'asc' => ['user_id' => SORT_ASC],
                'desc' => ['user_id' => SORT_DESC],
            ],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'location_id' => $this->location_id,
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
            'quantity' => $this->quantity,
        ]);

        $query->andFilterWhere(['like', InventoryLog::tableName() . '.created_at', $this->created_at])
            ->andFilterWhere(['like', Product::tableName() . '.barcode', $this->barcode])
            ->andFilterWhere(['like', Product::tableName() . '.barcode', $this->barcode])
            ->andFilterWhere(['like', Product::tableName() . '.size', $this->size]);

        return $dataProvider;
    }
}
