<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use common\models\Customer;
use frontend\models\CreateCustomerForm;
use frontend\controllers\access\CookieController;

class CustomerController extends CookieController
{

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
