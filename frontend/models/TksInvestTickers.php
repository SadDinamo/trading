<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tks_invest_tickers".
 *
 * @property int $id
 * @property string|null $figi
 * @property string $ticker
 * @property string|null $isin
 * @property float|null $minPriceIncrement
 * @property int|null $lot
 * @property string|null $currency
 * @property string|null $name
 * @property string|null $type
 */
class TksInvestTickers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tks_invest_tickers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticker','active'], 'required'],
            [['minPriceIncrement'], 'number'],
            [['lot'], 'integer'],
            [['figi', 'ticker', 'isin', 'currency', 'name', 'type'], 'string', 'max' => 255],
            [['ticker'], 'unique'],
            [['figi'], 'unique'],
            [['isin'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'figi' => 'Figi',
            'ticker' => 'Тикер',
            'isin' => 'Isin',
            'minPriceIncrement' => 'Min Price Increment',
            'lot' => 'Количество бумаг в лоте',
            'currency' => 'Валюта',
            'name' => 'Наименование',
            'type' => 'Тип',
            'active' => 'Активно',
            'creationDate' => 'Дата создания',
            'updateDate' => 'Дата изменения',
        ];
    }
}
