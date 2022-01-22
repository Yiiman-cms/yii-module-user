<?php
/**
 * @var $this \yii\web\View
 * @var $user User
 */
$this->title='مسدود سازی کاربر :'.$user->username;


$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'کاربران سایت'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\system\widgets\topMenu\TopMenuWidget::addBtb(
    'delete',
    Yii::t('form', 'حذف این مورد'),
    'danger',
    null,
    Yii::$app->Options->BackendUrl . '/factor/default/delete?id=' . $user->id);
\system\widgets\topMenu\TopMenuWidget::addBtb(
    'assignment',
    Yii::t('factor', 'بازبینی کاربر'),
    'info' ,
    null ,
    Yii::$app->Options->BackendUrl . '/user/default/view?id='.$user->id
);

$form=\yii\bootstrap\ActiveForm::begin(['method' => 'post']);
?>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-nav-tabs">
                <div class="card-body ">
                    <h4 class="text-center">مسدود سازی کاربر</h4>
                    <div class="row">
                        <div class="col-md-12 pull-right">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger">
                                        <p>
                                            شما در حال مسدود سازی کاربر به شماره ی <?= $user->username ?> و نام <?= $user->fullname ?> هستید.
                                        </p>
                                        <p>
                                            لطفا دلیل مسدود سازی را مختصر وارد نمایید، تا برای کاربر پیامک شود.
                                        </p>


                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="text">دلیل مسدود سازی</label>
                                        <textarea name="message" id="text" class="form-control" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button name="action" type="submit" value="close" class="btn btn-danger">
                                        مسدود سازی
                                    </button>
                                    <button name="action" type="submit" value="return" class="btn btn-success">
                                        لغو عملیات و بازگشت
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
\yii\bootstrap\ActiveForm::end();
?>

