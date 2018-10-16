<?php /** @noinspection PhpUnhandledExceptionInspection */

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use Scanerrr\Image;
use common\models\Location;
use yiister\gentelella\assets\Asset;
use yiister\gentelella\widgets\{FlashAlert, Menu};

Asset::register($this);
$user = Yii::$app->user->identity;
$avatar = Image::resize($user->avatarUrl, 57);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="nav-<?= !empty($_COOKIE['menuIsCollapsed']) && $_COOKIE['menuIsCollapsed'] == 'true' ? 'sm' : 'md' ?>">
<?php $this->beginBody(); ?>
<div class="container body">

    <div class="main_container">

        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <a href="/" class="site_title"><i class="fa fa-paw"></i> <span>POS!</span></a>
                </div>
                <div class="clearfix"></div>

                <!-- menu prile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                        <img src="<?= $avatar ?>" alt="user avatar in left menu"
                             class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2><?= $user->name ?></h2>
                    </div>
                </div>
                <!-- /menu prile quick info -->

                <br/>

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="menu_section">
                        <h3>General</h3>
                        <?=
                        Menu::widget(
                            [
                                'items' => [
                                    ['label' => 'Home', 'url' => '/', 'icon' => 'home'],
                                    ['label' => 'Locations', 'url' => ['location/index'], 'icon' => 'archive'],
                                    [
                                        'label' => 'Inventory',
                                        'url' => ['inventory/index', 'id' => Location::find()
                                            ->select('id')
                                            ->orderBy('id')
                                            ->limit(1)
                                            ->scalar()],
                                        'icon' => 'list-alt'
                                    ],
                                    ['label' => 'Employees', 'url' => ['employee/index'], 'icon' => 'users'],
                                    [
                                        'label' => 'Catalog',
                                        'icon' => 'tags fw',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Categories', 'url' => ['category/index']],
                                            ['label' => 'Products', 'url' => ['product/index']],
                                            ['label' => 'Supplier', 'url' => ['supplier/index']],
                                        ],
                                    ],
                                    [
                                        'label' => 'Sales',
                                        'icon' => 'shopping-cart',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Order', 'url' => ['order/index']],
                                            ['label' => 'Refunds', 'url' => ['#']],
                                        ],
                                    ],
                                    [
                                        'label' => 'Badges',
                                        'url' => '#',
                                        'icon' => 'table',
                                        'items' => [
                                            [
                                                'label' => 'Default',
                                                'url' => '#',
                                                'badge' => '123',
                                            ],
                                            [
                                                'label' => 'Success',
                                                'url' => '#',
                                                'badge' => 'new',
                                                'badgeOptions' => ['class' => 'label-success'],
                                            ],
                                            [
                                                'label' => 'Danger',
                                                'url' => '#',
                                                'badge' => '!',
                                                'badgeOptions' => ['class' => 'label-danger'],
                                            ],
                                        ],
                                    ],
                                ],
                            ]
                        )
                        ?>
                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                               aria-expanded="false">
                                <img src="<?= $avatar ?>" alt=""><?= $user->name ?>
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu pull-right">
                                <li><a href="javascript:;"> Profile</a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="badge bg-red pull-right">50%</span>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">Help</a>
                                </li>
                                <li>
                                    <?= Html::a('<i class="fa fa-sign-out pull-right"></i> Log Out', ['site/logout'], [
                                        'data-method' => 'POST'
                                    ]) ?>
                                </li>
                            </ul>
                        </li>

                        <li role="presentation" class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown"
                               aria-expanded="false">
                                <i class="fa fa-envelope-o"></i>
                                <span class="badge bg-green">6</span>
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                <li>
                                    <a>
                      <span class="image">
                                        <img src="http://placehold.it/128x128" alt="Profile Image"/>
                                    </span>
                                        <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                                        <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                      <span class="image">
                                        <img src="http://placehold.it/128x128" alt="Profile Image"/>
                                    </span>
                                        <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                                        <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                      <span class="image">
                                        <img src="http://placehold.it/128x128" alt="Profile Image"/>
                                    </span>
                                        <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                                        <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                      <span class="image">
                                        <img src="http://placehold.it/128x128" alt="Profile Image"/>
                                    </span>
                                        <span>
                                        <span>John Smith</span>
                      <span class="time">3 mins ago</span>
                      </span>
                                        <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="text-center">
                                        <a href="/">
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <?php if (isset($this->params['h1'])): ?>
                <div class="page-title">
                    <div class="title_left">
                        <h1><?= $this->params['h1'] ?></h1>
                    </div>
                    <div class="title_right">
                        <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">Go!</button>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>

            <?= FlashAlert::widget(['showHeader' => true]) ?>

            <?= $content ?>
        </div>
        <!-- /page content -->
        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com" rel="nofollow" target="_blank">Colorlib</a><br/>
                Extension for Yii framework 2 by <a href="http://yiister.ru" rel="nofollow" target="_blank">Yiister</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>

</div>

<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>
<!-- /footer content -->
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage() ?>
