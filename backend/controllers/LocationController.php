<?php

namespace backend\controllers;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;
use yii2mod\editable\EditableAction;
use common\models\search\LocationSearch;
use common\models\{Location, LocationUser, Order, PaymentMethod, Product, User};

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
                ->with([
                    'orderProducts',
                    'orderProducts.product' => function (ActiveQuery $query) {
                        return $query->select('name');
                    },
                    'orderPayments',
                    'orderPayments.method' => function (ActiveQuery $query) {
                        return $query->select(['id', 'type_id']);
                    },
                    'employee' => function (ActiveQuery $query) {
                        return $query->select(['id', 'name']);
                    },
                ])
                ->select(['id', 'total_tax', 'total', 'employee_id', 'created_at'])
                ->complete()
                ->forLocation($locationId)
                ->forDateRange($startDate, $endDate)
                ->orderBy('created_at ASC')
                ->all();

            $ordersByDays = $ordersPayments = $ordersProducts = $ordersEmployees = [];
            $total = $totalTax = 0;
            foreach ($orders as $order) {

                // statistic by days
                $createdAt = date($dateFormat, strtotime($order->created_at));
                $ordersByDays[$createdAt] = [
                    'total_tax' => isset($ordersByDays[$createdAt]['total_tax'])
                        ? $ordersByDays[$createdAt]['total_tax'] + $order->total_tax
                        : $order->total_tax,
                    'total' => isset($ordersByDays[$createdAt]['total'])
                        ? $ordersByDays[$createdAt]['total'] + $order->total
                        : $order->total,
                    'count' => isset($ordersByDays[$createdAt]['total'])
                        ? $ordersByDays[$createdAt]['count'] + 1
                        : 1
                ];

//                // payment statistic
//                foreach ($order->orderPayments as $orderPayment) {
//                    if ($orderPayment->method->isCash) {
//                        $ordersPayments[$orderPayment->method_id] = [
//                            'isCash' => $orderPayment->method->isCash,
//                            'total' => isset($orderPayment[$orderPayment->method_id]['total'])
//                                ? $orderPayment[$orderPayment->method_id]['total'] + $orderPayment->amount
//                                : $orderPayment->amount,
//                            'count' => isset($orderPayment[$orderPayment->method_id]['count'])
//                                ? $orderPayment[$orderPayment->method_id]['count'] + 1
//                                : 1,
//                    }
//                    $ordersPayments[$orderPayment->method_id] = [
//                        'isCash' => $orderPayment->method->isCash,
//                        'total' => isset($orderPayment[$orderPayment->method_id]['total'])
//                            ? $orderPayment[$orderPayment->method_id]['total'] + $orderPayment->amount
//                            : $orderPayment->amount,
//                        'count' => isset($orderPayment[$orderPayment->method_id]['count'])
//                            ? $orderPayment[$orderPayment->method_id]['count'] + 1
//                            : 1,
//                    ];
//                }

                // statistic by products
                foreach ($order->orderProducts as $orderProduct) {
                    $ordersProducts[$orderProduct->product_id] = [
                        'name' => $orderProduct->product->name,
                        'quantity' => isset($ordersProducts[$orderProduct->product_id]['quantity'])
                            ? $ordersProducts[$orderProduct->product_id]['quantity'] + $orderProduct->quantity
                            : $orderProduct->quantity,
                        'total' => isset($ordersProducts[$orderProduct->product_id]['total'])
                            ? $ordersProducts[$orderProduct->product_id]['total'] + $orderProduct->tax + $orderProduct->price // get net price
                            : $orderProduct->tax + $orderProduct->price,
                        'count' => isset($ordersProducts[$orderProduct->product_id]['count'])
                            ? $ordersProducts[$orderProduct->product_id]['count'] + 1
                            : 1,
                    ];
                }

                // employees statistic
                $employee = $order->employee;
                if ($employee) {
                    $ordersEmployees[$employee->id]['name'] = $employee->name;
                    $ordersEmployees[$employee->id]['total'] = isset($ordersEmployees[$employee->id]['total'])
                        ? $ordersEmployees[$employee->id]['total'] + $order->total - $order->total_tax
                        : $order->total - $order->total_tax;
                    $ordersEmployees[$employee->id]['count'] = isset($ordersEmployees[$employee->id]['count'])
                        ? $ordersEmployees[$employee->id]['count'] + 1
                        : 1;
                }
                $total += $order->total;
                $totalTax += $order->total_tax;
            }
        }

//        VarDumper::dump($ordersPayments, 10, 1); die();

        $this->view->registerCssFile('/css/location_report.css');

        return $this->render('report', [
            'orders' => $orders ?? null,
            'dateFormat' => $dateFormat,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'location' => $locationId ?? null,
            'orderTotal' => $total ?? 0,
            'orderTotalTax' => $totalTax ?? 0,
            'ordersByDays' => $ordersByDays ?? null,
            'ordersProducts' => $ordersProducts ?? null,
            'ordersEmployees' => $ordersEmployees ?? null,
        ]);
    }
}
