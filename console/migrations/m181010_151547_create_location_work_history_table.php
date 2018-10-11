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
            'event_id' => $this->integer()->notNull()->comment('1-opened, 2-closed, 3-clock-in, 4-clock-out'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->addForeignKey('fk-work_history-location', 'location_work_history', 'location_id', 'location', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-work_history-user', 'location_work_history', 'user_id', 'user', 'id');
        $this->addForeignKey('fk-work_history-event', 'location_work_history', 'event_id', 'location_event', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('location_work_history');
    }
}
