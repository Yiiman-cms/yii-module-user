<?php
namespace Yiiman\ModuleUser\module\models;
/**
 * با این کلاس اطلاعات پک شده را بوسیله ی آرایه ی جیسان به کاربر منتصب کنید
 * @property string $key
 * @property int $id
 * @property int $uid
 * @property string $packed_data
 *
 */
class UserPackedDataModel extends \system\lib\ActiveRecord
{

    public static function tableName()
    {
        return 'module_user_packed_data';
    }

    public function rules()
    {
        return
            [
                [['uid', 'packed_data', 'key'], 'required'],
                ['packed_data', 'safe'],
            ];
    }

    /**
     * بروزرسانی اطلاعات یک کلید برای کاربر
     * @param int $id
     * @param array $data
     */
    public static function UpdateKey(int $id,array $data)
    {
        $model=self::findOne($id);
        if (!empty($model)){
            $model->packed_data=$data;
            $model->save();
        }
    }

    /**
     * الصاق اطلاعات پک شده به حساب کاربر
     * @param string $key
     * @param array $data
     * @param \Yiiman\ModuleUser\module\models\User $user
     */
    public static function addKey(string $key, array $data, \Yiiman\ModuleUser\module\models\User $user)
    {
        $model = new self();
        $model->packed_data = json_encode($data);
        $model->key = $key;
        $model->uid = $user->id;
        $model->save();
    }

    /**
     * دریافت اطلاعات کاربر به صورت انپک شده
     * @param string $key
     * @param int $uid
     * @return array
     */
    public static function getAll(string $key, int $uid)
    {
        $model = self::find()->where(['uid' => Yii::$app->user->id])->all();
        $out = [];
        if (!empty($model)) {
            foreach ($model as $m) {
                /**
                 * @var $m self
                 */
                $out[] = json_decode($m->packed_data);
            }
        }
        return $out;
    }

}

