<?php

use yii\db\Migration;

/**
 * Handles the creation of table `product`.
 */
class m180925_135443_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('product', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'supplier_id' => $this->integer(),

            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'cost_price' => $this->decimal(15, 2)->defaultValue(0),
            'markup_price' => $this->decimal(15, 2)->defaultValue(0),
            'max_price' => $this->decimal(15, 2)->defaultValue(0),

            'tax' => $this->decimal(5, 2)->defaultValue(0),

            'commission_policy_id' => $this->smallInteger()->notNull()->defaultValue(1),
            'commission' => $this->decimal(15, 2)->defaultValue(0),

            'image' => $this->string()->null(),
            'barcode' => $this->string(64),
            'size' => $this->string(64),
            'sku' => $this->string(64),

            'status' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp() . ' on update CURRENT_TIMESTAMP',
        ]);

        $this->createIndex('idx-product-category_id', 'product', 'category_id');
        $this->createIndex('idx-product-supplier_id', 'product', 'supplier_id');

        $this->addForeignKey('fk-product-category', 'product', 'category_id', 'category', 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('fk-product-supplier', 'product', 'supplier_id', 'supplier', 'id', 'SET NULL', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('product');
    }
}
