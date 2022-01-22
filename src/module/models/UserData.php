<?php

namespace Yiiman\ModuleUser\module\models;

use system\lib\Model;
use system\modules\jobs\models\Jobs;
use Yii;
use yii\base\NotSupportedException;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $uid
 * @property string $key
 * @property string $val
 */
class UserData extends \system\lib\ActiveRecord
{

    const SEX_MEN = 1;
    const SEX_WOMEN = 2;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%module_user_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'key'], 'required'],

            [['key', 'val'], 'safe'],
            [['uid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getUid()
    {
        return User::findOne(['id' => $this->uid]);
    }

    /**
     * @param $uid
     * @return array|ActiveRecord[]
     */
    public static function getAll($uid)
    {
        $userData = self::find()->where(['uid' => $uid])->all();
        if (!empty($userData)) {
            return $userData;
        } else {
            return [];
        }
    }

    /**
     * @param $uid
     * @return array
     */
    public static function getMappedAll($uid)
    {
        $all = self::getAll($uid);
        if (!empty($all)) {
            return ArrayHelper::map($all, 'key', 'val');
        } else {
            return [];
        }
    }

    /**
     * @param $uid
     * @param $key
     * @return string
     */
    public static function getValue($uid, $key)
    {
        $user = self::findOne(['uid' => $uid, 'key' => $key]);
        if (!empty($user)) {
            return $user->val;
        } else {
            return '';
        }
    }

    /**
     * @param $uid
     * @param $key
     * @param $val
     */
    public static function setVal($uid, $key, $val)
    {
        $userData = self::findOne(['uid' => (int)$uid, 'key' => trim($key)]);
        if (empty($userData)) {
            $userData = new self();
        }
        $userData->uid = $uid;
        $userData->key = $key;
        $userData->val = $val;
        $userData->save();
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


    public function login($user)
    {
        if ($this->validate()) {
            return Yii::$app->user->login($user, 3600 * 24 * 30);
        }

        return false;
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
        $userMode = UserMode::find()->select(['id'])->asArray()->where(['uid' => $this->id, 'mode' => $mode])->one();
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
     * @return array|ActiveRecord[]|null
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
     * @return array|ActiveRecord[]
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
}
