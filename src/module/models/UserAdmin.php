<?php

namespace Yiiman\ModuleUser\module\models;

use system\lib\SoftEtherApi\Model\AuthType;
use system\lib\View;
use system\modules\jobs\models\Jobs;
use system\modules\sms\base\Sms;
use Upload\Exception;
use Yii;
use yii\base\NotSupportedException;

use system\lib\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $hash
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
 * @property string $verify_in_one_day_count تعداد دفعاتی که کد وریفای در یک روز ارسال شده است
 * @property string $avatar
 * @property string $verify_send_at
 * @property string $image
 * @property string $attached
 * @property string $status_job
 */
class UserAdmin extends ActiveRecord implements IdentityInterface
{
    public $upload;
    public $upload2;
    const STATUS_IN_ACTIVE = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_WAIT_VERIFY = 20;

    const SEX_MEN = 1;
    const SEX_WOMEN = 2;

    const STATUS_JOB_UNACTIVE = 21;
    const STATUS_JOB_SEND_ATTACHED = 22;
    const STATUS_JOB_ACTIVE = 20;
    const SCENARIO_STATUS_JOBS = 'status-jobs';

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
            [['username'], 'required'],
//            [['attached'], 'required', 'on' => self::SCENARIO_STATUS_JOBS],
            [['created_at', 'updated_at', 'verify_send_at', 'birthday', 'updated_by'], 'safe'],
            [['status', 'credit', 'sex', 'score', 'status_job', 'verify_in_one_day_count'], 'integer'],
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


    /**
     * این تابع مرحله ی اول ثبت نام، یعنی ایجاد کاربر جدید درصورت عدم وجود و ارسال یک کد وریفای به گوشی وی را اجام می دهد
     *
     * و سپس نتیجه را در قالب آرایه به شما بازگردانی میکند
     *
     *
     * برای استفاده ی راحت تر، از جاوا اسکریپت ساخته شده در همین کلاس برای لاگین استفاده کنید
     *
     *
     * شماره موبایل را باید به صورت پست با نام mobile برای این تابع ارسال کنید
     *
     * @return array
     */
    public function sendVerifyCode()
    {
        if (empty($this->username)) {
            $post = \Yii::$app->request->post();
            $mobile = $post['mobile'];
        } else {
            $mobile = $this->username;
        }

        $model = User::findOne(['username' => $mobile]);
        if (!empty($model)) {
            switch ($model->status) {
                case $model::STATUS_WAIT_VERIFY:
                    $lastDay = strtotime('+ 1 days', strtotime($model->verify_send_at));
                    $lastSentSms = strtotime('+ 3 minutes', strtotime($model->verify_send_at));
                    $now = time();

                    if ($now < $lastSentSms) {
                        return
                            [
                                'status' => 'error',
                                'message' => Yii::t('site', 'لطفا تا {time} برای درخواست مجدد پیامک اعتبارسنجی صبر کنید', ['time' => Yii::$app->functions->showDateTime(date('Y-m-d H:i:s', $lastSentSms))])
                            ];
                    } else {
                        if ($model->verify_in_one_day_count <= \Yii::$app->Options->MaxVerifySms) {
                            $model->verification = (string)rand(123456, 987654);
                            $model->verify_send_at = date('Y-m-d H:i:s');
                            $model->verify_in_one_day_count = ((int)$model->verify_in_one_day_count) + 1;

                            try {
                                $numericMobile = str_replace(['-', '(', ')'], '', $model->username);
                                Sms::sendPattern('verifycode',$numericMobile,$model->name, $model->verification);
                            } catch (\Exception $e) {
                                Yii::debug($e->getMessage(), 'sms');
//                                return
//                                    [
//                                        'status' => 'error',
//                                        'message' => 'خطایی در ارسال پیامک رخ داده است، لطفا مجددا امتحان کنید',
//                                        'error' => $e->getMessage()
//                                    ];
                            }

                            if ($model->save()) {
                                return
                                    [
                                        'status' => 'register'
                                    ];
                            } else {
                                return
                                    [
                                        'status' => 'errors', 'messages' => $model->errors
                                    ];
                            }
                        } else {
                            return
                                [
                                    'status' => 'error',
                                    'message' => Yii::t('site', 'لطفا تا {time} برای درخواست مجدد پیامک اعتبارسنجی صبر کنید', ['time' => Yii::$app->functions->showDateTime(date('Y-m-d H:i:s', $lastDay))])

                                ];
                        }
                    }


                    break;
                case $model::STATUS_ACTIVE:
                    return
                        [
                            'status' => 'login'
                        ];
                    break;
                case $model::STATUS_IN_ACTIVE:
                    return
                        [
                            'status' => 'error', 'message' =>
                            \Yii::t('site', 'کاربری شما توسط مدیر سایت محدود شده است و امکان ورود به سایت را ندارید، لطفا با پشتیبانی سایت تماس بگیرید')
                        ];
                    break;
            }

            return
                [
                    'status' => 'login'
                ];
        } else {
            $user = new User();
            $user->username = $mobile;
            $user->verification = (string)rand(123456, 987654);
            $user->status = $user::STATUS_WAIT_VERIFY;
            $user->verify_send_at = date('Y-m-d H:i:s');
            $user->verify_in_one_day_count = 1;
            $user->setPassword('123456');
            $user->generateAuthKey();

            if ($user->save()) {
                try {
                    $numericMobile = str_replace(['-', '(', ')'], '', $user->username);
                    Sms::sendPattern('verifycode',$numericMobile, $user->name,$user->verification);
                } catch (\Exception $e) {
                    Yii::error($e->getMessage(), 'sms');
                    return
                        [
                            'status' => 'error', 'message' => 'خطایی در ارسال پیامک رخ داده است، لطفا مجددا امتحان کنید'
                        ];
                }
                return
                    [
                        'status' => 'register'
                    ];
            } else {
                return ['status' => 'error', 'messages' => $user->errors];
            }
        }
    }

