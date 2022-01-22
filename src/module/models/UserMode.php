<?php

namespace Yiiman\ModuleUser\module\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "module_user_mode".
 *
 * @property int $uid
 * @property string $mode
 *
 * @property User $u
 */
class UserMode extends ActiveRecord
{
    const MODE_NORMAL = 'normal';
    const MODE_AGENT = 'agent';
    const MODE_AGENT_REJECTED = 'agentRejected';
    const MODE_CLOSED = 'closed';//کاربر مسدود شده است
    const MODE_PROFILE_COMPLETED = 'profileCompleted';// پروفایل کاربر کامل شده است
    const MODE_PROFILE_IS_LEGAL = 'profileIsLegal';// پروفایل از نوع حقوقی است
    const MODE_PROFILE_CONFIRMED = 'ProfileConfirmed';//  پروفایل کاربری به تایید ناظر رسیده است F11
    const MODE_PROFILE_REJECTED = 'ProfileRejected';//  پروفایل کاربری رد صلاحیت شده است F11
    const MODE_HAS_CARD = 'hasCard';//کاربری که مدیکارت منقضی نشده دارد
    const MODE_LEGATE = 'legate';//سفیر
    const MODE_SUPERVISOR = 'supervisor';//ناظر


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'module_user_mode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'mode'], 'required'],
            [['uid'], 'integer'],
            [['mode'], 'string', 'max' => 255],
            [['uid'], 'exist', 'skipOnError' => true, 'targetClass' => \Yiiman\ModuleUser\module\models\User::className(), 'targetAttribute' => ['uid' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'mode' => 'Mode',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getU()
    {
        return $this->hasOne(User::className(), ['id' => 'uid']);
    }
}
