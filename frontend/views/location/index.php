<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-21
 * Time: 3:17 PM
 */

/** @var \common\models\Location $location */
/** @var \common\models\Category $categories */

use yii\helpers\Html;
use yii\helpers\Url;
use Scanerrr\Image;

?>
<div class="user-index">

    <div class="jumbotron">
        <h1>Location "<?= Html::encode($location->name) ?>"</h1>

    </div>

    <div class="body-content">

        <div class="cards">
            <?php foreach ($categories as $category): ?>
                <a href="<?= Url::to(['catalog/category', 'id' => $category->id]) ?>">
                    <div class="card">
                        <div class="card-header"><?= Html::encode($category->name) ?></div>
                        <div class="card-main">
                            <?php if ($category->image): ?>
                                <?= Html::img(Image::resize($category->imageUrl, 120), [
                                    'width' => 120,
                                    'class' => 'img-rounded card-main-thumb'
                                ]) ?>
                            <?php endif; ?>
                            <div class="card-main-description"><?= Html::encode($category->name) ?></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</div>
