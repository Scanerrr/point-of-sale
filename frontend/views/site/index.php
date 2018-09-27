<?php
use yii\helpers\Html;
use Scanerrr\Image;

/* @var $this yii\web\View */
/* @var $categories \common\models\Category */

$this->title = 'Home';
?>
<div class="site-index">

    <h1>Choose category!</h1>

    <div class="body-content">
        <div class="cards">
            <?php foreach ($categories as $category): ?>
                <a href="<?= \yii\helpers\Url::to(['category/index', 'id' => $category->id]) ?>">
                    <div class="card" data-toggle="">
                        <div class="card-header"><?= $category->name ?></div>
                        <div class="card-main">
<!--                            --><?php //if ($category->image): ?>
                                <?= Html::img(Image::resize($category->imageUrl, 120), [
                                    'width' => 120,
                                    'class' => 'card-main-thumb'
                                ]) ?>
<!--                            --><?php //endif; ?>
                            <div class="card-main-description"><?= $category->name ?></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
