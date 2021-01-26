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
            [['ticker', 'name', 'date', 'value'], 'required'],
            [['date'], 'safe'],
            [['value'], 'number'],
            [['ticker', 'name'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'date' => 'Date',
            'value' => 'Value',
        ];
    }

    /**
     * Gets query for [[Ticker0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicker0()
    {
        return $this->hasOne(TksInvestTickers::className(), ['ticker' => 'ticker']);
    }
}
