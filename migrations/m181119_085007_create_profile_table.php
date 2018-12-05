<?php

use yii\db\Migration;

/**
 * Handles the creation of table `profile`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `gallery`
 */
class m181119_085007_create_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('profile', [
            'id' => $this->primaryKey(11)->unsigned(),
            'avatar' => $this->string(64),
            'first_name' => $this->string(32),
            'last_name' => $this->string(32),
            'birth_date' => $this->string(32),
            'details' => $this->text(),
            'user_id' => $this->integer(11)->unsigned(),
            'gallery_id' => $this->integer(11)->unsigned(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-profile-user_id',
            'profile',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-profile-user_id',
            'profile',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `gallery_id`
        $this->createIndex(
            'idx-profile-gallery_id',
            'profile',
            'gallery_id'
        );

        // add foreign key for table `gallery`
        $this->addForeignKey(
            'fk-profile-gallery_id',
            'profile',
            'gallery_id',
            'gallery',
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
            'fk-profile-user_id',
            'profile'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-profile-user_id',
            'profile'
        );

        // drops foreign key for table `gallery`
        $this->dropForeignKey(
            'fk-profile-gallery_id',
            'profile'
        );

        // drops index for column `gallery_id`
        $this->dropIndex(
            'idx-profile-gallery_id',
            'profile'
        );

        $this->dropTable('profile');
    }
}
