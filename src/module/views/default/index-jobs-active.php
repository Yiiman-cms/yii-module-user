<?php

use system\modules\filemanager\widget\MediaViewWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel Yiiman\ModuleUser\module\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('user', 'لیست کاربرانی که درخواست تایید شغل دارند') . ' ';
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'کاربران سایت'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <p>

    </p>
    <div class="card card-nav-tabs">
        <div class="card-body ">
            <h3 class="text-center"><?= Html::encode($this->title) ?></h3>

            <div class="row">
                <div class="col-md-12 pull-right">

                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



                    <?= GridView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                'username',
                                'name',
                                'family',
                                [
                                    'attribute' => 'status_job',
                                    'value' => function ($model) {
                                        switch ($model->status_job) {
                                            case \Yiiman\ModuleUser\module\models\User::STATUS_JOB_SEND_ATTACHED:
                                                return 'مدارک ارسال شده است';
                                                break;
                                            case \Yiiman\ModuleUser\module\models\User::STATUS_JOB_ACTIVE:
                                                return 'فعال';
                                                break;
                                            case \Yiiman\ModuleUser\module\models\User::STATUS_JOB_UNACTIVE:
                                                return 'غیرفعال';
                                                break;
                                        }
                                    },
                                ],
                                [
                                    'attribute' => 'attached',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return Html::img(!empty($model->attached) ? Yii::$app->urlManager->createAbsoluteUrl(['../upload/users/attached/' . $model->attached]) : Yii::$app->Options->UploadUrl . '/users/default.png', ['style' => 'height: 110px;']);

                                    }
                                ],
                                ['class' => 'yii\grid\ActionColumn',
                                    'template' => '{view} {active} {deactive}',
                                    'buttons' => [
                                        'active' => function ($url, $model, $key) {     // render your custom button
                                            return Html::a('<i class="material-icons">done</i>', ['default/jobs-active?id='.$model->id]);
                                        },
                                        'deactive' => function ($url, $model, $key) {     // render your custom button
                                            return Html::a('<i class="material-icons">close</i>', ['default/jobs-deactive?id='.$model->id]);
                                        }
                                    ]
                                ],
                            ],
                        ]
                    ); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>


        </div>


    </div>
</div>
