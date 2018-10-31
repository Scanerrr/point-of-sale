<?php

namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\{InventoryReport, Location};
use common\models\search\InventoryReportSearch;

/**
 * InventoryReportController implements the CRUD actions for InventoryReport model.
 */
class InventoryReportController extends AccessController
{
    /**
     * Lists all InventoryReport models.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex(int $id)
    {
        if (($iSearch = Yii::$app->request->get('InventoryReportSearch')) && isset($iSearch['location_id'])) {
            return $this->redirect(['index', 'id' => $iSearch['location_id']]);
        }

        $location = $this->findLocationModel($id);

        $searchModel = new InventoryReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'location' => $location
        ]);
    }

    /**
     * Creates a new InventoryReport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InventoryReport();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->location_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing InventoryReport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $model = $this->findInventoryReportModel($id);
        $model->delete();

        return $this->redirect(['index', 'id' => $model->location_id]);
    }

    /**
     * Finds the InventoryReport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InventoryReport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findInventoryReportModel(int $id)
    {
        if (($model = InventoryReport::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findLocationModel(int $id)
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Location does not exist.');
    }
}
