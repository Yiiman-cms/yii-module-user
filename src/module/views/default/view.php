<?php

use system\modules\filemanager\widget\MediaViewWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Yiiman\ModuleUser\module\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'کاربران سایتs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$avatar_path = Yii::$app->urlManager->createAbsoluteUrl(['../upload/users/avatar/' . $model->image]);

?>
<div class="user-view">


    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a(Yii::t('user', 'بروزرسانی'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('user', 'تغییر رمز'), ['change-password', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('user', 'جذف'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('user', 'آیا برای حذف این مورد اطمینان دارید؟?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="card">
        <div class="row ">
            <div class="col-md-12">
                <a href="<?= (!empty($model->image) ? $avatar_path : Yii::$app->Options->UploadUrl . '/users/default.png') ?>">
                    <img class="img" style="margin-right: 35%;width: 20% !important;"
                         src="<?= (!empty($model->image) ? $avatar_path : Yii::$app->Options->UploadUrl . '/users/default.png') ?>"/>
                </a>
            </div>
        </div>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'family',
            'username',
//            'mobile',
//            'auth_key',
//            'password_hash',
//            'password_reset_token',

            [
                'attribute' => 'status',
                'value' => function ($model) {
                    /**
                     * @var $model \common\models\Neighbourhoods
                     */
                    switch ($model->status) {
                        case 1:
                            return 'فعال';
                            break;
                        case 0:
                            return 'غیرفعال';
                            break;
                    }
                },
            ],
            'verification',

            'birthday',
//            'created_by',
//            'updated_by',
//            'deleted_by',
//            'restored_by',
            'credit',
            'nation_code',
            'bank_card',
            [
                'attribute' => 'jobs',
                'value' => function ($model) {
                    return $model->getJobs($model->jobs);
                }
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
