<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class InventoryLogFixture extends ActiveFixture
{
    public $modelClass = 'common\models\InventoryLog';
    public $dataFile = '@common/tests/_data/inventory_log.php';
}