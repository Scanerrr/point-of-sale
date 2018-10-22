<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class OrderFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Order';
    public $dataFile = '@common/tests/_data/order.php';
}