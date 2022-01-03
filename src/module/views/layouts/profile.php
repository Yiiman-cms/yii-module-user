<?php

use system\modules\menu\models\Menu;
use system\modules\menu\models\Pages;
use system\modules\tour\models\Tour;
use system\modules\tour\models\TourAirlines;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */
$asset = \frontend\assets\ProfileAssets::register($this);
$this->beginPage();
if (!empty(Yii::$app->user->identity->image)) {
    $avatar_path = Yii::$app->urlManager->createAbsoluteUrl(
        ['../upload/users/avatar/' . Yii::$app->user->identity->image]
    );

}
$menus = Menu::find()->where(['status' => Menu::MENU_ACTIVE])->andWhere(['position' => Menu::POSITION_TOP])->orWhere(
    ['position' => Menu::POSITION_BOTH])->all();

?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::$app->Options->siteTitle . ' / ' . $this->title ?></title>
    <?php $this->head() ?>
    <style>
        :root {
            /* Colors: */
            --blue: #47e9c4;
            --orange: #ffb23e;

            /* Images: */

            /* Sizes: */
            --inputs-height: 40px;
            --inputs-radius: 8px;
            --inputs-text-color: #5b5b5b;

            /* zIndexes and translateZ: */
        }
    </style>
</head>
<body>
<nav id="navbar">
    <div class="container contents">
        <div class="logo">
            <a href="/">
                <img class="logo_href" src="<?= Yii::$app->Options->logo ?>" alt="logo">
            </a>
        </div>
        <ul class="links">
            <li class="list-pages">
                <a href="<?= Yii::$app->urlManager->createUrl(['/installment']) ?>">
                    <div class="link-icon"
                         style="background-image: url(<?= $asset->baseUrl . "/images/chamedan.png" ?>)"></div>
                    تور اقساطی
                </a>
            </li>
            <li class="list-pages">
                <a href="#">
                    <div class="link-icon"
                         style="background-image: url(<?= $asset->baseUrl . "/images/havapeyma.png" ?>)"></div>
                    هواپیما
                </a>
            </li>
            <li class="list-pages">
                <a href="#">
                    <div class="link-icon"
                         style="background-image: url(<?= $asset->baseUrl . "/images/train.png" ?>)"></div>
                    قطار
                </a>
            </li>
            <li class="list-pages">
                <a href="#">
                    <div class="link-icon"
                         style="background-image: url(<?= $asset->baseUrl . "/images/hotel.png" ?>)"></div>
                    هتل
                </a>
            </li>
            <?php
            if (!empty($menus)) {
                ?>
                <li class="list1">
                    <div class="dropdown" style="position: relative;left: -20px;">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            برگه ها
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2" style="align-items: center;">
                            <?php
                            foreach ($menus as $item) {
                                $pages_link = Pages::find()->where(['id' => $item->page_id])->one();
                                ?>
                                <div class="d-flex">
                                    <img class="icon-menu"
                                         src="<?= Yii::$app->Options->UploadUrl . '/menus/' . $item->image ?>"
                                         alt="*">
                                    <a class="dropdown-item" href="<?php
                                    if ($item->type_id == Menu::LINK_ETERNAL){
                                    echo '/page/' . $pages_link->slug ?>">
                                        <?= $item->title;
                                        }
                                        else {
                                            $item->url ?>">
                                            <?= $item->title;
                                        }
                                        ?></a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
        <div class="line"></div>
        <?php
        if (Yii::$app->user->isGuest) {
            ?>
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">ورود / عضویت
            </button>
            <?php
        } else {
            ?>

            <div class="dropdown" id="sunfav">
                <div data-toggle="dropdown">
                    <?= !empty(Yii::$app->user->identity->image) ? '<img width="50" class="user-image" src="' . $avatar_path . '" style="position: absolute;margin-right: -65px;margin-top: -13px;">' : '<i class="far fa-user-circle"></i>' ?>
                    <h4 class="user"><?= Yii::$app->user->identity->name ?></h4>
                    <button type="button" class="user"
                    ><?= Yii::$app->user->identity->username ?></button>
                </div>
                <div class="dropdown-menu drop1">
                    <a class="dropdown-item down" href="<?= Yii::$app->urlManager->createAbsoluteUrl(
                        ['users/profile?id=' . Yii::$app->user->identity->id]
                    ) ?>">ویرایش پروفایل</a>
                    <a class="dropdown-item down" href="#">ذخیره ها</a>
                    <a class="dropdown-item down" href="<?= Yii::$app->urlManager->createAbsoluteUrl(
                        ['users/factor?id=' . Yii::$app->user->identity->id]
                    ) ?>">سوابق خرید</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item down"
                       href="<?= Yii::$app->urlManager->createUrl(['logout']) ?>">خروج</a>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="container">
            <!-- Trigger the modal with a button -->

            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">ورود حساب کاربری</h4>
                        </div>
                        <div class="modal-body">
                            <form action="<?= Yii::$app->urlManager->createUrl(['login']) ?>" method="post"
                                  style="margin: 20px 0;">
                                <div class="col-md-12 email">
                                    <input type="text" class="form-control"
                                           placeholder="شماره همراه خود را وارد کنید"
                                           name="LoginForm[username]"/>
                                    <p style="font-size: 10px; margin-top: 8px;">مثال : 091212345678</p>
                                </div>
                                <div class="col-md-12 email" style="margin-top: -6px;">
                                    <input type="password" class="form-control"
                                           placeholder="رمز عبور خود را وارد کنید"
                                           name="LoginForm[password]"/>
                                </div>
                                <button type="submit" class="btn btn-success col-md-12 vorod">وارد شوید</button>
                            </form>
                            <a class="recovery" href="reset.html"><i class="fas fa-fingerprint finger"></i>
                                بازیابی رمز عبور</a>
                            <div class="dropdown-divider"></div>
                            <p class="acc">حساب کاربری ندارید؟</p>
                            <a href="<?= Yii::$app->urlManager->createUrl(['register']) ?>">
                                <button type="button" class="btn btn-warning col-md-12 vorod">ثبت نام کنید</button>
                            </a>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</nav>
<!--این navbar اطلاعات navbar اصلی را در خودش میریزد و در سایز کوچک نمایش داده میشود، نیازی نیست این navbar را تغیر دهید.-->
<nav id="navbar-sm">
    <div class="container contents">
        <div class="head">
            <div class="logo"></div>
            <button class="button-dropdown fa fa-bars"></button>
        </div>
        <div class="dropdown">
            <ul class="links"></ul>
            <div class="user"></div>
        </div>
    </div>
</nav>
<div id="scroll-button" class="fa fa-angle-up btn-hover-d"></div>

<div id="profile">
    <div id="top-bg" style="background-image: url(../assets/images/France01.jpg)">
        <h1 class="title">
            با دهکده پرواز به صرفه سفر کنید!
        </h1>
    </div>
    <div class="main-container container">
        <div class="left">
            <div class="user-image" style="background-image: url(../assets/images/person.png)"></div>
            <h3 class="user-name">مهدی حامدی</h3>
            <p class="user-email">
                example@gmail.com
            </p>
            <ul class="profile-links">
                <li class="active">
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['users/profile']) ?>">
                        ویرایش پروفایل
                    </a>
                </li>
                <li >
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['users/credit']) ?>">
                        اعتبار من
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['users/score']) ?>">
                        امتیاز من
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['users/password']) ?>">
                        تغییر رمز عبور
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['users/bookmark']) ?>">
                        لیست ذخیره ها
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['users/factor']) ?>">
                        سوابق خرید
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['users/request-visa']) ?>">
                        درخواست ویزا
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['users/my-check']) ?>">
                        چک های من
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['users/invite-friend']) ?>">
                        دعوت دوستان
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['users/list-passenger']) ?>">
                        لیست مسافران
                    </a>
                </li>
            </ul>
        </div>
        <div class="right">
            <?= $content ?>
        </div>
    </div>
</div>


    <?= $this->beginBody() ?>
    <?php
    $js = $this->render('@frontend/assets/files/js/custom.js');
    $this->registerJs($js, $this::POS_END);
    ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
