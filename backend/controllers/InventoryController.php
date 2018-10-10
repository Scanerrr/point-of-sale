<?php

namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii2mod\editable\EditableAction;
use common\models\{Inventory, Location};
use common\models\search\InventorySearch;

/**
 * InventoryController implements the CRUD actions for Inventory model.
 */
class InventoryController extends AccessController
{

    public function actions()
    {
        return [
            'change-quantity' => [
                'class' => EditableAction::class,
                'modelClass' => Inventory::class,
            ],
        ];
    }

    /**
     * Lists all Inventory models.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex(int $id)
    {
        if (($iSearch = Yii::$app->request->get('InventorySearch')) && isset($iSearch['location_id'])) {
            return $this->redirect(['index', 'id' => $iSearch['location_id']]);
        }

        $location = Location::find()->where(['id' => $id])->one();

        if (!$location) throw new NotFoundHttpException();

        $searchModel = new InventorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'location' => $location,
        ]);
    }

    /**
     * Creates a new Inventory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Inventory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->location_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Inventory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->redirect(['index', 'id' => $model->location_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Inventory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\StaleObjectException
     * @throws \Throwable
     */
    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index', 'id' => $model->location_id]);
    }

    /**
     * Finds the Inventory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inventory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = Inventory::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
