<?php

namespace backend\controllers;

use backend\models\EmployeeForm;
use Yii;
use common\models\User;
use common\models\search\UserSearch;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii2mod\editable\EditableAction;

/**
 * EmployeeController implements the CRUD actions for User model.
 */
class EmployeeController extends AccessController
{

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs']['actions']['copy'] = ['post'];
        return $behaviors;
    }

    public function actions()
    {
        return [
            'change-status' => [
                'class' => EditableAction::class,
                'modelClass' => User::class,
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDetails(int $id)
    {
        $user = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $user->salary_settings = User::validateSalaryInfo(Yii::$app->request->post());
            if (!$user->save()) {
                Yii::$app->session->setFlash('error', 'An error occurred while saving settings');
            }
            return $this->redirect(['details', 'id' => $user->id]);
        }

        return $this->render('details', [
            'user' => $user,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new EmployeeForm(['scenario' => 'create']);

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->create()) {

                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

                if ($model->imageFile) {

                    if (!$model->upload($user)) {
                        Yii::$app->session->setFlash('error', 'An error occurred while uploading file');
                    }

                }
                return $this->redirect(['details', 'id' => $user->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionUpdate(int $id)
    {
        $user = $this->findModel($id);
        if (!$user) throw new NotFoundHttpException();

        $model = new EmployeeForm([], $user);

        if ($model->load(Yii::$app->request->post()) && $model->update($user)) {

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->imageFile) {

                if ($oldImg = $user->avatar) {
                    FileHelper::unlink(User::UPLOAD_PATH . $user->id . '/' . $oldImg);
                }

                if (!$model->upload($user)) {
                    Yii::$app->session->setFlash('error', 'An error occurred while uploading file');
                }

            }

            return $this->redirect(['details', 'id' => $user->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCopy()
    {
        $user = $this->findModel(Yii::$app->request->post('id'));
        return $user->salary_settings;
    }
}