    public function checkAjaxMobile()
    {
        $post = Yii::$app->request->post();
        if (!empty($post['code']) && !empty($post['mobile'])) {
            $model = User::findOne(['username' => str_replace([' ', '-', '_', '(', ')'], '', $post['mobile'])]);
            if (!empty($model)) {
                $code = str_replace(['_', ' ', '-'], '', $post['code']);
                if ((string)$model->verification == (string)$code) {
                    $model->verify_send_at = null;
                    $model->verify_in_one_day_count = 0;
                    $model->status = $model::STATUS_ACTIVE;
                    if ($model->save()) {
                        return ['status' => 'verified'];
                    } else {
                        return ['status' => 'errors', 'message' => $model->firstErrors];
                    }
                } else {
                    return ['status' => 'error', 'message' => Yii::t('site', 'کد وارد شده اشتباه است')];
                }
            } else {
                return ['status' => 'error', 'message' => Yii::t('site', 'کاربر یافت نشد')];

            }
        } else {
            return ['status' => 'error', 'message' => Yii::t('site', 'اطلاعات ارسالی دارای ایراد است')];
        }

    }


    public function finalRegisterStep()
    {
        $post = Yii::$app->request->post();
        if (!empty($post['name']) && !empty($post['password']) && !empty($post['mobile'])) {
            $model = User::findOne(['username' => str_replace([' ', '-', '_', '(', ')'], '', $post['mobile'])]);
            $model->fullname = $post['name'];
            $model->setPassword(trim($post['password']));
            if ($model->save()) {
                Yii::$app->user->switchIdentity($model);
                return ['status' => 'registered'];
            } else {
                return ['status' => 'errors', 'message' => $model->firstErrors];
            }
        } else {
            return ['status' => 'error', 'message' => Yii::t('site', 'اطلاعات ارسالی دارای ایراد است')];
        }
    }


