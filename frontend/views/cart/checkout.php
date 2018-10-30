<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 10/8/2018
 * Time: 1:18 PM
 */

/* @var $cart \frontend\components\cart\Cart */
/* @var $location \common\models\Location */
/* @var $model \common\models\Order */
/* @var $paid float */
/* @var $payments array */

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use common\models\Customer;

$total = $cart->total;

Pjax::begin(['id' => 'payments-load0']);

$remain = $total - $paid;

$isPaid = $paid >= $total;

Pjax::end();
?>

    <div class="checkout-form">
        <div class="row">
            <div class="col-sm-5 col-left">
                <h2 class="text-center">Checkout Information</h2>
                <section class="checkout-section cart-items">
                    <div class="item">
                        <span>Products</span>
                    </div>
                    <?php foreach ($cart->items as $item): ?>
                        <?php $product = $item['product']; ?>
                        <div class="item item-c checkout-section-flex">
                            <span><?= $product->name ?></span>
                            <span>Qty: <?= $item['quantity'] ?></span>
                            <span><?= Yii::$app->formatter->asCurrency($product->markup_price) ?></span>
                        </div>
                    <?php endforeach; ?>
                </section>

                <section class="checkout-section customer">
                    <?php Pjax::begin(['id' => 'customer-load']) ?>
                    <?= Select2::widget([
                        'name' => 'customer',
                        'data' => Customer::find()
                            ->select(['CONCAT(firstname, " ", lastname, " (", email , ")") AS name'])
                            ->orderBy('name')
                            ->indexBy('id')
                            ->column(),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'size' => Select2::SMALL,
                        'options' => [
                            'placeholder' => 'Select a customer ...',
                            'id' => 'assigned-customer'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            /* uncomment below to use AJAX search by firstname, lastname, email, phone */
//                            'minimumInputLength' => 3,
//                            'language' => [
//                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
//                            ],
//                            'ajax' => [
//                                'url' => Url::to(['/customer/search']),
//                                'dataType' => 'json',
//                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
//                            ],
//                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
//                            'templateResult' => new JsExpression('function(customer) { return customer.text; }'),
//                            'templateSelection' => new JsExpression('function (customer) { return customer.text; }')
                        ],
                    ]) ?>
                    <?php Pjax::end() ?>
                    <a href="#" class="add-customer" data-toggle="modal" data-target="#add-customer-modal">
                        <div class="text-center">
                            <span>Create new customer</span>
                        </div>
                    </a>
                </section>

                <section class="checkout-section totals">
                    <div class="item checkout-section-flex">
                        <span>Total</span>
                        <span><?= Yii::$app->formatter->asCurrency($total) ?></span>
                    </div>
                    <div class="item item-c checkout-section-flex">
                        <span>Tax</span>
                        <span><?= Yii::$app->formatter->asCurrency($cart->totalTax) ?></span>
                    </div>
                    <div class="item item-c checkout-section-flex">
                        <span>Discount</span>
                        <span><?= Yii::$app->formatter->asCurrency($cart->totalDiscount) ?></span>
                    </div>
                    <div class="item item-c checkout-section-flex">
                        <span>Subtotal</span>
                        <span><?= Yii::$app->formatter->asCurrency($cart->subTotal) ?></span>
                    </div>
                </section>

                <?php Pjax::begin(['id' => 'payments-load']) ?>
                <section class="checkout-section payments">
                    <div class="item">
                        <span>Paid</span>
                        <span><?= Yii::$app->formatter->asCurrency($paid) ?></span>
                    </div>
                    <?php foreach ($payments as $key => $payment): ?>
                        <div class="item item-c checkout-section-flex">
                            <span><?= $payment['name'] ?></span>
                            <span><?= Yii::$app->formatter->asCurrency($payment['price']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </section>

                <section class="checkout-section change-total">
                    <div class="item">
                        <span>Change Due</span>
                        <span class="change-due <?= $isPaid ? '' : 'negative' ?>"><?= Yii::$app->formatter->asCurrency(abs($remain)) ?></span>
                    </div>
                </section>
                <?php Pjax::end() ?>
            </div>

            <?php Pjax::begin(['id' => 'payments-load1']) ?>
            <div class="col-sm-7 col-right">
                <div class="wrapper">
                    <section class="checkout-section payment">
                        <h3 class="text-center">Select Payment</h3>
                        <div class="cards select-payment-type select-payment-type <?= $isPaid ? 'disabled' : '' ?>">
                            <div class="card" data-payment_type="0">
                                <div class="icon">
                                    <i class="fa fa-4x fa-money"></i>
                                </div>
                                <div class="title">Cash</div>
                            </div>
                            <div class="card" data-payment_type="1">
                                <div class="icon">
                                    <i class="fa fa-4x fa-credit-card"></i>
                                </div>
                                <div class="title">Credit</div>
                            </div>
                        </div>
                    </section>

                    <section class="checkout-section payment-by-type"></section>
                    <?php if ($isPaid): ?>
                        <section class="checkout-section">
                            <span class="small">You can complete the sale now</span>
                        </section>
                    <?php endif; ?>

                    <section class="checkout-section payment-actions">
                        <button class="card add-payment" <?= $isPaid ? 'disabled' : '' ?>>Add payment</button>
                        <button class="card complete-sale" <?= $isPaid ? '' : 'disabled' ?>>Complete Sale</button>
                    </section>
                </div>
            </div>
            <?php Pjax::end() ?>
        </div>
    </div>

<?php
// Add customer modal
Modal::begin([
    'header' => Html::tag('h3', 'Add Customer'),
    'id' => 'add-customer-modal',
    'size' => 'modal-md',
]);
echo $this->render('_add_customer_form');
Modal::end();