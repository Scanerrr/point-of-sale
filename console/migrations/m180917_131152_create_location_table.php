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

            'prefix' => $this->string()->notNull()->unique(),
            'name' => $this->string(64)->notNull(),
            'region_id' => $this->integer()->notNull(),

            'email' => $this->string(64)->notNull()->unique(),
            'phone' => $this->string()->null(),

            'country' => $this->string()->notNull(),
            'state' => $this->string()->notNull(),
            'city' => $this->string()->null(),
            'address' => $this->string()->null(),
            'zip' => $this->string(5)->null(),

            'tax_rate' => $this->decimal(5, 2)->null(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),

            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp() . ' on update CURRENT_TIMESTAMP',
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
