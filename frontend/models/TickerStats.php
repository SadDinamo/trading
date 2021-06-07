<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticker_stats".
 *
 * @property int $id
 * @property string $ticker
 * @property string $name
 * @property string $date
 * @property float $value
 *
 * @property TksInvestTickers $ticker0
 */
class TickerStats extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticker_stats';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticker', 'date'], 'required'],
            [['date'], 'safe'],
            [['ticker'], 'string', 'max' => 255],
            // [['short_ratio', 
            // 'short_percent_of_float', 
            // 'ebitda', 
            // 'total_cash', 
            // 'total_debt', 
            // 'operating_cash_flow'],'number'],
            [['ticker'], 'exist', 'skipOnError' => true, 'targetClass' => TksInvestTickers::className(), 'targetAttribute' => ['ticker' => 'ticker']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticker' => 'Ticker',
            'date' => 'Date',
            'short_ratio' => 'Short ratio',
            'short_percent_of_float' => 'Short percent of float',
            'ebitda' => 'EBITDA',
            'total_cash' => 'Total cash',
            'total_debt' => 'Total debt',
            'operating_cash_flow' => 'Operating cash flow'
        ];
    }

    /**
     * Gets query for [[Ticker]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicker()
    {
        return $this->hasOne(TksInvestTickers::className(), ['ticker' => 'ticker']);
    }
}
