<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 10/8/2018
 * Time: 12:43 PM
 */

/* @var $cart \frontend\components\cart\Cart */
/* @var $this \yii\web\View */

use Scanerrr\Image;
use yii\widgets\Pjax;
use yii\helpers\{Url, Html};

$cart = Yii::$app->cart;
$items = $cart->getItems();
?>
<?php if ($items): ?>

    <div class="cart">
        <ul class="cartWrap">
            <?php $idx = 0 ?>
            <?php foreach ($items as $item): ?>
                <?php $product = $item['product'] ?>
                <li class="items <?= $idx++ % 2 === 0 ? 'odd' : 'even' ?>">

                    <div class="infoWrap">
                        <div class="cartSection">
                            <?= Html::img(Image::resize($product->imageUrl, 300), ['class' => 'itemImg']) ?>
                            <p class="itemNumber"># <?= $product->barcode ?></p>
                            <h3><?= Html::encode($product->name) ?></h3>

                            <p><?= Html::input('number', null, $item['quantity'], [
                                    'min' => 1,
                                    'class' => 'qty update-quantity',
                                    'data-id' => $product->id
                                ]) ?> x <?= Yii::$app->formatter->asCurrency($item['price']) ?></p>

                            <p class="stockStatus">In Stock</p>
                        </div>

                        <div class="prodTotal cartSection">
                            <p><?= Yii::$app->formatter->asCurrency($item['price'] * $item['quantity']) ?></p>
                        </div>
                        <div class="cartSection removeWrap">
                            <?= Html::a('x', '#', [
                                'class' => 'remove',
                                'data-href' => Url::to(['/cart/delete', 'id' => $product->id]),
                                'data-type' => 'post'
                            ]) ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php Pjax::begin(['id' => 'cart-idx-total']) ?>

        <div class="subtotal cf">
            <ul class="list-unstyled">
                <li class="totalRow"><span class="label">Subtotal</span><span
                            class="value"><?= Yii::$app->formatter->asCurrency($cart->total) ?></span></li>
                <li class="totalRow"><span class="label">Tax</span><span
                            class="value"><?= Yii::$app->formatter->asCurrency($cart->tax) ?></span></li>
                <li class="totalRow final"><span class="label">Total</span><span
                            class="value"><?= Yii::$app->formatter->asCurrency($cart->total + $cart->tax) ?></span></li>
                <li class="totalRow">
                    <?= Html::a('Checkout', ['/cart/checkout'], [
                        'class' => 'button continue create-order',
                        'data-href' => Url::to(['/cart/checkout']),
                    ]) ?>
                </li>
            </ul>
        </div>

        <?php Pjax::end() ?>

    </div>
    <?php $this->registerJsFile('@web/js/cart.js', [
        'depends' => [\yii\web\JqueryAsset::class],
    ]) ?>
<?php else: ?>
    <h3>Cart is empty</h3>
<?php endif; ?>