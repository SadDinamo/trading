<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tks_preferencies}}`.
 */
class m201014_105411_create_tks_preferencies_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tks_preferencies}}', [
            'ID' => $this->primaryKey(),
            'PreferenceName' => $this->string()->notNull()->unique(),
            'Value' => $this->string(),
            'Min' => $this->string(),
            'Max' => $this->string(),

        ]);

        $this->insert( '{{%tks_preferencies}}', [
            'PreferenceName' => 'SandBoxToken',
        ]);

        $this->insert('{{%tks_preferencies}}', [
            'PreferenceName' => 'Token',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tks_preferencies}}');
    }
}
