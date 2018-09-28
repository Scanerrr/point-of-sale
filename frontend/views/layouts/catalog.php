<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-28
 * Time: 2:31 PM
 */

/* @var $content string */

use common\widgets\Categories;

?>
<?php $this->beginContent('@frontend/views/layouts/layout.php'); ?>
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