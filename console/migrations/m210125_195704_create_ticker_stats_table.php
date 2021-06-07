<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticker_stats}}`.
 */
class m210125_195704_create_ticker_stats_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticker_stats}}', [
            'id' => $this->primaryKey(),
            'ticker' => $this->string(255)->notNull(),
            'short_ratio' => $this->float(),
            'short_percent_of_float' => $this->float(),
            'ebitda' => $this->float(),
            'total_cash' => $this->float(),
            'total_debt' => $this->float(),
            'operating_cash_flow' => $this->float(),
            'date' => $this->datetime()->notNull(),
        ]);

        $this->createIndex(
            'idx-ticker_stats-ticker',
            'ticker_stats',
            'ticker'
        );

        $this->createIndex(
            'idx-ticker_stats-date',
            'ticker_stats',
            'date'
        );

        $this->addForeignKey(
            'fk-ticker_stats-ticker', // это "условное имя" ключа
            'ticker_stats', // это название текущей таблицы
            'ticker', // это имя поля в текущей таблице, которое будет ключом
            'tks_invest_tickers', // это имя таблицы, с которой хотим связаться
            'ticker', // это поле таблицы, с которым хотим связаться
            'CASCADE', // ON DELETE
            'CASCADE' // ON UPDATE
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey(
            'fk-ticker_stats-ticker',
            'ticker_stats'
        );

        $this->dropIndex(
            'idx-ticker_stats-ticker',
            'ticker_stats'
        );

        $this->dropIndex(
            'idx-ticker_stats-date',
            'ticker_stats'
        );

        $this->dropTable('{{%ticker_stats}}');
    }
}