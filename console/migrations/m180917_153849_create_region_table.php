<?php

use yii\db\Migration;

/**
 * Handles the creation of table `region`.
 */
class m180917_153849_create_region_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('region', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('fk_location_region', 'location', 'region_id', 'region', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('region');
    }
}
