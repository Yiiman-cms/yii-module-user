<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model \frontend\models\ChangePassword */
$this->title = Yii::t('base', 'Change Password');
?>

<div>
    <h2 class="change-password-title">تغییر رمز عبور</h2>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <?php $form = ActiveForm::begin(); ?>
            <div class="form-group">
                <label class="form-label">
                    رمز عبور فعلی
                </label>
                <?= $form->field($model, 'old_password')->passwordInput(['class'=>'form-control'])->label(false); ?>
            </div>
        </div>
        <div class="w-100"></div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="form-label">
                    رمز عبور جدید
                </label>
                <?= $form->field($model, 'new_password')->passwordInput(['class'=>'form-control'])->label(false); ?>

            </div>
        </div>
        <div class="w-100"></div>
        <div class="col-md-5">
            <div class="form-group">
                <label class="form-label">
                    تکرار رمز عبور جدید
                </label>
                <?= $form->field($model, 'new_password_repeat')->passwordInput(['class'=>'form-control'])->label(false); ?>

            </div>
        </div>
    </div>
    <br><br><br>
    <div class="text-center">
        <button type="submit" class="custom-btn-orange btn-hover-d">
            ذخیره رمز عبور
        </button>
    </div>
    <?php ActiveForm::end(); ?>

</div>
