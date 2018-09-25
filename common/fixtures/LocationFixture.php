<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class LocationFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Location';
    public $dataFile = '@common/tests/_data/location.php';
}