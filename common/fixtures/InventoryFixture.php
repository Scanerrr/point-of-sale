<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class InventoryFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Inventory';
    public $dataFile = '@common/tests/_data/inventory.php';
}