<?php

use yii\db\Migration;

/**
 * Handles the creation of table `location_event`.
 */
class m181010_091018_create_location_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('location_event', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->string()
        ]);

        $this->batchInsert('location_event', ['name', 'description'], [
            ['Open', 'Location is open'],
            ['Closed', 'Location is closed'],
            ['Clock-In', 'User clocked in'],
            ['Clock-Out', 'User clocked out'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('location_event');
    }
}
