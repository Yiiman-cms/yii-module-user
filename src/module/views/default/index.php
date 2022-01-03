<?php

use system\modules\filemanager\widget\MediaViewWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel Yiiman\ModuleUser\module\models\SearchUser */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('user', 'مدیریت کاربران سایت') . ' ';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-index">
    <p>
        <?= Html::a(Yii::t('user', 'ثبت کاربر جدید'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('user', 'درخواست تایید شغل'), ['default/index-jobs-active'], ['class' => 'btn btn-info']) ?>
    </p>
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
//								'mobile' ,
//								'auth_key' ,
//								'password_hash' ,
                                //'password_reset_token',
                                //'created_at',
                                //'updated_at',
                                //'fullname',

                                //'verification',
                                'name',
                                'family',
                                //'birthday',
                                //'created_by',
                                //'updated_by',
                                //'deleted_by',
                                //'restored_by',
                                'credit',
                                //'nation_code',
                                //'bank_card',
                                [
                                    'attribute' => 'status',
                                    'value' => function ($model) {
                                        /**
                                         * @var $model \common\models\Neighbourhoods
                                         */
                                        switch ($model->status) {
                                            case 10:
                                                return 'انتشار یافته';
                                                break;
                                            case 0:
                                                return 'بازبینی';
                                                break;
                                        }
                                    },
                                ],
                                ['class' => 'system\lib\ActionColumn'],
                            ],
                        ]
                    ); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>


        </div>


    </div>
</div>
