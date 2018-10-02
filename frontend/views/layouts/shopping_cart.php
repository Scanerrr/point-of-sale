<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-10-02
 * Time: 4:44 PM
 */

use yii\helpers\Html;
use Scanerrr\Image;

$cart = Yii::$app->cart;
$location = Yii::$app->params['location'];
$tax = $cart->getTax($location->tax_rate);
?>

<div class="shopping-cart" style="display: none">
    <div class="shopping-cart-header text-right">
        <div>
            <span class="lighter-text"><strong>Tax:</strong></span>
            <span class="main-color-text"><?= Yii::$app->formatter->asCurrency($tax) ?></span>
        </div>
        <div>
            <span class="lighter-text"><strong>Subtotal:</strong></span>
            <span class="main-color-text"><?= Yii::$app->formatter->asCurrency($cart->total) ?></span>
        </div>
        <div>
            <span class="lighter-text"><strong>Total:</strong></span>
            <span class="main-color-text"><?= Yii::$app->formatter->asCurrency($cart->total + $cart->tax) ?></span>
        </div>
    </div>
    <?php if ($items = $cart->getItems()): ?>
        <ul class="shopping-cart-items list-unstyled">
            <?php foreach ($items as $item): ?>
                <?php $product = $item['product'] ?>
                <li class="clearfix">
                    <?php /*if($product->image): */?><!--
                        <div>
                            <?/*= Html::img(Image::resize($product->imageUrl, 70), ['width' => 70, 'class' => 'img-rounded']) */?>
                        </div>
                    --><?php /*endif; */?>
                    <div class="item-mid-content">
                        <span class="item-name text-capitalize"><?= Html::encode($product->name) ?></span>
                        <div>
                            <span class="item-price"><?= Yii::$app->formatter->asCurrency($item['price']) ?></span>
                            <span class="item-quantity">Quantity: <?= $item['quantity'] ?></span>
                        </div>
                    </div>
                    <div class="item-delete">
                        <?= Html::a('<span aria-hidden="true">&times;</span>', ['/cart/delete', 'id' => $product->id], [
                            'class' => 'close',
                            'aria-label' => 'Close',
                            'data-method' => 'POST'
                        ]) ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <a href="#" class="btn btn-primary btn-lg btn-block">Checkout</a>
    <?php else: ?>
        <p class="text-center">Cart is empty</p>
    <?php endif; ?>
</div>
