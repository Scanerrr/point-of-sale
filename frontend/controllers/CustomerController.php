<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use common\models\Customer;
use frontend\models\CreateCustomerForm;
use frontend\controllers\access\CookieController;

class CustomerController extends CookieController
{
    /**
     * @return Response
     */
    public function actionCreate(): Response
    {
        $model = new CreateCustomerForm();

        $response = ['success' => false, 'customer' => null];

        if ($model->load(Yii::$app->request->post()) && $customer = $model->create()) {
            $response = ['success' => true, 'customer' => $customer];
        }

        return $this->asJson($response);
    }

    public function actionSearch($q = null): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if ($q) {
            $out['results'] = Customer::find()
                ->select(['id', 'CONCAT(firstname, " ", lastname, " (", email , ")") AS text'])
                ->filterWhere(['LIKE', 'firstname', $q])
                ->orFilterWhere(['LIKE', 'lastname', $q])
                ->orFilterWhere(['LIKE', 'email', $q])
                ->orFilterWhere(['LIKE', 'phone', $q])
                ->asArray()
                ->all();
        }
        return $out;
    }
}
