<?php

namespace Yiiman\ModuleUser\module\controllers;

use frontend\models\ResetPasswordForm;
use system\modules\sms\base\Sms;
use system\modules\transactions\models\Transactions;
use system\modules\transactions\models\TransactionsFactor;
use system\modules\transactions\models\TransactionsFactorItems;
use system\modules\transactions\Terminals\CreditTerminal;
use Yiiman\ModuleUser\module\models\UserMode;
use Upload\Exception;
use Yii;
use Yiiman\ModuleUser\module\models\User;
use Yiiman\ModuleUser\module\models\SearchUser;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * DefaultController implements the CRUD actions for User model.
 */
class DefaultController extends \system\lib\Controller
{
    /**
     *
     * @var $model SearchUser
     */
    public $model;


    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchUser();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Displays a single User model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render(
            'view',
            [
                'model' => \Yiiman\ModuleUser\module\models\User::findOne($id),
            ]
        );
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /**
         *
         * @var $model User
         */
        $model = new $this->model();

        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $model->setPassword(123456);
            $model->generateAuthKey();
            if ($model->save()) {
                $avatar = UploadedFile::getInstance($model, 'upload');
                if ($avatar) {
                    $path = realpath(__DIR__ . '/../../../../../public_html/upload/users/avatar/');
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $fileName = uniqid(time(), true) . '.' . $avatar->extension;
                    $avatar->saveAs($path . '/' . $fileName);
                    $model->image = $fileName;
                    $model->save();
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render(
            'create',
            [
                'model' => $model,
                'jobs' => $jobs,
            ]
        );
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = \Yiiman\ModuleUser\module\models\User::findOne($id);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {

                if (!empty($_POST['resetPass'])) {
                    $model->setPassword($_POST['resetPass']);
                    $model->save();
                    $text = Yii::$app->Options->SMSResetPassText;
                    $text = str_replace(
                        [
                            '{{first_name}}',
                            '{{last_name}}',
                            '{{site_name}}',
                            '{{new_pass}}',
                        ],
                        [
                            $model->name,
                            $model->family,
                            Yii::$app->Options->siteTitle,
                            $_POST['resetPass']
                        ],
                        $text
                    );
                    try {

                        Sms::sendPattern('accountpassword', $model->username, $model->name, $_POST['resetPass']);
                    } catch (\Exception $e) {
                    }
                } else {
                    Yii::$app->Notification->send('userEditFromAdmin', $model, self::className(),
                        [
                            'tname' => $model->name,
                            'tfamily' => $model->family,
                            'date' => Yii::$app->functions->convertdatetime(date('Y-m-d H:i:s'))
                        ]
                    );
                }
                $model->saveAttachments('image');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render(
            'update',
            [
                'model' => $model,
            ]
        );
    }

    public function actionChangePassword($id)
    {
        $model = $this->findModel($id);
        $new_password = rand(11111, 99999);
        $model->setPassword($new_password);

        $messages = '';
        $messages .= 'رمز عبور جدید شما :';
        $messages .= $new_password;
        $messages .= "\n";
        $messages .= 'دهکده پرواز';

        if ($model->save()) {
            Yii::$app->Notification->send('userChargeFromAdmin', $model, $model::className(),
                [
                    'tname' => $model->name,
                    'tfamily' => $model->family,
                    'date' => Yii::$app->functions->convertdatetime(date('Y-m-d H:i:s'))
                ]
            );
            Sms::sendPattern('accountpassword', $model->username, $model->name, $new_password);
            Yii::$app->session->setFlash(
                'success',
                "رمز عبور برای $model->name به : $new_password تغییر یافت ."
            );
        }

        return $this->render(
            'view',
            [
                'model' => $this->findModel($id),
            ]
        );
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        try {

            $user = User::findOne($id);
            $model = clone $user;
            $user->delete();
            Yii::$app->Notification->send(
                'userDeleteFromAdmin',
                $model,
                self::className(),
                [
                    'tname' => $model->name,
                    'tfamily' => $model->family,
                    'date' => Yii::$app->functions->convertdatetime(date('Y-m-d H:i:s'))
                ]
            );

        } catch (\Exception $e) {
            Yii::$app->session->addFlash('warning', 'این کاربر در سامانه تراکنش انجام داده است و قابل حذف نیست');
        }


        return $this->redirect(['index']);
    }
//
//    /**
//     * Finds the User model based on its primary key value.
//     * If the model is not found, a 404 HTTP exception will be thrown.
//     *
//     * @param integer $id
//     *
//     * @return User the loaded model
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    protected function findModel($id)
//    {
//        if (($this->model = User::findOne($id)) !== null) {
//            return $this->model;
//        }
//
//        throw new NotFoundHttpException(Yii::t('user', 'The requested page does not exist.'));
//    }

    public function actionCloseuser($id)
    {
        $user = User::findOne($id);
        if (empty($user)) {
            throw new NotFoundHttpException('این کاربر پیدا نشد');
        }
        $post = Yii::$app->request->post();

        if (!empty($post) && !empty($post['action'])) {
            switch ($post['action']) {
                case 'return':
                    Yii::$app->session->addFlash('warning', 'مسدود سازی کاربر لغو شد');
                    return $this->redirect(['/user/default']);
                    break;
                case 'close':
                    $user->addMode(UserMode::MODE_CLOSED);
                    Yii::$app->session->addFlash('success', 'کاربر با موفقیت مسدود سازی شد');
                    if (!empty($post['message'])) {
                        try {
                            Sms::sendPattern('accountclosed', str_replace(['_', ' ', '-', '(', ')'], '', $user->username), $user->name);
                        } catch (\Exception $e) {
                            Yii::$app->session->addFlash('danger', 'پیامک ارسال نشد');
                        }
                        Yii::$app->Notification->send(
                            'userRejectFromAdmin',
                            $user,
                            self::className(),
                            [
                                'tname' => $user->name,
                                'tfamily' => $user->family,
                                'date' => Yii::$app->functions->convertdatetime(date('Y-m-d H:i:s'))
                            ]
                        );
                    }
                    return $this->redirect(['/user/default']);
                    break;
            }
        }


        return $this->render('close', ['user' => $user]);
    }

    public function actionOpenuser($id)
    {
        $user = User::findOne($id);
        if (empty($user)) {
            throw new NotFoundHttpException('این کاربر یافت نشد');
        }
        $user->deleteMode(UserMode::MODE_CLOSED);
        Yii::$app->session->addFlash('success', 'حساب کاربر با شماره همراه ' . $user->username . ' رفع مسدودی شد');
        try {
            Sms::sendPattern('accountopen', $user->getMobileNumber(), $user->name);

        } catch (\Exception $e) {
            Yii::$app->session->addFlash('danger', 'مشکلی در ارسال پیامک برای کاربر بوجود آمد');
        }
        Yii::$app->Notification->send(
            'userUnRejectFromAdmin',
            $user,
            self::className(),
            [
                'tname' => $user->name,
                'tfamily' => $user->family,
                'date' => Yii::$app->functions->convertdatetime(date('Y-m-d H:i:s'))
            ]
        );
        return $this->redirect(['/user/default']);
    }

    public function actionCharge($id)
    {
        $user = User::findOne($id);
        if (empty($user)) {
            throw new NotFoundHttpException('کاربر مورد نظر شما یافت نشد');
        }

        $post = Yii::$app->request->post();

        if (!empty($post['action'])) {
            if ($post['action'] == 'close') {
                if (!empty($post['price']) && !empty($post['title'])) {
                    $title = !empty($post['title']) ? $post['title'] : 'شارژ حساب کاربر توسط مدیر سایت';
                    $user->correctCredit();
                    $oldCredit = $user->credit;
                    $user->chargeUser($post['price'], $title);
                    Yii::$app->session->addFlash('success', 'حساب کاربر ' . $user->username . ' با موفقیت به مبلغ ' . number_format($post['price']) . ' تومان شارژ شد.');

                    Yii::$app->Notification->send(
                        'userChargeFromAdmin',
                        $user,
                        self::className(),
                        [
                            'credit' =>  (string)$user->credit,
                            'oldCredit' => (string)$oldCredit,
                            'chargePrice' => (string)number_format((float)$post['price'])
                        ]
                    );
                    return $this->redirect(['/user']);
                }
            }
            if ($post['action'] == 'return') {
                return $this->redirect(['/user']);
            }
        }


        return $this->render('charge', ['user' => $user]);
    }

    protected function upload()
    {


    }

    public function init()
    {
        parent::init();
        $this->model = new SearchUser();
    }
}
