<?php

namespace backend\controllers;

use Yii;
use common\models\Product;
use common\models\search\ProductSearch;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii2mod\editable\EditableAction;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends AccessController
{
    public function actions()
    {
        return [
            'change-status' => [
                'class' => EditableAction::class,
                'modelClass' => Product::class,
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->imageFile = UploadedFile::getInstance($model, 'imageFile')) {

                if ($model->upload()) {
                    $model->image = $model->imageFile->name;
                    $model->save(false);
                } else {
                    Yii::$app->session->setFlash('error', 'An error occurred while uploading file');
                }

            }

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\base\Exception
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if ($model->imageFile = UploadedFile::getInstance($model, 'imageFile')) {

                if ($model->upload()) {
                    $model->image = $model->imageFile->name;
                    $model->save(false);
                } else {
                    Yii::$app->session->setFlash('error', 'An error occurred while uploading file');
                }

            }

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
