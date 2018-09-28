<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-28
 * Time: 2:08 PM
 */
use yii\widgets\Menu;
?>

<div class="panel panel-default">
    <div class="panel-heading">Categories</div>
    <?= Menu::widget([
        'options' => ['class' => 'nav nav-pills nav-stacked parent-menu'],
        'items' => $items,
//        'submenuTemplate' => '<ul class="nav nav-pills nav-stacked children-menu">{items}</ul>'
    ]); ?>
</div>
