<?php

use yii\db\Migration;

/**
 * Handles the creation of table `location`.
 */
class m180917_131152_create_location_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('location', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'region_id' => $this->smallInteger()->notNull(),

            'email' => $this->string(64)->notNull()->unique(),
            'phone' => $this->string(16)->null(),

            'country_id' => $this->smallInteger()->notNull(),
            'state_id' => $this->smallInteger()->notNull(),
            'city' => $this->string(64)->null(),
            'address' => $this->string(128)->null(),
            'zip' => $this->string(5)->null(),

            'tax_rate' => $this->decimal(5, 2)->null()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('location');
    }
}
