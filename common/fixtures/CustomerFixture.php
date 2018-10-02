<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class CustomerFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Customer';
    public $dataFile = '@common/tests/_data/customer.php';
}