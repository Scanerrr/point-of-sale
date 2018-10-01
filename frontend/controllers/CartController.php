<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-10-01
 * Time: 12:52 PM
 */

namespace frontend\controllers;


use Yii;
use common\models\Product;
use yii\base\InvalidArgumentException;

class CartController extends AccessController
{

    public function actionIndex()
    {
        return 1;
    }

    public function actionAdd()
    {
        if (($productId = Yii::$app->request->post('product_id'))
                && ($price = Yii::$app->request->post('price'))
                && ($quantity = Yii::$app->request->post('quantity'))) {

            $product = Product::findOne($productId);
            if (!$product) throw new InvalidArgumentException('Product does\'n exists');
            Yii::$app->cart->add($product, $price, $quantity);
            Yii::$app->session->setFlash('success', 'Item added to cart');
        }
        return $this->redirect(Yii::$app->request->referrer ?? ['/site/index']);
    }
}