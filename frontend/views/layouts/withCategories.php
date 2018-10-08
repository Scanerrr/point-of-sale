<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 10/8/2018
 * Time: 2:21 PM
 */

/* @var $content string */

use common\widgets\Categories;

?>
<?php $this->beginContent('@frontend/views/layouts/afterLocation.php'); ?>
<div class="row">
    <div class="col-md-3 col-sm-4">

        <?= Categories::widget([
            'category' => isset($this->params['category']) ? $this->params['category'] : null,
        ]) ?>

    </div>
    <div class="col-md-9 col-sm-8">
        <?= $content ?>
    </div>
</div>
<?php $this->endContent() ?>
