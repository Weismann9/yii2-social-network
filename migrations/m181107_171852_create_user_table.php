<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m181107_171852_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(11)->unsigned(),
            'email' => $this->string(64),
            'username' => $this->string(32),
            'password_hash' => $this->string(60),
            'salt' => $this->string(32),
            'auth_key' => $this->string(32),
            'verification_token' => $this->string(32),
            'status' => $this->tinyInteger(1)->unsigned()->notNull()->defaultValue(2),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
