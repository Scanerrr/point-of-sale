<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\VarDumper;
use yii\web\Response;
use common\models\Customer;
use frontend\models\CreateCustomerForm;
use frontend\controllers\access\CookieController;

class CustomerController extends CookieController
{
    public function actionSearch(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $response = ['success' => false, 'customers' => []];

        if ($q = Yii::$app->request->post('query')) {
            $response['customers'] = Customer::find()
                ->select(['id', 'firstname', 'lastname', 'email', 'phone', 'created_at'])
                ->filterWhere(['LIKE', 'firstname', $q])
                ->orFilterWhere(['LIKE', 'lastname', $q])
                ->orFilterWhere(['LIKE', 'email', $q])
                ->all();

            if ($response['customers']) $response['success'] = true;
        }

        return $response;
    }

    public function actionCreate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new CreateCustomerForm();

        $response = ['success' => false, 'customer' => null];

        if ($model->load(Yii::$app->request->post())) {
            if ($customer = $model->create()) {
                $response = ['success' => true, 'customer' => $customer];

                Yii::$app->session->set('customer', $customer->id);
            }
        }
        return $response;
    }

    public function actionAssign(int $id): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = ['success' => false, 'customer' => null];

        if ($customer = Customer::findOne($id)) {
            $response = ['success' => true, 'customer' => $customer];
            Yii::$app->session->set('customer', $customer->id);
        }

        return $response;
    }
}
