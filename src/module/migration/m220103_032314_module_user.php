<?php

use yii\db\Schema;
use yii\db\Migration;

class m220103_032314_module_user extends Migration
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
            '{{%module_user}}',
            [
                'id'=> $this->primaryKey(11),
                'username'=> $this->string(255)->null()->defaultValue(null),
                'auth_key'=> $this->string(32)->null()->defaultValue(null),
                'created_at'=> $this->datetime()->null()->defaultValue(null),
                'status'=> $this->integer(255)->null()->defaultValue(null),
                'updated_at'=> $this->datetime()->notNull(),
                'birthday'=> $this->date()->null()->defaultValue(null),
                'updated_by'=> $this->integer(11)->null()->defaultValue(null),
                'credit'=> $this->decimal(10)->null()->defaultValue(null),
                'sex'=> $this->integer(11)->null()->defaultValue(null),
                'score'=> $this->integer(11)->null()->defaultValue(null),
                'status_job'=> $this->integer(11)->null()->defaultValue(null),
                'fullname'=> $this->string(255)->null()->defaultValue(null),
                'verification'=> $this->string(255)->null()->defaultValue(null),
                'name'=> $this->string(255)->null()->defaultValue(null),
                'family'=> $this->string(255)->null()->defaultValue(null),
                'deleted_by'=> $this->string(255)->null()->defaultValue(null),
                'restored_by'=> $this->string(255)->null()->defaultValue(null),
                'nation_code'=> $this->string(255)->null()->defaultValue(null),
                'bank_card'=> $this->string(255)->null()->defaultValue(null),
                'jobs'=> $this->string(255)->null()->defaultValue(null),
                'attached'=> $this->string(255)->null()->defaultValue(null),
                'email'=> $this->string(255)->null()->defaultValue(null),
                'mobile'=> $this->string(11)->null()->defaultValue(null),
                'password_hash'=> $this->string(300)->null()->defaultValue(null),
                'verify_send_at'=> $this->string(20)->null()->defaultValue(null),
                'verify_in_one_day_count'=> $this->integer(2)->null()->defaultValue(null),
                'password_reset_token'=> $this->string(255)->null()->defaultValue(null),
                'created_by'=> $this->integer(11)->null()->defaultValue(null),
                'language'=> $this->integer(11)->null()->defaultValue(null),
                'language_parent'=> $this->integer(11)->null()->defaultValue(null),
                'invited_by'=> $this->integer(11)->null()->defaultValue(null),
            ],$tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%module_user}}');
    }
}
