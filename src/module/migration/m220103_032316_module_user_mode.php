<?php

use yii\db\Schema;
use yii\db\Migration;

class m220103_032316_module_user_mode extends Migration
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
            '{{%module_user_mode}}',
            [
                'uid'=> $this->integer(11)->notNull(),
                'mode'=> $this->string(255)->notNull(),
                'language'=> $this->integer(11)->null()->defaultValue(null),
                'language_parent'=> $this->integer(11)->null()->defaultValue(null),
                'id'=> $this->primaryKey(11),
            ],$tableOptions
        );
        $this->createIndex('uid','{{%module_user_mode}}',['uid'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('uid', '{{%module_user_mode}}');
        $this->dropTable('{{%module_user_mode}}');
    }
}
