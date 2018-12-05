<?php

use yii\db\Migration;

/**
 * Handles the creation of table `gallery`.
 */
class m181119_084938_create_gallery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('gallery', [
            'id' => $this->primaryKey(11)->unsigned(),
            'image' => $this->string(64),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('gallery');
    }
}
