<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class LocationUserFixture extends ActiveFixture
{
    public $modelClass = 'common\models\LocationUser';
    public $dataFile = '@common/tests/_data/location_user.php';
}