    /**
     *
     * کاربر را با استفاده از ایکجس لاگین میکند
     * @return array|string[]
     */
    public function checkLogin()
    {
        $post = Yii::$app->request->post();
        if (!empty($post['mobile']) && !empty($post['password'])) {
            $model = User::findOne(['username' => str_replace([' ', '-', '_', '(', ')'], '', $post['mobile'])]);
            if (!empty($model)) {
                if ($model->validatePassword($post['password'])) {
                    Yii::$app->user->login($model, 3600 * 24 * 30);
                    return ['status' => 'logined'];
                }
            } else {
                return ['status' => 'error', 'message' => Yii::t('site', 'کاربر یافت نشد')];
            }
        } else {
            return ['status' => 'error', 'message' => Yii::t('site', 'اطلاعات ارسالی دارای ایراد است')];
        }
    }

    /**
     *
     * کاربر را با استفاده از ایکجس لاگین میکند
     * @return array|string[]
     */
    public function checkLogout()
    {
        $post = Yii::$app->request->post();
        Yii::$app->user->logout();
        return ['status' => 'success'];


    }


    /**
     * تمام سلکتور های موجود در این داکیومنت را در رابط کاربری خود ایجاد کنید:
     *
     * این تابع، فایل های جاوا اسکریپت مربوط به ثبت نام را در رابط کاربری لود میکند
     *
     * container = $('#register-form');
     *
     *
     * container_mobile = $('#mobile-register-box');
     *
     *
     * container_fields = $('#register-fields');
     *
     *
     * container_verify = $('#verify-fields');
     *
     *
     * container_error = $('#register-errors');
     *
     *
     *
     * btn_register = $('#register-button');
     *
     *
     * btn_verify = $('#verify-button-a');
     *
     *
     * btn_send_mobile_button = $('#send-mobile-button');
     *
     *
     *
     * input_register_name = $('#register-name');
     *
     *
     * input_mobile_register = $('#register-mobile');
     *
     *
     * input_register_password = $('#register-password');
     *
     *
     * input_register_password_confirm = $('#register-password-confirm');
     *
     *
     * input_verify_field = $('#verify-code-input');
     *
     *
     *
     * check_privacy = $('#check-privacy:checked');
     *
     *
     * check_laws = $('#check-laws:checked');
     *
     *
     *
     *
     * loginTab = $('#tab-login');
     *
     *
     *
     *
     *
     */
    public function registerJsForRegister()
    {
        $sendMobileUrl = Yii::$app->urlManager->createUrl(['/ajax/send-mobile']);
        $registerUrl = Yii::$app->urlManager->createUrl(['/ajax/register']);
        $checkMobileUrl = Yii::$app->urlManager->createUrl(['/ajax/check-mobile']);
        $texts =
            [
                'no_name_entered' => Yii::t('site', 'لطفا نام خود را وارد کنید'),
                'name_not_valid' => \Yii::t('site', 'نام وارد شده معتبر نیست'),
                'passwords_not_same' => \Yii::t('site', 'رمز های عبور وارد شده یکی نیستند'),
                'password_not_valid' => \Yii::t('site', 'رمز عبور انتخابی معتبر نیست'),
                'password_confirm_not_entered' => \Yii::t('site', 'لطفا رمزعبور خود را در هر دو فیلد ورودی رمز وارد کنید'),
                'password_is_under_7_character' => \Yii::t('site', 'رمزعبور انتخابی باید 7 رقم یا بیشتر باشد'),
                'password_not_entered' => \Yii::t('site', 'رمزعبور وارد نشده است'),
                'laws_not_checked' => \Yii::t('site', 'لطفا تیک قوانین را بزنید'),
                'privacy_not_checked' => \Yii::t('site', 'لطفا تیک شرایط حریم خصوصی را فعال کنید')
            ];

        Yii::$app->view->registerJs('var tokappsRegisterTexts=' . json_encode($texts) . ';'
            . 'var sendMobile=\'' . $sendMobileUrl . '\';
                var registerUrl=\'' . $registerUrl . '\';
                var checkMobileUrl=\'' . $checkMobileUrl . '\';'
            , View::POS_HEAD);
        Yii::$app->view->registerJs(file_get_contents(__DIR__ . '/../assets/register.js'), View::POS_END);

    }


