<?php

use yii\db\Migration;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Handles the creation of table `{{%tks_invest_tickers}}`.
 */
class m200826_075539_create_tickers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tks_invest_tickers}}', [
            'id' => $this->primaryKey(),
            'figi' => $this->string()->unique(),
            'ticker' => $this->string()->unique()->notNull(),
            'isin' => $this->string()->unique(),
            'minPriceIncrement' => $this->float(),
            'lot' => $this->integer(),
            'currency' => $this->string(),
            'name' => $this->string(),
            'type' => $this->string(),
            'active'=> $this->boolean()->defaultValue(TRUE),
            'creationDate'=>$this->dateTime()->Null(),
            'updateDate'=>$this->dateTime()->Null(),
        ]);

        $this->createIndex(
            'tickerIndex', //имя индекса
            '{{%tks_invest_tickers}}', //имя таблицы
            ['ticker'], //колонки индекса
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tks_invest_tickers}}');
    }
}
