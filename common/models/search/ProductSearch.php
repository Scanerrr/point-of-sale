<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use common\models\Product;
use yii\data\ActiveDataProvider;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'supplier_id', 'commission_policy_id', 'status'], 'integer'],
            [['name', 'description', 'image', 'barcode', 'size', 'sku', 'created_at', 'updated_at'], 'safe'],
            [['cost_price', 'markup_price', 'max_price', 'tax'], 'number'],
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
        $query = Product::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
            'cost_price' => $this->cost_price,
            'markup_price' => $this->markup_price,
            'max_price' => $this->max_price,
            'tax' => $this->tax,
            'commission_policy_id' => $this->commission_policy_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'barcode', $this->barcode])
            ->andFilterWhere(['like', 'size', $this->size])
            ->andFilterWhere(['like', 'sku', $this->sku]);

        return $dataProvider;
    }
}