    /**
     * تمام سلکتور های موجود در این داکیومنت را در رابط کاربری خود ایجاد کنید:
     *
     * این تابع، فایل های جاوا اسکریپت مربوط به لاگین را در رابط کاربری لود میکند
     *
     * this.container_error = $('#register-errors');
     *
     *
     * this.btn_login = $('#login-button');
     *
     *
     * this.input_mobile = $('#login-mobile');
     *
     *
     * this.input_password = $('#login-password');
     *
     *
     */
    public function registerJsForLogin()
    {
        $loginUrl = Yii::$app->urlManager->createUrl(['/ajax/login']);

        $texts =
            [
                'InvalidMobile' => Yii::t('site', 'لطفا شماره موبایل را کامل وارد کنید'),
                'PasswordUnder7Character' => \Yii::t('site', 'رمز عبور باید حداقل 7 رقم باشد'),
                'EnterPassword' => \Yii::t('site', 'لطفا رمز عبور خود را وارد کنید'),
                'EnterMobile' => \Yii::t('site', 'لطفا شماره همراه خود را وارد کنید'),
            ];

        Yii::$app->view->registerJs('var tokappsLoginTexts=' . json_encode($texts) . ';'
            . 'var loginUrl=\'' . $loginUrl . '\';'
            , View::POS_HEAD);
        Yii::$app->view->registerJs(file_get_contents(__DIR__ . '/../assets/login.js'), View::POS_END);

    }


    /**
     * تمام سلکتور های موجود در این داکیومنت را در رابط کاربری خود ایجاد کنید:
     *
     * این تابع، فایل های جاوا اسکریپت مربوط به لاگ اوت را در رابط کاربری لود میکند
     *
     * this.btn_logout = $('#btn-logout');
     *
     *
     */
    public function registerJsForLogout()
    {
        $logoutUrl = Yii::$app->urlManager->createUrl(['/ajax/logout']);


        Yii::$app->view->registerJs(
            'var logoutUrl=\'' . $logoutUrl . '\';'
            , View::POS_HEAD);
        Yii::$app->view->registerJs(file_get_contents(__DIR__ . '/../assets/logout.js'), View::POS_END);

    }


    /**
     * افزودن مود های جدید به حساب کاربری کاربر
     * @param $mode string
     */
    public function addMode(string $mode)
    {
        $userMode = $this->hasMode($mode);
        if (!empty($userMode)) {
            $userMode = new UserMode();
            $userMode->uid = $this->id;
            $userMode->mode = $mode;
            $userMode->save();
        }
    }

    /**
     * افزودن مود های جدید به حساب کاربری کاربر
     * @param $mode string
     */
    public static function addMode0($uid, $mode)
    {
        $userMode = self::hasMode0($uid, $mode);
        if (!empty($userMode)) {
            $userMode = new UserMode();
            $userMode->uid = $uid;
            $userMode->mode = $mode;
            $userMode->save();
        }
    }

    /**
     * همه ی مودهای کاربری را بازگردانی میکند
     * @return array
     */
    public function getAllModes()
    {
        $userMode = UserMode::find()->select(['mode'])->asArray()->where(['uid' => $this->id])->all();
        if (!empty($userMode)) {
            return ArrayHelper::getColumn($userMode, 'mode');
        } else {
            return [];
        }
    }

