<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_payment`.
 */
class m181008_091458_create_order_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order_payment', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'method_id' => $this->integer()->notNull(),
            'details' => $this->text(),
            'amount' => $this->decimal(15, 2)->defaultValue(0),

            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp() . ' on update CURRENT_TIMESTAMP',
        ]);

        $this->addForeignKey('fk-order_payment-order', 'order_payment', 'order_id', 'order', 'id');
        $this->addForeignKey('fk-order_payment-payment_method', 'order_payment', 'method_id', 'payment_method', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order_payment');
    }
}
