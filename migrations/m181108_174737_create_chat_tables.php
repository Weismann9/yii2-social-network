<?php

use yii\db\Migration;

/**
 * Class m181108_174737_create_chat_tables
 */
class m181108_174737_create_chat_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('conversation', [
            'id' => $this->primaryKey(11)->unsigned(),
            'title' => $this->string(64),
        ]);

        $this->createTable('user_has_conversation', [
            'id' => $this->primaryKey(11)->unsigned(),
            'user_id' => $this->integer(11)->unsigned(),
            'conversation_id' => $this->integer(11)->unsigned()
        ]);

        $this->createIndex(
            'idx-user_has_conversation-user_id',
            'user_has_conversation',
            'user_id'
        );

        $this->createIndex(
            'idx-user_has_conversation-conversation_id',
            'user_has_conversation',
            'conversation_id'
        );

        $this->createTable('conversation_message', [
            'id' => $this->primaryKey(11)->unsigned(),
            'conversation_id' => $this->integer(11)->unsigned(),
            'user_id' => $this->integer(11)->unsigned(),
            'content' => $this->text(),
            'created_at' => $this->string(32)
        ]);

        $this->createIndex(
            'idx-conversation_message-conversation_id',
            'conversation_message',
            'conversation_id'
        );

        $this->createIndex(
            'idx-conversation_message-user_id',
            'conversation_message',
            'user_id'
        );


        $this->addForeignKey(
            'fk-conversation_message-user_id',
            'conversation_message',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-conversation_message-conversation_id',
            'conversation_message',
            'conversation_id',
            'conversation',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_has_conversation-user_id',
            'user_has_conversation',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-user_has_conversation-conversation_id',
            'user_has_conversation',
            'conversation_id',
            'conversation',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-conversation_message-conversation_id', 'conversation_message');
        $this->dropIndex('idx-conversation_message-conversation_id', 'conversation_message');

        $this->dropForeignKey('fk-conversation_message-user_id', 'conversation_message');
        $this->dropIndex('idx-conversation_message-user_id', 'conversation_message');

        $this->dropForeignKey('fk-user_has_conversation-conversation_id', 'user_has_conversation');
        $this->dropIndex('idx-user_has_conversation-conversation_id', 'user_has_conversation');

        $this->dropForeignKey('fk-user_has_conversation-user_id', 'user_has_conversation');
        $this->dropIndex('idx-user_has_conversation-user_id', 'user_has_conversation');

        $this->dropTable('conversation_message');
        $this->dropTable('user_has_conversation');
        $this->dropTable('conversation');
    }
}