    /**
     * همه ی مود های کاربری را بازگردانی میکند
     * @param $uid
     * @return array
     */
    public static function getAllModes0($uid)
    {
        $userMode = UserMode::find()->select(['mode'])->asArray()->where(['uid' => $uid])->all();
        if (!empty($userMode)) {
            return ArrayHelper::getColumn($userMode, 'mode');
        } else {
            return [];
        }
    }

    /**
     * بررسی میکند که آیا مود مورد نظر برای کاربر موجود هست یا خیر
     * @param $mode
     * @return bool
     */
    public function hasMode($mode)
    {
        $userMode = UserMode::find()->select(['mode'])->asArray()->where(['uid' => $this->id, 'mode' => $mode])->one();
        if (!empty($userMode)) {
            unset($userMode);
            return true;
        } else {
            unset($userMode);
            return false;
        }
    }

    /**
     * بررسی میکند که آیا مود مورد نظر برای کاربر مورد نظر موجود است یا خیر
     *
     * @param $uid
     * @param $mode
     * @return bool
     */
    public static function hasMode0($uid, $mode)
    {
        $userMode = UserMode::find()->select(['id'])->asArray()->where(['uid' => $uid, 'mode' => $mode])->one();
        if (!empty($userMode)) {
            unset($userMode);
            return true;
        } else {
            unset($userMode);
            return false;
        }
    }

    /**
     * حذف یک مود خاص از حساب کاربر
     * @param $mode
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteMode($mode)
    {
        $userMode = UserMode::find()->where(['uid' => $this->id, 'mode' => $mode])->one();
        if (!empty($userMode)) {
            $userMode->delete();
        }
    }

    /**
     * حذف یک مود خاص از حساب کاربر
     * @param $uid
     * @param $mode
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteMode0($uid, $mode)
    {
        $userMode = UserMode::find()->where(['uid' => $uid, 'mode' => $mode])->one();
        if (!empty($userMode)) {
            $userMode->delete();
        }
    }


    /**
     * یافتن مدل کاربرانی که دارای یک مود خاص هستند
     * @param $mode
     * @return array|\yii\db\ActiveRecord[]|null
     */
    public static function findUserModelsByMode($mode)
    {
        $ids = self::findUserIdsByMode($mode);

        if (!empty($ids)) {
            return User::find()->where(['id' => $ids])->all();
        } else {
            return null;
        }
    }

    /**
     * یافتن شناسه ی کاربری کاربرانی که دارای یک مود خاص هستند
     * @param $mode
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findUserIdsByMode($mode)
    {
        $users = UserMode::find()->select(['uid'])->asArray()->where(['mode' => $mode])->all();
        if (!empty($users)) {
            return ArrayHelper::getColumn($users, 'uid');
        } else {
            return [];
        }
    }

    /**
     * بازگردانی کاربران به صورت مپ شده برای سلکت باکس
     * @param $mode
     * @param $userFields string فیلدی که باید از جدول کاربران بازگردانی شد
     * @return array
     */
    public static function find_mapped_users_has_mode($mode, $userFields)
    {
        $users = self::findUserModelsByMode($mode);
        if (!empty($users)) {
            return ArrayHelper::map($users, 'id', $userFields);
        } else {
            return [];
        }
    }

    public function __get($name)
    {
        try {

            $out = parent::__get($name); // TODO: Change the autogenerated stub
            return $out;
        } catch (Exception $e) {
            $userData = UserData::findOne(['uid' => $this->id, 'key' => $name]);
            if (!empty($userData)) {
                return $userData->val;
            } else {
                return '';
            }
        }
    }

    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value); // TODO: Change the autogenerated stub
        } catch (\Exception $e) {
            $userData = UserData::findOne(['uid' => $this->id, 'key' => $name]);
            if (empty($userData)) {
                $userData = new UserData();
                $userData->uid = $this->id;
                $userData->key = $name;
            }
            $userData->val = $value;
            $userData->save();
        }
    }

}
