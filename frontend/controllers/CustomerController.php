<?php

namespace frontend\controllers;

use common\models\Customer;
use Yii;
use yii\web\Response;
use frontend\controllers\access\CookieController;

class CustomerController extends CookieController
{
    public function actionSearch()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($q = Yii::$app->request->post('query')) {
            $customer = Customer::find()
                ->select(['id', 'firstname', 'lastname', 'email', 'phone', 'created_at'])
                ->filterWhere(['LIKE', 'firstname', $q])
                ->orFilterWhere(['LIKE', 'lastname', $q])
                ->orFilterWhere(['LIKE', 'email', $q])
                ->all();
            return $customer;
        }

        return [];
    }

}
