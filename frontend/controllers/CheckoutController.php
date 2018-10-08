<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 10/8/2018
 * Time: 12:42 PM
 */

namespace frontend\controllers;


use Yii;
use common\models\Customer;
use frontend\controllers\access\CookieController;

class CheckoutController extends CookieController
{
    public function actionIndex()
    {
        return $this->render('index', [
            'customer' => Customer::findOne(Yii::$app->session->get('customer'))
        ]);
    }
}