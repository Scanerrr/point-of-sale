<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_commission`.
 */
class m181030_134338_create_user_commission_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_commission', [
            'order_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'commission_type' => $this->smallInteger(1)->notNull(),
            'commission_value' => $this->decimal(15, 2)->defaultValue(0)
        ]);

        $this->addPrimaryKey('pk-user_commission', 'user_commission', ['order_id', 'user_id']);

        $this->addForeignKey('fk_user_commission_order', 'user_commission', 'order_id', 'order', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_commission_user', 'user_commission', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_commission');
    }
}
