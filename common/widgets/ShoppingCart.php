<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-28
 * Time: 1:59 PM
 */

namespace common\widgets;


use Yii;
use yii\base\Widget;

class ShoppingCart extends Widget
{
    public function run()
    {
        return $this->render('shopping_cart');
    }
}