<?php

namespace Yiiman\ModuleUser\module\models;

use system\modules\sms\base\Sms;
use Yiiman\ModuleUser\module\models\User;
use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;

class VerifyCodeForm extends Model
{
    public $code;
    public $hash;


    public function rules()
    {
        return [
            [['code', 'hash'], 'required'],
            [
                'code', 'verify'
            ]
        ];
    }

    public function attributeLabels()
    {
        return
            [
                'code' => Yii::t('user', 'کد اعتبارسنجی'),
            ];
    }

    /**
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function verify()
    {


        if (empty($this->code)) {
            $this->addError('code', 'کدی وارد نکردید');
        }
        $this->code = str_replace(['_', '-', ' '], '', $this->code);

        $user = User::findOne(['verification' => $this->code, 'hash' => $this->hash]);

        /* < Code is Valid > */

        if (!empty($user)) {
            if ($user->password_reset_token == 'reseting') {
                $password = rand(11111111, 99999999);
                $user->verify_in_one_day_count=1;
                $user->setPassword($password);
                $user->save();
                Sms::sendPattern('accountpassword',str_replace(['_', '-', ' ', '(', ')'], '', $user->username),$user->name,  $password);
                UserNotifications::addUserNotifications('رمز عبور شما روی ' . $password . ' تنظیم شده است، لطفا از بخش پروفایل رمز عبور خود را تغییر دهید', $user->id, UserNotifications::TYPE_SUCCESS);
                $user->login($user);
            } else {
                $user->verification = null;
                $user->verify_in_one_day_count=1;
                $user->status = User::STATUS_ACTIVE;
                $user->save();
                $user->login($user);
            }
            return true;
        }

        /* </ Code is Valid > */

        /* < Code Is Not Valid > */

        else {
            $this->addError('code', 'کد اعتبارسنجی اشتباه است');
            return false;
        }

        /* </ Code Is Not Valid > */


    }

}
