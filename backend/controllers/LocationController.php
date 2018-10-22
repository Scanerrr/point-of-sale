<?php

namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii2mod\editable\EditableAction;
use common\models\search\LocationSearch;
use common\models\{Location, LocationUser, Order};

/**
 * LocationController implements the CRUD actions for Location model.
 */
class LocationController extends AccessController
{

    public function actions()
    {
        return [
            'change-status' => [
                'class' => EditableAction::class,
                'modelClass' => Location::class,
            ],
        ];
    }

    /**
     * Lists all Location models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Location model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Location model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Location();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $employees = Yii::$app->request->post('employees');
            LocationUser::makeRelation($model->id, $employees);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Location model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $employees = Yii::$app->request->post('employees');
            LocationUser::deleteAll(['location_id' => $model->id]);
            LocationUser::makeRelation($model->id, $employees);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Location model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Location the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Location::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReport()
    {
        $dateFormat = 'Y-m-d';
        $startDate = date($dateFormat, strtotime('monday this week'));
        $endDate = date($dateFormat);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $locationId = $post['location'];

            $startDate = $post['from'];
            $endDate = $post['to'];

            $orders = Order::find()
                ->select(['id', 'total_tax', 'total', 'created_at'])
                ->complete()
                ->forLocation($locationId)
                ->forDateRange($startDate, $endDate)
                ->orderBy('created_at DESC')
                ->all();

            $d1 = new \DateTime($startDate);
            $d2 = new \DateTime($endDate);

            $interval = \DateInterval::createFromDateString('+1 day');

            $datePeriod = new \DatePeriod($d1, $interval, $d2);

            /*$total = $totalTax = 0;
            foreach ($orders as $order) {
                $total += $order->total;
                $totalTax += $order->total_tax;
            }
            */
        }

        return $this->render('report', [
            'orders' => $orders ?? null,
            'dateFormat' => $dateFormat,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'location' => $locationId ?? null,
            'datePeriod' => $datePeriod ?? null
        ]);
    }
}
