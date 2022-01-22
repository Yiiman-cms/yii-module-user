<?php

namespace Yiiman\ModuleUser\module\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;

class ResetPasswordForm extends Model
{
    public $username;
    /**
     * @var User
     */
    private $_user = null;

    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'validateUsername']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('base', 'شماره همراه'),
        ];
    }

    public function validateUsername()
    {
        $this->_user = User::findOne(['username' => $this->username, 'status' => User::STATUS_ACTIVE]);
        if (empty($this->_user)) {
            $this->addError('username', 'این نام کاربر وجود ندارد، یا غیر فعال است');
            return false;
        }
    }

    /**
     * @return User|null
     */
    public function sendCode()
    {
        $status=$this->_user->sendVerifyCode(true);
        switch ($status){
            case 'error':
            case 'errors':
                return false;
                break;
        }
        $this->_user->password_reset_token='reseting';
        $this->_user->save();
        return $this->_user;
    }
}
