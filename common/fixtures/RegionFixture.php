<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class RegionFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Region';
    public $dataFile = '@common/tests/_data/region.php';
}