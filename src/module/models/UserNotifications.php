<?php

namespace Yiiman\ModuleUser\module\models;

use phpDocumentor\Reflection\Types\This;
use Yii;

/**
 * This is the model class for table "{{%module_user_notifications}}".
 *
 * @property int $id
 * @property string $text
 * @property string $type
 * @property int $uid
 * @property int $user_mode
 * @property string $created_at
 * @property int $viewed
 */
class UserNotifications extends \system\lib\ActiveRecord
{
    const STATUS_VIEWED = 1;
    const STATUS_NOT_VIEWED = 0;


    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_INFO = 'info';
    const TYPE_ERROR = 'danger';


    const USER_MODE_ADMIN = 2;
    const USER_MODE_NORMAL = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%module_user_notifications}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'type', 'uid', 'user_mode', 'created_at', 'viewed'], 'required'],
            [['uid', 'user_mode', 'viewed'], 'integer'],
            [['created_at'], 'safe'],
            [['text', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'text' => Yii::t('user', 'Text'),
            'type' => Yii::t('user', 'Type'),
            'uid' => Yii::t('user', 'Uid'),
            'user_mode' => Yii::t('user', 'User Mode'),
            'created_at' => Yii::t('user', 'Created At'),
            'viewed' => Yii::t('user', 'Viewed'),
        ];
    }

    /**
     * افزودن یک نوتیفیکیشن برای یک کاربر فرانت
     * @param string $text
     * @param int $uid
     * @param string $type
     * @param array $options
     */
    public static function addUserNotifications(string $text, int $uid, string $type, $options = [])
    {
        $model = new self();
        $model->text = $text;
        $model->uid = $uid;
        $model->type = $type;
        $model->created_at = date('Y-m-d H:i:s');
        $model->user_mode = self::USER_MODE_NORMAL;
        $model->viewed = 0;
        $model->save();
    }

    /**
     *
     * دریافت همه ی اعلانیه های کاربر
     * @param $uid
     * @return null|self[]
     */
    public static function getNotifications($uid)
    {
        return self::find()->where(['uid' => $uid, 'user_mode' => self::USER_MODE_NORMAL, 'viewed' => 0])->orderBy(['created_at' => SORT_DESC])->all();
    }

}

