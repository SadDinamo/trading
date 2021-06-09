<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TickerStats;

/**
 * TickerStatsSearch represents the model behind the search form of `app\models\TickerStats`.
 */
class TickerStatsSearch extends TickerStats
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['ticker', 'date'], 'safe'],
            [['short_ratio', 'short_percent_of_float', 'ebitda', 'total_cash', 'total_debt', 'operating_cash_flow'], 'number'],
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
        $query = TickerStats::find();

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
            'short_ratio' => $this->short_ratio,
            'short_percent_of_float' => $this->short_percent_of_float,
            'ebitda' => $this->ebitda,
            'total_cash' => $this->total_cash,
            'total_debt' => $this->total_debt,
            'operating_cash_flow' => $this->operating_cash_flow,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'ticker', $this->ticker]);

        return $dataProvider;
    }
}
