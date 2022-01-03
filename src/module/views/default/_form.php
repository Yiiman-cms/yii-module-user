<?php

use system\modules\filemanager\widget\FileSelectorWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model Yiiman\ModuleUser\module\models\User */
/* @var $form yii\widgets\ActiveForm */
$avatar_path = Yii::$app->urlManager->createAbsoluteUrl(['../upload/users/avatar/' . $model->image]);


?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-md-3">
            <div class="row">
                <div class="card card-nav-tabs">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="<?= (!empty($model->image) ? $avatar_path : Yii::$app->Options->UploadUrl . '/users/default.png') ?>">
                                <img class="img" width="150"
                                     src="<?= (!empty($model->image) ? $avatar_path : Yii::$app->Options->UploadUrl . '/users/default.png') ?>"/>
                                </a>
                                <?= $form->field($model, 'upload')->fileInput(
                                    [

                                        'class' => 'btn btn-danger',
                                        'onchange' => 'preview(this,\'image\');',
                                    ]
                                )->label('آپلود تصویر') ?>
                            </div>
                        </div>
                        <div class="row">
                            <h4 class="text-center">وضعیت انتشار مدیریت کاربران سایت</h4>

                            <div class="col-md-12 pull-right">
                                <?= $form->field($model, 'status')->widget(
                                    \kartik\select2\Select2::className(),
                                    [
                                        'data' =>
                                            [
                                                1 => 'منتشر شده',
                                                0 => 'در حال بازبینی',

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
        </div>
        <div class="col-md-9">
            <div class="card card-nav-tabs">
                <div class="card-body ">
                    <div class="col-md-12">
                        <h4 class="text-center">مشخصات</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'family') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'name') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'username') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'birthday')->widget(
                                    \system\widgets\materialTimePicker\TimePickerWidget::className()
                                ) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'credit') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'nation_code')->widget(
                                    \yii\widgets\MaskedInput::className(),
                                    [
                                        'mask' => '999-999-999-9',
                                        'options' =>
                                            [
                                                'style' => 'direction:ltr',
                                                'class' => 'form-control'
                                            ]
                                    ]
                                ) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'bank_card')->widget(
                                    \yii\widgets\MaskedInput::className(),
                                    [
                                        'mask' => '9999-9999-9999-9999',
                                        'options' =>
                                            [
                                                'style' => 'direction:ltr',
                                                'class' => 'form-control'
                                            ]
                                    ]
                                ) ?>
                            </div>

                        </div>
                    </div>
                    <?php
                    if (class_exists(\system\modules\jobs\Module::className())) {

                        ?>
                        <div class="col-md-6">
                            <?= $form->field($model, 'jobs')->widget(
                                Select2::className(),
                                [
                                    'data' => $jobs,
                                    'options' =>
                                        [
                                            'style' => 'direction:ltr',
                                            'class' => 'form-control',
                                            'placeholder' => 'انتخاب کنید ...',
                                            'dir' => 'rtl'
                                        ]
                                ]
                            )->label('شغل') ?>
                        </div>
                        <?php
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
