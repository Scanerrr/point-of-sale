<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class OrderPaymentFixture extends ActiveFixture
{
    public $modelClass = 'common\models\OrderPayment';
    public $dataFile = '@common/tests/_data/order_payment.php';
}