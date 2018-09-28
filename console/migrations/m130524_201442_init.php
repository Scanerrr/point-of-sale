<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    /**
     * @return bool|void
     * @throws \yii\base\Exception
     */
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

            'phone' => $this->string(),
            'position' => $this->string(),
            'country' => $this->string(),
            'state' => $this->string(),
            'city' => $this->string(),
            'zip' => $this->string(5),
            'address' => $this->string(),

            'role' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(1),

            'salary_settings' => $this->json(),

            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp() . ' on update CURRENT_TIMESTAMP',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
