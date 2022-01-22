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
<style>
    table .glyphicon.glyphicon-flash.on {
        background: darkgreen;
    }

    table .glyphicon.glyphicon-flash.off {
        background: darkred;
    }

    table .glyphicon.glyphicon-eur {
        background: black;
    }

</style>
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
                            'columns' =>
                                [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    [
                                        'attribute' => 'username',
                                        'value' => function ($model) {
                                            /**
                                             * @var $model User
                                             *
                                             */
                                            return '<span style="direction:ltr !important;display: block;
margin: auto;
text-align: center;">' . $model->username . '</span>';
                                        },
                                        'format' => 'raw',
                                        'filter' => \yii\widgets\MaskedInput::widget(
                                            [
                                                'model' => $searchModel,
                                                'attribute' => 'username',
                                                'mask' => '(9999) 999 9999',
                                                'options' =>
                                                    [
                                                        'class' => 'form-control h-auto text-white bg-white-o-5 rounded-pill border-0 py-4 px-8',
                                                        'placeholder' => 'شماره همراه',
                                                        'style' => 'direction:ltr !important;text-align:center;font-size:20px'
                                                    ]
                                            ]
                                        )
                                    ],
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
//                                'credit',
                                    //'nation_code',
                                    //'bank_card',
                                    [
                                        'attribute' => 'status',
                                        'value' => function ($model) {
                                            /**
                                             * @var $model \Yiiman\ModuleUser\module\models\User
                                             */
                                            switch ($model->status) {
                                                case 10:
                                                    return 'فعال';
                                                    break;
                                                case 0:
                                                    return 'بازبینی';
                                                    break;
                                            }
                                        },
                                        'filter' => Html::activeDropDownList($searchModel, 'status',
                                            [
                                                '' => 'همه',
                                                \Yiiman\ModuleUser\module\models\User::STATUS_ACTIVE => 'فعال',
                                                \Yiiman\ModuleUser\module\models\User::STATUS_IN_ACTIVE => 'غیر فعال',
                                                \Yiiman\ModuleUser\module\models\User::STATUS_WAIT_VERIFY => 'وریفای نشده'
                                            ],
                                            [
                                                'class' => 'form-control'
                                            ]
                                        )
                                    ],
                                    [
                                        'class' => 'system\lib\ActionColumn',
                                        'template' => '{view} {update} {delete} {closeuser} {charge}',
                                        'buttons' =>
                                            [
                                                'closeuser' => function ($url, $model, $key) {
                                                    if ($model->hasMode(\Yiiman\ModuleUser\module\models\UserMode::MODE_CLOSED)) {
                                                        $url = Yii::$app->urlManager->createUrl(['/user/default/openuser?id=' . $model->id]);
                                                        return Html::a('<i class="glyphicon glyphicon-flash on"></i>', $url, ['title' => 'رفع مسدودی کاربر']);
                                                    } else {
                                                        $url = Yii::$app->urlManager->createUrl(['/user/default/closeuser?id=' . $model->id]);
                                                        return Html::a('<i class="glyphicon glyphicon-flash off"></i>', $url, ['title' => 'مسدود سازی کاربر']);
                                                    }
                                                },
                                                'charge' => function ($url, $model, $key) {
                                                    $url = Yii::$app->urlManager->createUrl(['/user/default/charge?id=' . $model->id]);
                                                    return Html::a('<i class="glyphicon glyphicon-eur"></i>', $url, ['title' => 'شارژ کردن حساب کاربر']);
                                                },

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
