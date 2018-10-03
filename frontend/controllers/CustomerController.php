<?php

namespace frontend\controllers;

use common\models\Customer;
use frontend\models\CreateCustomerForm;
use Yii;
use yii\web\Response;
use frontend\controllers\access\CookieController;

class CustomerController extends CookieController
{
    public function actionSearch()
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

    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new CreateCustomerForm();

        $response = ['success' => false, 'customer' => null];

        if ($model->load(Yii::$app->request->post())) {
            if ($response['customer'] = $model->create()) {
                Yii::$app->session->set('customer', $response['customer']->id);
                $response['success'] = true;
            }
        }
        return $response;
    }

}
