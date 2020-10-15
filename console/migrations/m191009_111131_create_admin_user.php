<?php

use yii\db\Migration;
use common\models\user;

/**
 * Class m191009_111131_create_admin_user
 */

class m191009_111131_create_admin_user extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('{{%user}}', [
        'username' => 'admin',
        'password_hash' => YII::$app->security->generatePasswordHash('root'),
        'auth_key' => YII::$app->security->generateRandomString(),
        'password_reset_token' => YII::$app->security->generateRandomString(),
        'verification_token' => YII::$app->security->generateRandomString(),
        'email' => '',
        'status' => user::STATUS_ACTIVE,
        'created_at' => time(),
        'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user}}',['username' => 'admin']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191009_111131_create_admin_user cannot be reverted.\n";

        return false;
    }
    */
}
