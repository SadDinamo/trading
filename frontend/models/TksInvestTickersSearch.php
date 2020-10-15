<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TksInvestTickers;

/**
 * TksInvestTickersSearch represents the model behind the search form of `app\models\TksInvestTickers`.
 */
class TksInvestTickersSearch extends TksInvestTickers
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'lot'], 'integer'],
            [['figi', 'ticker', 'isin', 'currency', 'name', 'type'], 'safe'],
            [['minPriceIncrement'], 'number'],
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
        $query = TksInvestTickers::find();

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
            'minPriceIncrement' => $this->minPriceIncrement,
            'lot' => $this->lot,
        ]);

        $query->andFilterWhere(['like', 'figi', $this->figi])
            ->andFilterWhere(['like', 'ticker', $this->ticker])
            ->andFilterWhere(['like', 'isin', $this->isin])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
