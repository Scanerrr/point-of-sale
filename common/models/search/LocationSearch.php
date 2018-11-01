<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use common\models\Location;
use yii\data\ActiveDataProvider;

/**
 * LocationSearch represents the model behind the search form of `common\models\Location`.
 */
class LocationSearch extends Location
{
    public $region;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'region_id', 'status'], 'integer'],
            [['prefix', 'name', 'region', 'email', 'phone', 'country', 'state', 'city', 'address', 'zip', 'created_at', 'updated_at'], 'safe'],
            [['tax_rate'], 'number'],
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
        $query = Location::find()->joinWith('region');

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

        // добавляем сортировку по колонке из зависимости
        $dataProvider->sort->attributes['region.name'] = [
            'asc' => ['region.name' => SORT_ASC],
            'desc' => ['region.name' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'region_id' => $this->region_id,
            'tax_rate' => $this->tax_rate,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'prefix', $this->prefix])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'zip', $this->zip]);

        return $dataProvider;
    }
}
