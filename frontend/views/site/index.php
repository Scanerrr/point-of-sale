<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Choose location!</h1>
        <!---->
        <!--        <p class="lead">You have successfully created your Yii-powered application.</p>-->
        <!---->
        <!--        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>-->
    </div>

    <div class="body-content">

        <div class="row">
            <?php foreach ($locations as $location): ?>
                <div class="location">
                    <?= \yii\helpers\Html::a($location->name, ['/location/' . $location->id]) ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>
