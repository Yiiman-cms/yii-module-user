<?php

use system\modules\filemanager\widget\FileSelectorWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model Yiiman\ModuleUser\module\models\User */
/* @var $form yii\widgets\ActiveForm */
$js = <<<JS
$('button.btn-success').click(function (e){
    var passLength=$('#passreset').val().length;
    if (passLength>0 && passLength<8){
        e.preventDefault();
        alert('رمز عبور باید حداقل ۸ رقم باشد');
    }
});
JS;
$this->registerJs($js, $this::POS_END);
?>
<div class="user-form">

    <?php $form = ActiveForm::begin(
        [
            'enableAjaxValidation' => true,
            'validateOnSubmit' => true,
            'validateOnChange' => false,
            'validateOnBlur' => false,
            'validateOnType' => false,
            'enableClientValidation' => true,
            'options' => ['enctype' => 'multipart/form-data']
        ]
    ); ?>

    <div class="row">
        <div class="col-md-3">
            <div class="row">
                <div class="card card-nav-tabs">
                    <div class="card-body ">

                        <div class="row">
                            <h4 class="text-center">وضعیت انتشار</h4>

                            <div class="col-md-12 pull-right">
                                <?= $form->field($model, 'status')->widget(
                                    \kartik\select2\Select2::className(),
                                    [
                                        'data' =>
                                            [
                                                \Yiiman\ModuleUser\module\models\User::STATUS_ACTIVE => 'منتشر شده',
                                                \Yiiman\ModuleUser\module\models\User::STATUS_IN_ACTIVE => 'غیر فعال',
                                                \Yiiman\ModuleUser\module\models\User::STATUS_WAIT_VERIFY => 'وریفای نشده',
                                            ],
                                        'options' => ['dir' => 'rtl'],
                                        'pluginOptions' => ['dir' => 'rtl'],
                                        'pluginEvents' => [
                                            "change" => "function() {  }",
                                            "select2:opening" => "function() {  }",
                                            "select2:open" => "function() {  }",
                                            "select2:closing" => "function() {  }",
                                            "select2:close" => "function() {  }",
                                            "select2:selecting" => "function() {  }",
                                            "select2:select" => "function() {  }",
                                            "select2:unselecting" => "function() {  }",
                                            "select2:unselect" => "function() {  }"
                                        ]
                                    ]
                                )->label(false) ?>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">ذخیره</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $model->image_input_widget($form, 'تصویر پروفایل', true, ['image'], [], 'image', 1) ?>
                </div>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="card card-nav-tabs">
                    <div class="card-body ">
                        <h4 class="text-center">تنظیم رمز جدید</h4>
                        <div class="row">
                            <div class="col-md-12 pull-right">
                                <div class="form-group field-user-national_number required bmd-form-group bmd-form-group-lg is-empty ">
                                    <label class="col-form-label bmd-label-static" for="passreset">رمز جدید</label>
                                    <input type="text" id="passreset"
                                           class="form-control form-control-lg form-control-solid"
                                           name="resetPass" value=""
                                           aria-invalid="true">
                                    <p>حداقل ۸ رقم</p>
                                    <div class="help-block"></div>
                                    <span class="material-input"></span>
                                </div>
                                <p>چنانچه قصد دارید رمز کاربر را تغییر دهید، رمز را در این قسمت درج نمایید</p>
                                <p>پس از تغییر رمز، یک پیامک حاوی رمز جدید برای کاربر ارسال میگردد</p>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card card-nav-tabs">
                <div class="card-body ">
                    <div class="col-md-12">
                        <h4 class="text-center">مشخصات</h4>
                        <div class="col-md-4">
                            <?= $form->field($model, 'username')->textInput(['class' => 'form-control form-control-lg form-control-solid', 'style' => 'direction:ltr !important', 'disabled' => 'disabled'])->label(null, ['class' => 'col-form-label']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'name')->textInput(['class' => 'form-control form-control-lg form-control-solid'])->label(null, ['class' => 'col-form-label']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'family')->textInput(['class' => 'form-control form-control-lg form-control-solid'])->label(null, ['class' => 'col-form-label']) ?>
                        </div>


                        <div class="col-md-6">
                            <?= $form->field($model, 'city')->dropDownList(
                                \yii\helpers\ArrayHelper::map(\system\modules\location\models\LocationCity::find()->all(), 'id', 'name'),
                                ['id' => 'province-id', 'class' => 'form-control form-control-lg form-control-solid select2',]
                            )->label(null, ['class' => 'col-form-label']) ?>
                        </div>

                        <div class="col-md-3">
                            <?= $form->field($model, 'nation_code')->textInput()
                                ->label(null, ['class' => 'col-form-label']) ?>
                        </div>


                        <div class="col-md-3">
                            <?= $form->field($model, 'birthday')->widget(\system\widgets\datePicker\DatePickerWidget::className())
                                ->label(null, ['class' => 'col-form-label']) ?>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
