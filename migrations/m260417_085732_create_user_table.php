<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m260417_085732_create_user_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(100)->notNull()->unique(),
            'password' => $this->string()->notNull(),
            'full_name' => $this->string(100)->notNull(),
            'phone' => $this->string(50)->notNull(),
            'email' => $this->string(100)->notNull(),
            'role' => 'enum("admin","user") NOT NULL DEFAULT "user"',
        ]);

        $this->insert('{{%user}}', [
            'username' => 'moder',
            'password' => md5('moder'),
            'full_name' => 'Admin',
            'phone' => '8(888)888-88-88',
            'email' => 'admin@moder.loc',
            'role' => 'admin',
        ]);
    }


    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
