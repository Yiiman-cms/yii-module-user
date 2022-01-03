<?php

namespace Yiiman\ModuleUser\module\controllers;

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
class DefaultController extends YiiMan\YiiBasics\lib\Controller
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
        $searchModel = new $this->model();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    public function actionJobsActive($id)
    {
        $model = $this->findModel($id);
        $model->status_job = User::STATUS_JOB_ACTIVE;
        if($model->save()){
            Yii::$app->session->setFlash('success','شغل کاربر با نام '.$model->name.' تایید شد');
        }
        return $this->redirect(
            'index-jobs-active'
        );
    }
    public function actionJobsDeactive($id)
    {
        $model = $this->findModel($id);
        $model->status_job = User::STATUS_JOB_UNACTIVE;
        if($model->save()){
            Yii::$app->session->setFlash('warning','شغل کاربر با نام '.$model->name.' رد شد');
        }
        return $this->redirect(
            'index-jobs-active'
        );
    }

    public function actionIndexJobsActive()
    {
        $searchModel = new $this->model();
        $dataProvider = $searchModel->searchJobStatus(Yii::$app->request->queryParams);

        return $this->render(
            'index-jobs-active',
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
                'model' => $this->findModel($id),
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
        $jobs = ArrayHelper::map(
            Jobs::find()->where(['status' => Jobs::STATUS_ACTIVE])->all(),
            'id',
            'job_title'
        );
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $model->jobs = $post["SearchUser"]["jobs"];
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
        $model = $this->findModel($id);
        $jobs = ArrayHelper::map(
            Jobs::find()->where(['status' => Jobs::STATUS_ACTIVE])->all(),
            'id',
            'job_title'
        );

        if ($model->load(Yii::$app->request->post())) {

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
            'update',
            [
                'model' => $model,
                'jobs' => $jobs,

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
            Yii::$app->sms->Send($model->username, $messages);
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
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
