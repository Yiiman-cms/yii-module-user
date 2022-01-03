<?php

use yii\db\Schema;
use yii\db\Migration;

class m220103_032319_Relations extends Migration
{

    public function init()
    {
       $this->db = 'db';
       parent::init();
    }

    public function safeUp()
    {
        $this->addForeignKey('fk_module_user_data_uid',
            '{{%module_user_data}}','uid',
            '{{%module_user}}','id',
            'CASCADE','CASCADE'
         );
        $this->addForeignKey('fk_module_user_mode_uid',
            '{{%module_user_mode}}','uid',
            '{{%module_user}}','id',
            'CASCADE','CASCADE'
         );
        $this->addForeignKey('fk_module_user_packed_data_uid',
            '{{%module_user_packed_data}}','uid',
            '{{%module_user}}','id',
            'CASCADE','CASCADE'
         );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_module_user_data_uid', '{{%module_user_data}}');
        $this->dropForeignKey('fk_module_user_mode_uid', '{{%module_user_mode}}');
        $this->dropForeignKey('fk_module_user_packed_data_uid', '{{%module_user_packed_data}}');
    }
}
