<?php


namespace Yiiman\ModuleUser\module\validators;


use system\lib\Model;
use yii\validators\EachValidator;
use yii\validators\IpValidator;
use yii\validators\StringValidator;
use yii\validators\Validator;

class NationalCodeValidator extends Validator
{

    protected function validateValue($value)
    {
        if(strlen($value)==0){
            return ['لطفا کدملی را وارد کنید',[]];
        }
        if (!$this->validateNationalCode($value)) {
            return ['کدملی صحیح نیست', []];
        }
    }

    public function validateAttribute($model, $attribute)
    {
        if (empty($model->{$attribute})){
            $this->addError($model,$attribute,'لطفا کدملی را وارد کنید');
            return;
        }

        if (!$this->validateNationalCode($model->{$attribute})) {
            $this->addError($model, $attribute,'کدملی صحیح نیست');
        }
        return;
    }

    public function validateNationalCode($code)
    {
        $code=str_replace([' ','-','(',')','_'],'',$code);
        if (!preg_match('/^[0-9]{10}$/', $code))
            return false;
        for ($i = 0; $i < 10; $i++)
            if (preg_match('/^' . $i . '{10}$/', $code))
                return false;
        for ($i = 0, $sum = 0; $i < 9; $i++)
            $sum += ((10 - $i) * intval(substr($code, $i, 1)));
        $ret = $sum % 11;
        $parity = intval(substr($code, 9, 1));
        if (($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity))
            return true;
        return false;

    }
}
