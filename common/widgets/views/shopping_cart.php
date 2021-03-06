<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-10-02
 * Time: 4:44 PM
 */

/* @var $this \yii\web\View */
/* @var $customer \common\models\Customer */

/* @var $cart \frontend\components\cart\Cart */

use Scanerrr\Image;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

$cart = Yii::$app->cart;
$items = $cart->getItems();
?>

<div class="shopping-cart" style="display: none">
    <div class="shopping-cart-header text-right">
        <div>
            <span class="lighter-text"><strong>Subtotal:</strong></span>
            <span class="main-color-text"><?= Yii::$app->formatter->asCurrency($cart->subTotal) ?></span>
        </div>
        <div>
            <span class="lighter-text"><strong>Tax:</strong></span>
            <span class="main-color-text"><?= Yii::$app->formatter->asCurrency($cart->totalTax) ?></span>
        </div>
        <div>
            <span class="lighter-text"><strong>Discount:</strong></span>
            <span class="main-color-text"><?= Yii::$app->formatter->asCurrency($cart->totalDiscount) ?></span>
        </div>
        <div>
            <span class="lighter-text"><strong>Total:</strong></span>
            <span class="main-color-text"><?= Yii::$app->formatter->asCurrency($cart->total) ?></span>
        </div>
    </div>
    <?php if ($items): ?>
        <ul class="shopping-cart-items list-unstyled">
            <?php foreach ($items as $item): ?>
                <?php $product = $item['product'] ?>
                <li class="clearfix">
                    <div class="item-mid-content">
                        <span class="item-name text-capitalize"><?= Html::encode($product->name) ?></span>
                        <div>
                            <span class="item-price"><?= Yii::$app->formatter->asCurrency($product->markup_price) ?></span>
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
        <?= Html::a('Checkout', ['/cart/index'], [
            'class' => 'btn btn-primary btn-lg btn-block',
        ]) ?>
    <?php else: ?>
        <p class="text-center">Cart is empty</p>
    <?php endif; ?>
</div>