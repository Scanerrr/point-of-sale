<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m180925_135434_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),

            'name' => $this->string()->notNull(),
            'image' => $this->string()->null(),

            'status' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp() . ' on update CURRENT_TIMESTAMP',
        ]);

        $this->createIndex('idx-category-parent_id', 'category', 'parent_id');

        $this->addForeignKey('fk-category-parent', 'category', 'parent_id', 'category', 'id', 'SET NULL', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');
    }
}
