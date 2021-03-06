<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use common\models\Order;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'location_id', 'employee_id', 'customer_id'], 'integer'],
            [['total_tax', 'total'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
     * @param bool $isRefund
     * @return ActiveDataProvider
     */
    public function search($params, bool $isRefund = false)
    {
        $query = Order::find();

        if ($isRefund) $query = $query->forStatus(Order::STATUS_REFUND);

        $query = $query->with([
            'location',
            'customer',
            'employee',
        ]);

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
            'status' => $this->status,
            'location_id' => $this->location_id,
            'employee_id' => $this->employee_id,
            'customer_id' => $this->customer_id,
            'total_tax' => $this->total_tax,
            'total' => $this->total,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'created_at', $this->created_at]);

        return $dataProvider;
    }
}
