<?php

/* @var $this \yii\web\View */
/* @var $cart \frontend\components\cart\Cart */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\{Nav, NavBar};
use frontend\assets\AppAsset;
use common\widgets\{Categories, Alert};

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <header>
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-top',
            ],
        ]);
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
        } else {
            $menuItems[] = [
                'label' => '<i class="fa fa-shopping-cart"></i> <span class="badge">' . Yii::$app->cart->getCount() . '</span>',
                'url' => '#',
                'options' => ['class' => 'show-cart'],
                'encode' => false
            ];
            $menuItems[] = '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>';
        }
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>
    </header>

    <?= $this->render('shopping_cart') ?>

    <main class="container">
        <?= Alert::widget() ?>
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
    </main>
</div>

<footer class="footer">
    <div class="container">
        <p class="text-center">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
