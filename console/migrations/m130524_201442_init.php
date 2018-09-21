<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),

            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'avatar' => $this->string()->null(),

            'phone' => $this->string()->null(),
            'position' => $this->string()->null(),
            'country' => $this->string()->null(),
            'state' => $this->string()->null(),
            'city' => $this->string()->null(),
            'zip' => $this->string(5)->null(),
            'address' => $this->string()->null(),

            'role' => $this->smallInteger()->notNull()->defaultValue(10),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),

            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp() . ' on update CURRENT_TIMESTAMP',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
