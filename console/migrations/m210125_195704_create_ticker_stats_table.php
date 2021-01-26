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
            'name' => $this->string(255)->notNull(),
            'date' => $this->datetime()->notNull(),
            'value' => $this->float()->notNull(),
        ]);

        $this->addForeignKey(
            'ticker_stat_ticker', // это "условное имя" ключа
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
        $this->dropTable('{{%ticker_stats}}');
    }
}