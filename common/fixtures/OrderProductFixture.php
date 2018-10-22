<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class OrderProductFixture extends ActiveFixture
{
    public $modelClass = 'common\models\OrderProduct';
    public $dataFile = '@common/tests/_data/order_product.php';
}