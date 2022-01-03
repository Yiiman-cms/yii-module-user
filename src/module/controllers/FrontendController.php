<?php

namespace Yiiman\ModuleUser\module\controllers;

use system\modules\transactions\models\Transactions;
use Yiiman\ModuleUser\module\models\ChangePassword;
use system\modules\bookmark\models\Bookmark;
use system\modules\credit\models\Credit;
use system\modules\factor\models\Factor;
use Yii;
use Yiiman\ModuleUser\module\models\User;
use Yiiman\ModuleUser\module\models\SearchUser;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yiiman\ModuleUser\lib\UploadedFile;

/**
 * DefaultController implements the CRUD actions for User model.
 */
class FrontendController extends \YiiMan\YiiBasics\lib\Controller
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
       if (Yii::$app->user->isGuest){
       	return $this->redirect( ['register']);
       }else{
       	return $this->render( 'profile');
       }
    }


    public function actionPassword()
    {
        $this->layout = 'profile';
        $model = new ChangePassword(Yii::$app->user->id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->compare(1)) {
                if ($model->compare(2)) {
                    $model->setNewPassword();
                    Yii::$app->session->setFlash('success', 'رمز عبور جدید با موفقیت ثبت شد.');
                    return $this->refresh();
                } else {
                    Yii::$app->session->setFlash('warning', 'رمز عبور جدید با تکرار آن برابر نمی باشد.');
                }
            } else {
                Yii::$app->session->setFlash('warning', 'رمز عبور فعلی اشتباه می باشد.');
            }
        }

        return $this->render('password', [
            'model' => $model,
        ]);
    }

    public function actionChangePassword($id)
    {
        $this->layout = 'profile';
        $model = $this->findModel($id);
        $new_password = rand(11111, 99999);
        $model->setPassword($new_password);

        $messages = '';
        $messages .= 'رمز عبور جدید شما :';
        $messages .= $new_password;
        $messages .= "\n";
        $messages .= 'دهکده پرواز';

        if ($model->save()) {
            Yii::$app->sms->Send($model->username, $messages);
            Yii::$app->session->setFlash('success', "رمز عبور برای $model->name به : $new_password تغییر یافت .");
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionRegister(){
    	return $this->render( 'register');
    }
    
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $jobs = ArrayHelper::map(Jobs::find()
            ->where(['status' => Jobs::STATUS_ACTIVE])
            ->all(), 'id', 'job_title');

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'jobs' => $jobs,

        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($this->model = User::findOne($id)) !== null) {
            return $this->model;
        }

        throw new NotFoundHttpException(Yii::t('user', 'The requested page does not exist.'));
    }


    protected function upload()
    {


    }


    public function init()
    {
        $this->model = new SearchUser();
    }
}
