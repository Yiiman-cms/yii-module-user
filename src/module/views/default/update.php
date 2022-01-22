<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Yiiman\ModuleUser\module\models\User */

$this->title = Yii::t('user', 'ویرایش اطلاعات کاربر: ' . $model->fullname, [
    'nameAttribute' => '' . $model->fullname,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'کاربران سایت'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('user', 'ویرایش');

\system\widgets\topMenu\TopMenuWidget::addBtb(
    'assignment',
    Yii::t('factor', 'بازبینی کاربر'),
    'info' ,
    null ,
    Yii::$app->Options->BackendUrl . '/user/default/view?id='.$model->id
);
?>
<div class="user-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
