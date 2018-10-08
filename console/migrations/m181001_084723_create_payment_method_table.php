<?php

use yii\db\Migration;

/**
 * Handles the creation of table `payment_method`.
 */
class m181001_084723_create_payment_method_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('payment_method', [
            'id' => $this->primaryKey(),
            'type_id' => $this->smallInteger(0)->notNull(),
            'name' => $this->string()
        ]);

        $this->batchInsert('payment_method', ['type_id', 'name'], [
            [0, 'Cash'],
            [1, 'Visa'],
            [1, 'Master Card'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('payment_method');
    }
}
