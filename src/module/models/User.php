<?php

namespace Yiiman\ModuleUser\module\models;

use system\modules\jobs\models\Jobs;
use Yii;
use yii\base\NotSupportedException;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property string $nation_code
 * @property string $birthday
 * @property string $fullname
 * @property string $name
 * @property string $family
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property int $sex
 * @property string $credit
 * @property string $score
 * @property string $bank_kard
 * @property string $verification
 * @property string $jobs
 * @property string $avatar
 * @property string $image
 * @property string $attached
 * @property string $status_job
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $upload;
    public $upload2;
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const SEX_MEN = 1;
    const SEX_WOMEN = 2;

    const STATUS_JOB_UNACTIVE = 21;
    const STATUS_JOB_SEND_ATTACHED = 22;
    const STATUS_JOB_ACTIVE = 20;
    const SCENARIO_STATUS_JOBS='status-jobs';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%module_user}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'created_at', 'status'], 'required'],
//            [['attached'], 'required', 'on' => self::SCENARIO_STATUS_JOBS],
            [['created_at', 'updated_at', 'birthday', 'updated_by'], 'safe'],
            [['status', 'credit', 'sex', 'score','status_job'], 'integer'],
            [
                [
                    'username',
                    'password_hash',
                    'password_reset_token',
                    'fullname',
                    'verification',
                    'name',
                    'family',
                    'created_by',
                    'deleted_by',
                    'restored_by',
                    'nation_code',
                    'bank_card',
                    'jobs',
                    'attached',
                    'email',

                ],
                'string',
                'max' => 255
            ],
            [['mobile'], 'string', 'max' => 11],
            [['auth_key'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'شناسه ی خصوصی'),
            'username' => Yii::t('user', 'شماره ی همراه'),
            'mobile' => Yii::t('user', 'شماره ی همراه'),
            'auth_key' => Yii::t('user', 'Auth Key'),
            'password_hash' => Yii::t('user', 'Password Hash'),
            'password_reset_token' => Yii::t('user', 'Password Reset Token'),
            'created_at' => Yii::t('user', 'تاریخ ثبت نام'),
            'updated_at' => Yii::t('user', 'تاریخ بروزرسانی'),
            'fullname' => Yii::t('user', 'نام'),
            'status' => Yii::t('user', 'وضعیت کاربری'),
            'verification' => Yii::t('user', 'کد اعتبار سنجی'),
            'name' => Yii::t('user', 'نام'),
            'family' => Yii::t('user', 'نام خانوادگی'),
            'birthday' => Yii::t('user', 'تاریخ تولد'),
            'created_by' => Yii::t('user', 'ایجاد شده توسط'),
            'updated_by' => Yii::t('user', 'بروزرسانی شده توسط'),
            'deleted_by' => Yii::t('user', 'حذف شده توسط'),
            'restored_by' => Yii::t('user', 'بازگردانی شده توسط'),
            'credit' => Yii::t('user', 'موجودی حساب'),
            'nation_code' => Yii::t('user', 'کد ملی'),
            'bank_card' => Yii::t('user', 'شماره ی کارت اعتباری'),
            'jobs' => Yii::t('user', 'شغل'),
            'attached' => Yii::t('user', 'مدارک تایید شغل'),
            'status_job' => Yii::t('user', 'وضعیت شغل'),
            'sex' => Yii::t('user', 'جنسیت'),
            'email' => Yii::t('user', 'ایمیل'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

// < عنوان شغل رو میاره >
    public function getJobs($id)
    {
        $jobs = Jobs::findOne($id);

        return !empty($jobs->job_title) ? $jobs->job_title : '';
    }
// </  >

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public
    static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public
    static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(
            [
                'password_reset_token' => $token,
                'status' => self::STATUS_ACTIVE,
            ]
        );
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public
    static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public
    function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public
    function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public
    function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public
    function validatePassword($password)
    {

        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public
    function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public
    function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public
    function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public
    function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    public
    function login($user)
    {
        if ($this->validate()) {
            return Yii::$app->user->login($user, 3600 * 24 * 30);
        }

        return false;
    }
}
