<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $categories \common\models\Category */

$this->title = 'Home';
?>
<div class="site-index">

    <h1>Choose Location!</h1>

    <div class="body-content">
        <div class="cards">
            <?php foreach ($locations as $location): ?>
                <a href="<?= Url::to(['location/index', 'id' => $location->id]) ?>">
                    <div class="card">
                        <div class="card-header"><?= Html::encode($location->name) ?></div>
                        <div class="card-main">
                            <div class="card-main-description"><?= Html::encode($location->name) ?></div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
