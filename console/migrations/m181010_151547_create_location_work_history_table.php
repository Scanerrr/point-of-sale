<?php

use yii\db\Migration;

/**
 * Handles the creation of table `location_work_history`.
 */
class m181010_151547_create_location_work_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('location_work_history', [
            'id' => $this->primaryKey(),
            'location_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'event' => $this->smallInteger(1)->notNull()->comment('0-opened, 1-closed'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->addForeignKey('fk-work_history-location', 'location_work_history', 'location_id', 'location', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-work_history-user', 'location_work_history', 'user_id', 'user', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('location_work_history');
    }
}
