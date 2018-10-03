<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-10-02
 * Time: 4:44 PM
 */

/* @var $this \yii\web\View */
/* @var $cart \frontend\components\cart\Cart */

use yii\helpers\Html;
use yii\bootstrap\Modal;

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
                    <?php /*if($product->image): */ ?><!--
                        <div>
                            <? /*= Html::img(Image::resize($product->imageUrl, 70), ['width' => 70, 'class' => 'img-rounded']) */ ?>
                        </div>
                    --><?php /*endif; */ ?>
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

        <a href="#" class="btn btn-primary btn-lg btn-block" data-toggle="modal"
           data-target="#checkout-modal">Checkout</a>
    <?php else: ?>
        <p class="text-center">Cart is empty</p>
    <?php endif; ?>
</div>

<!--Checkout-->
<?php Modal::begin([
    'header' => Html::tag('h3', 'Checkout details'),
    'id' => 'checkout-modal',
    'size' => 'modal-lg',
]) ?>
<div class="modal-first">
    <div>
        <span class="lighter-text"><strong>Total:</strong></span>
        <span class="main-color-text"><?= Yii::$app->formatter->asCurrency($cart->total + $cart->tax) ?></span>
    </div>
    <div>
        <button class="btn btn-sm btn-success add-customer" data-toggle="modal" data-target="#customer-modal"><i
                    class="fa fa-plus"></i> Add Customer
        </button>
    </div>
</div>
<div class="modal-second">

</div>
<?php Modal::end() ?>

<!--Find customer-->
<?php Modal::begin([
    'header' => Html::tag('h3', 'Customer search'),
    'id' => 'customer-modal',
    'size' => 'modal-md',
]) ?>
<div class="modal-first">
    <?= Html::beginForm(['/customer/search'], 'POST', ['class' => 'form-horizontal search_customer-form']) ?>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-1" style="padding-right: 0">
                <?= Html::input('text', 'query', '', ['class' => 'form-control']) ?>
            </div>
            <div class="col-sm-3" style="padding-left: 0">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= Html::endForm() ?>
</div>
<div class="modal-second">
    <table class="table found-customers">
        <thead>
        <tr>
            <th>First</th>
            <th>Last</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Date Added</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

</div>
<?php Modal::end() ?>

<?php $this->registerJsFile('@web/js/shopping_cart.js', [
    'depends' => [\yii\web\JqueryAsset::class]
]) ?>