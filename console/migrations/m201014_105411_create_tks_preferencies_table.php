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
            'Value' => 't.K0KeSoTVc_pQQYN-4vH-s1Pzt4efB1H4rDia2cKqU0cf8fsC9aCI0k3eOLaQX4sj8mPFouVJK3V4HmQvigR27w',
        ]);

        $this->insert('{{%tks_preferencies}}', [
            'PreferenceName' => 'Token',
        ]);

        $this->insert('{{%tks_preferencies}}', [
            'PreferenceName' => 'ClientType',
            'Value' => 'Sandbox',
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
