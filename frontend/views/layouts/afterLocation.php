<?php

/* @var $this \yii\web\View */
/* @var $cart \frontend\components\cart\Cart */
/* @var $location \common\models\Location */

/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Pjax;
use frontend\assets\AppAsset;
use yii\bootstrap\{Nav, NavBar};
use common\widgets\{Alert, ShoppingCart};

AppAsset::register($this);

$location = Yii::$app->params['location'];
$user = Yii::$app->user->identity;
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
                . Html::beginForm(['/location/change-status'], 'post', ['class' => 'change-status-form'])
                . Html::submitButton(
                    !$location->is_open ? '<i class="fa fa-unlock"></i> to Open' : '<i class="fa fa-lock"></i> To Close',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>';
            if ($location->is_open) {
                $menuItems[] = '<li>'
                    . Html::beginForm(['/location/clock'], 'post', ['class' => 'clock-form'])
                    . Html::submitButton('<i class="fa fa-clock-o"></i> Clock'
                        . ($user->is_working ? '-Out' : '-In'),
                        ['class' => 'btn btn-link']
                    )
                    . Html::endForm()
                    . '</li>';
            }
            $menuItems[] = '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . $user->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>';
        }
        Pjax::begin(['id' => 'nav-location-menu']);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right list-location-menu'],
            'items' => $menuItems,
        ]);
        Pjax::end();
        NavBar::end();
        ?>
    </header>


    <main class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </main>
</div>

<footer class="footer">
    <div class="container">
        <p class="text-center">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
    </div>
</footer>

<?= ShoppingCart::widget() ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
