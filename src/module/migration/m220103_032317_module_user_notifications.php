<?php

use yii\db\Schema;
use yii\db\Migration;

class m220103_032317_module_user_notifications extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%module_user_notifications}}',
            [
                'id'=> $this->primaryKey(11),
                'text'=> $this->string(255)->notNull(),
                'type'=> $this->string(255)->notNull(),
                'uid'=> $this->integer(11)->notNull(),
                'user_mode'=> $this->tinyInteger(1)->notNull(),
                'created_at'=> $this->datetime()->notNull(),
                'viewed'=> $this->tinyInteger(1)->notNull(),
                'language'=> $this->integer(11)->null()->defaultValue(null),
                'language_parent'=> $this->integer(11)->null()->defaultValue(null),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%module_user_notifications}}');
    }
}
