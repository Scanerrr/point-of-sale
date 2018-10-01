<?php

/* @var $this \yii\web\View */
/* @var $cart \frontend\components\cart\Cart */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use Scanerrr\Image;

AppAsset::register($this);
$cart = Yii::$app->cart;
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

    <main class="container">
        <div class="shopping-cart" style="display: none">
            <div class="shopping-cart-header">
                <i class="fa fa-shopping-cart cart-icon"></i><span class="badge"><?= $cart->getCount() ?></span>
                <div class="shopping-cart-total">
                    <span class="lighter-text"><strong>Total:</strong></span>
                    <span class="main-color-text"><?= Yii::$app->formatter->asCurrency($cart->getTotal()) ?></span>
                </div>
            </div> <!--end shopping-cart-header -->

            <ul class="shopping-cart-items list-unstyled">
                <?php foreach ($cart->items as $item): ?>
                <?php $product = $item['product'] ?>
                    <li class="clearfix">
                        <?= Html::img($product->image ? Image::resize($product->imageUrl, 70) : null, ['width' => 70, 'class' => 'img-rounded']) ?>
                        <span class="item-name text-capitalize"><?= $product->name ?></span>
                        <span class="item-price"><?= Yii::$app->formatter->asCurrency($item['price']) ?></span>
                        <span class="item-quantity">Quantity: <?= $item['quantity'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>

            <a href="#" class="btn btn-primary btn-lg btn-block">Checkout</a>
        </div>
        <?= Alert::widget() ?>
        <?= $content ?>
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
