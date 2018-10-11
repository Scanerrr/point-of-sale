<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
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
}
