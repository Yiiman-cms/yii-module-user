<?php

use system\modules\filemanager\widget\MediaViewWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \Yiiman\ModuleUser\module\models\User */

$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'کاربران سایت'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\system\widgets\topMenu\TopMenuWidget::addBtb(
    'delete',
    Yii::t('form', 'حذف این مورد'),
    'danger',
    null,
    Yii::$app->Options->BackendUrl . '/factor/default/delete?id=' . $model->id);


$form = \yii\bootstrap\ActiveForm::begin(
    [
        'enableAjaxValidation' => true,
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'validateOnType' => false,
        'enableClientValidation' => true,
        'options' => ['enctype' => 'multipart/form-data']
    ]
);
?>

<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12">
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
                                        'options' => ['dir' => 'rtl', 'disabled' => 'disabled'],
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:20px">
            <div class="col-md-12">
                <div class="card card-nav-tabs">
                    <div class="card-body ">
                        <h4 class="text-center">تصویر(در صورت وجود)</h4>
                        <div class="row">
                            <div class="col-md-12 pull-right">
                                <?= MediaViewWidget::widget(['model' => $model, 'attribute' => 'image']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-nav-tabs">
                    <div class="card-body ">
                        <div class="col-md-12">
                            <h4 class="text-center">مشخصات</h4>
                            <div class="col-md-4">
                                <?= $form->field($model, 'username')->textInput(['class' => 'form-control form-control-lg form-control-solid', 'style' => 'direction:ltr !important', 'disabled' => 'disabled'])->label(null, ['class' => 'col-form-label']) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'name')->textInput(['class' => 'form-control form-control-lg form-control-solid', 'disabled' => 'disabled'])->label(null, ['class' => 'col-form-label']) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'family')->textInput(['class' => 'form-control form-control-lg form-control-solid', 'disabled' => 'disabled'])->label(null, ['class' => 'col-form-label']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'user_province_id')->dropDownList(
                                    \yii\helpers\ArrayHelper::map(\system\modules\location\models\LocationCity::find()->all(), 'id', 'name'),
                                    ['id' => 'province-id', 'class' => 'form-control form-control-lg form-control-solid select2', 'disabled' => 'disabled']
                                )->label(null, ['class' => 'col-form-label']) ?>
                            </div>

                            <div class="col-md-3">
                                <?= $form->field($model, 'nation_code')->textInput(['class' => 'form-control form-control-lg form-control-solid', 'disabled' => 'disabled'])->label(null, ['class' => 'col-form-label']) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'birthday')->textInput(['disabled' => 'disabled'])
                                    ->label(null, ['class' => 'col-form-label']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-nav-tabs">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-md-12 pull-right">
                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                        <th>مدهای کاربری</th>
                                        <td><?= $model->modeListHtml() ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-nav-tabs">
                    <div class="card-body ">
                        <h3 class="text-center">اطلاعات کاربر</h3>
                        <div class="row">
                            <div class="col-md-6 pull-right">
                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                        <th>کد نمایندگی</th>
                                        <td><?= $model->agentCode ?></td>
                                    </tr>
                                    <tr>
                                        <th>نام کاربری</th>
                                        <td><?= $model->username ?></td>
                                    </tr>
                                    <tr>
                                        <th>آدرس</th>
                                        <td><?= $model->address ?></td>
                                    </tr>
                                    <tr>
                                        <th>کدپستی</th>
                                        <td><?= $model->postalCode ?></td>
                                    </tr>
                                    <tr>
                                        <th>کد دعوت</th>
                                        <td><?= $model->referral ?></td>
                                    </tr>
                                    <tr>
                                        <th>کاربر معرف</th>
                                        <td><?= !empty($model->invited_by)&& !empty($model->invitedby0) ? ('<a target="_blank" href="' . Yii::$app->urlManager->createUrl(['/user/default/view?id=' . $model->invited_by]) . '">' . (!empty($model->invitedby0->username)?$model->invitedby0->username:'کاربر حذف شده') . '</a>') : ' - ' ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6 pull-right">
                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                        <th>نام حقوقی</th>
                                        <td><?= $model->legalName ?></td>
                                    </tr>
                                    <tr>
                                        <th>شناسه ی ملی</th>
                                        <td><?= $model->legalCode ?></td>
                                    </tr>
                                    <tr>
                                        <th>تاریخ انعقاد قرارداد</th>
                                        <td><?= $model->start_date ?></td>
                                    </tr>
                                    <tr>
                                        <th>تاریخ تاریخ پایان قرارداد</th>
                                        <td><?= $model->end_date ?></td>
                                    </tr>
                                    <tr>
                                        <th>نام بانک</th>
                                        <td><?= $model->bank_name ?></td>
                                    </tr>
                                    <tr>
                                        <th>شماره حساب</th>
                                        <td><?= $model->account_number ?></td>
                                    </tr>
                                    <tr>
                                        <th>شماره شبا</th>
                                        <td><?= $model->sheba_number ?></td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
\yii\bootstrap\ActiveForm::end()
?>
