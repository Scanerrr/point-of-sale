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
            'phone' => $this->string(),

            'country' => $this->string()->notNull(),
            'state' => $this->string()->notNull(),
            'city' => $this->string(),
            'address' => $this->string(),
            'zip' => $this->string(5),

            'tax_rate' => $this->decimal(5, 2)->defaultValue(0),

            'status' => $this->smallInteger(1)->notNull()->defaultValue(1)->comment('0-disabled, 1-active'),

            'is_open' => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('0-closed, 1-open'),

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
