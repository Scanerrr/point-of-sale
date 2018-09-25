<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class CategoryFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Category';
    public $dataFile = '@common/tests/_data/category.php';
}