<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Yiiman\ModuleUser\module\models\User */

$this->title = Yii::t('user', 'ویرایش مدیریت کاربران سایت: ' . $model->name, [
    'nameAttribute' => '' . $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'کاربران سایتs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('user', 'ویرایش');
?>
<div class="user-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'jobs' => $jobs,

    ]) ?>

</div>
