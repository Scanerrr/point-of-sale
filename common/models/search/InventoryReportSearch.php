<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\{InventoryReport, Product};

/**
 * InventoryReportSearch represents the model behind the search form of `common\models\InventoryReport`.
 */
class InventoryReportSearch extends InventoryReport
{
    public $product;
    public $barcode;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'location_id', 'product_id', 'user_id', 'reason_id', 'quantity'], 'integer'],
            [['comment', 'created_at'], 'safe'],
            [['product', 'barcode'], 'string'],
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
        $query = InventoryReport::find()
            ->forLocation($params['id'])
            ->with(['product'])
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
            'user_id' => [
                'asc' => ['user_id' => SORT_ASC],
                'desc' => ['user_id' => SORT_DESC],
            ],
            'reason_id' => [
                'asc' => ['reason_id' => SORT_ASC],
                'desc' => ['reason_id' => SORT_DESC],
            ],
            'quantity' => [
                'asc' => ['quantity' => SORT_ASC],
                'desc' => ['quantity' => SORT_DESC],
            ],
            'comment' => [
                'asc' => ['comment' => SORT_ASC],
                'desc' => ['comment' => SORT_DESC],
            ],
            'created_at' => [
                'asc' => ['created_at' => SORT_ASC],
                'desc' => ['created_at' => SORT_DESC],
            ],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'location_id' => $this->location_id,
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
            'reason_id' => $this->reason_id,
            'quantity' => $this->quantity,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', InventoryReport::tableName() . '.created_at', $this->created_at])
            ->andFilterWhere(['like', Product::tableName() . '.name', $this->product])
            ->andFilterWhere(['like', Product::tableName() . '.barcode', $this->barcode]);

        return $dataProvider;
    }
}
