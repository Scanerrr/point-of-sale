<?php

namespace backend\controllers;

use Yii;
use common\models\Location;
use yii\web\NotFoundHttpException;
use common\models\search\InventoryLogSearch;

/**
 * InventoryLogController implements the CRUD actions for InventoryLog model.
 */
class InventoryLogController extends AccessController
{
    /**
     * Lists all InventoryLog models.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex(int $id)
    {
        if (($iSearch = Yii::$app->request->get('InventoryLogSearch')) && isset($iSearch['location_id'])) {
            return $this->redirect(['index', 'id' => $iSearch['location_id']]);
        }

        $location = $this->findLocationModel($id);

        $searchModel = new InventoryLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'location' => $location
        ]);
    }

    protected function findLocationModel(int $id)
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Location does not exist.');
    }
}
