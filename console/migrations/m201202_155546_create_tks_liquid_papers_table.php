<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tks_liquid_papers}}`.
 */
class m201202_155546_create_tks_liquid_papers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tks_liquid_papers}}', [
            'id' => $this->primaryKey(),
            'ticker' => $this->string(255)->notNull()->unique(),
            'isin' => $this->string(255)->notNull()->unique(),
            'short' => $this->string(255)->null(),
            'longRisk' => $this->float()->null(),
            'shortRisk' => $this->float()->null(),
        ]);

        $this->createIndex(
            'tickerIndex', //имя индекса
            '{{%tks_liquid_papers}}', //имя таблицы
            ['ticker'], //колонки индекса
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tks_liquid_papers}}');
    }
}
