<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_product`.
 */
class m181002_160454_create_order_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('order_product', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer(),
            'price' => $this->decimal(15, 2)->defaultValue(0),
            'tax' => $this->decimal(15, 2)->defaultValue(0),
            'total' => $this->decimal(15, 2)->defaultValue(0),
        ]);

        $this->addForeignKey('fk-order_product-order', 'order_product', 'order_id', 'order', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-order_product-product', 'order_product', 'product_id', 'product', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order_product');
    }
}
