<?php

use yii\db\Migration;

/**
 * Handles the creation of table `inventory`.
 */
class m180925_135528_create_inventory_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('inventory', [
            'id' => $this->primaryKey(),
            'location_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer(4)->defaultValue(0),
        ]);

        $this->createIndex('idx-inventory-location_product', 'inventory', ['location_id', 'product_id'], true);

        $this->addForeignKey('fk-inventory-location', 'inventory', 'location_id', 'location', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-inventory-product', 'inventory', 'product_id', 'product', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('inventory');
    }
}
