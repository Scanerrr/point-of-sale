<?php

use yii\db\Migration;

/**
 * Handles the creation of table `customer`.
 */
class m181001_161732_create_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('customer', [
            'id' => $this->primaryKey(),
            'firstname' => $this->string(),
            'lastname' => $this->string(),
            'phone' => $this->string(),
            'gender' => 'ENUM("male", "female")',
            'email' => $this->string(),
            'added_by' => $this->integer()->null(),
            'country' => $this->string(),
            'state' => $this->string(),
            'city' => $this->string(),
            'address' => $this->string(),
            'zip' => $this->string(5),

            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp() . ' on update CURRENT_TIMESTAMP',
        ]);

        $this->addForeignKey('fk-customer-user', 'customer', 'added_by', 'user', 'id', 'SET NULL', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('customer');
    }
}
