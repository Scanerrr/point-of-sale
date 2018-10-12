<?php

use yii\db\Migration;

/**
 * Handles the creation of table `location_user`.
 */
class m180921_132540_create_location_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('location_user', [
            'id' => $this->primaryKey(),
            'location_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'is_working' => $this->smallInteger(0)->defaultValue(0)->comment('0-closed, 1-open'),
        ]);

        $this->addForeignKey('fk_location_user_location', 'location_user', 'location_id', 'location', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_location_user_user', 'location_user', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('location_user');
    }
}
