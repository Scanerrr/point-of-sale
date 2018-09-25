<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ProductFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Product';
    public $dataFile = '@common/tests/_data/product.php';
}