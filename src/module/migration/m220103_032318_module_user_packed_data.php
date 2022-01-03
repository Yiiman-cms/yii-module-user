<?php

use yii\db\Schema;
use yii\db\Migration;

class m220103_032318_module_user_packed_data extends Migration
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
            '{{%module_user_packed_data}}',
            [
                'id'=> $this->primaryKey(11),
                'uid'=> $this->integer(11)->notNull(),
                'key'=> $this->string(100)->notNull(),
                'packed_data'=> $this->text()->notNull(),
                'language'=> $this->integer(11)->null()->defaultValue(null),
                'language_parent'=> $this->integer(11)->null()->defaultValue(null),
            ],$tableOptions
        );
        $this->createIndex('uid','{{%module_user_packed_data}}',['uid'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('uid', '{{%module_user_packed_data}}');
        $this->dropTable('{{%module_user_packed_data}}');
    }
}
