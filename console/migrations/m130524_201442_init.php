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
            'avatar' => $this->string(64)->null(),

            'phone' => $this->string(16)->null(),
            'position' => $this->string(64)->null(),
            'country' => $this->string(64)->null(),
            'state' => $this->string(64)->null(),
            'city' => $this->string(64)->null(),
            'zip' => $this->string(5)->null(),
            'address' => $this->string(128)->null(),

            'role_id' => $this->smallInteger()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),

            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
