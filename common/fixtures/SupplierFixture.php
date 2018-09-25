<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class SupplierFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Supplier';
    public $dataFile = '@common/tests/_data/supplier.php';
}