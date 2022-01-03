<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model Yiiman\ModuleUser\module\models\User */

$this->title = Yii::t('user', 'ثبت کاربر جدید');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'کاربران سایت'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
        'jobs' => $jobs,

    ]) ?>

</div>
