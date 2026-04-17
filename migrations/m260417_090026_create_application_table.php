<?php

use yii\db\Migration;


class m260417_090026_create_application_table extends Migration
{

    public function safeUp()
    {
        $this->createTable('{{%application}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'car_number' => $this->string(20)->notNull(),
            'description' => $this->text()->notNull(),
            'status' => "ENUM('new','confirmed','rejected') NOT NULL DEFAULT 'new'",
            'rejection_reason' => $this->text()->null(),
        ]);

        $this->createIndex(
            '{{%idx-application-user_id}}',
            '{{%application}}',
            'user_id'
        );

        $this->addForeignKey(
            '{{%fk-application-user_id}}',
            '{{%application}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%application}}');
    }
}
