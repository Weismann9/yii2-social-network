<?php

use yii\db\Migration;

/**
 * Handles the creation of table `friendship`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `user`
 */
class m181119_084318_create_friendship_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('friendship', [
            'id' => $this->primaryKey(11)->unsigned(),
            'first_user_id' => $this->integer(11)->unsigned(),
            'second_user_id' => $this->integer(11)->unsigned(),
        ]);

        $this->createIndex(
            'idx-friendship-users_id',
            'friendship',
            [
                'first_user_id',
                'second_user_id'
            ],
            true
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-friendship-first_user_id',
            'friendship',
            'first_user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-friendship-second_user_id',
            'friendship',
            'second_user_id',
            'user',
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
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-friendship-first_user_id',
            'friendship'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-friendship-second_user_id',
            'friendship'
        );

        $this->dropIndex(
            'idx-friendship-users_id',
            'friendship'
        );

        $this->dropTable('friendship');
    }
}
