<?php

namespace Yiiman\ModuleUser\module\models;

use common\models\Agency;
use common\models\Neighbourhoods;
use phpDocumentor\Reflection\Types\This;
use Yiiman\ModuleUser\module\models\User;
use Yiiman\ModuleUser\module\validators\NationalCodeValidator;
use system\validators\Mobile;
use Yii;
use yii\base\Model;
use common\models\Provinces;
use common\models\Cities;

class SignupForm extends Model
{
    public $name;
    public $family;
    public $email;
    public $nationCode;
    public $username;
    public $password;
    public $passwordRepeat;
    public $phone;
    private $_hash;

    public function rules()
    {
        return
            [
                [
                    'username',
                    'unique',
                    'targetClass' => User::className(),
                    'message' => Yii::t('base', 'این شماره همراه قبلا ثبت شده است.')
                ],
                [
                    ['name', 'family', 'nationCode'], 'required'
                ],
                ['username', 'trim'],
                ['username', 'required'],
                ['username', 'string', 'min' => 10, 'max' => 15],


                ['password', 'required'],
                ['password', 'string', 'min' => 6, 'max' => 22],
                [
                    ['name', 'family'],
                    'string',
                    'min' => 3,
                    'max' => 30,
                ],
                [
                    'passwordRepeat',
                    'compare',
                    'compareAttribute' => 'password',
                    'message' => 'رمز عبور های وارد شده یکسان نیستند'
                ],
                [
                    'nationCode',
                    NationalCodeValidator::className()
                ]
            ];
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('base', 'شماره همراه'),
            'password' => Yii::t('base', 'رمز عبور'),
            'passwordRepeat' => Yii::t('base', 'تکرار رمز عبور'),
            'name' => 'نام',
            'family' => 'نام خانوادگی',
            'nationCode' => 'گد ملی'
        ];
    }

    public function register()
    {
        $user = new User();
        $user->setPassword($this->password);
        $user->fullname = $this->name . ' ' . $this->family;
        $user->nation_code = (string)$this->nationCode;
        $user->created_at = date('Y-m-d H:i:s');
        $user->status = $user::STATUS_WAIT_VERIFY;
        $user->updated_at = $user->created_at;
        $user->family = $this->family;
        $user->username = $this->username;
        $user->name = $this->name;
        $user->hash = hash('ripemd160', $user->name . $user->username);
        $this->_hash=$user->hash;
        if ($user->save()) {
            $result = $user->sendVerifyCode();
            switch ($result['status']) {
                case 'error':
                    if (!empty($result['message'])) {
                        $this->addError('username', $result['message']);
                    }
                    if (!empty($result['messages'])) {
                        $this->addErrors($result['messages']);
                    }
                    return 'error';
                    break;
                case 'errors':
                    $this->addErrors($result['messages']);
                    return 'error';
                    break;
                case 'register':
                    return 'register';
                    break;
                case 'login':
                    return 'login';
                    break;
            }
        }
    }
    public function getHash(){
        return $this->_hash;
    }
}
