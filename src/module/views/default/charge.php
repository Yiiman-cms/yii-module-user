<?php
/**
 * @var $this \yii\web\View
 * @var $user \Yiiman\ModuleUser\module\models\User
 */
$this->title = 'شارژ حساب کاربر :' . $user->username;


$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'کاربران سایت'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\system\widgets\topMenu\TopMenuWidget::addBtb(
    'assignment',
    Yii::t('factor', 'بازبینی کاربر'),
    'info',
    null,
    Yii::$app->Options->BackendUrl . '/user/default/view?id=' . $user->id
);


$oldCharge = $user->correctCredit();
$js = <<<JS
function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
    $('#text').keyup(function(e){
        var price=parseInt($(this).val());
        var oldCharge= $oldCharge;
        var newPrice=price+oldCharge;
        if (isNaN(newPrice)){
            newPrice=0;
        }
        $('#afterCharge').text(addCommas(newPrice));
    });
JS;
$this->registerJs($js, $this::POS_END);
$form = \yii\bootstrap\ActiveForm::begin(['method' => 'post']);
?>
<style>
    .ct {
        margin-top: 31px
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card card-nav-tabs">
            <div class="card-body ">
                <h4 class="text-center">شارژ حساب کاربری</h4>
                <div class="row">
                    <div class="col-md-12 pull-right">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success">
                                    <p>
                                        توجه داشته باشید، مقدار شارژی که وارد میکنید به تومان میباشد.
                                    </p>
                                    <p>
                                        چنانچه عدد منفی وارد کنید، از میزان شارژ حساب کاربر کاسته میشود.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label id="current" class="text-info ct">شارژ فعلی
                                        کاربر: <?= number_format($user->credit) ?> تومان</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="text">میزان شارژ را وارد فرمایید(تومان)</label>
                                    <input name="price" id="text" class="form-control" value="0">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label id="sumcharge" class="text-success ct">میزان اعتبار کاربر پس از
                                        شارژ: <span id="afterCharge"><?= number_format($user->credit) ?></span>
                                        تومان</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="titleMessage">دلیل شارژ کاربر را وارد کنید</label>
                                    <input type="text" class="form-control" id="titleMessage" name="title"
                                           value="شارژ توسط ادمین">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button name="action" type="submit" value="close" class="btn btn-success">
                                    شارژ حساب کاربری
                                </button>
                                <button name="action" type="submit" value="return" class="btn btn-default">
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

