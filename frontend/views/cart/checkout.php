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

use yii\helpers\{Html, Url};
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use common\models\Customer;
use yii\web\JsExpression;

$total = $cart->total;
/*
?>
    <div class="panel panel-default">
        <div class="panel-body">
            <h4>Total Due: <span class="total-due"><?= Yii::$app->formatter->asCurrency($total) ?></span></h4>
            <h4>Total: <span class="total"><?= Yii::$app->formatter->asCurrency($total) ?></span></h4>
        </div>
    </div>
<?php $form = ActiveForm::begin(['options' => ['class' => 'checkout-form']]) ?>
    <div class="panel panel-primary">
        <div class="panel-heading">Customer</div>
        <div class="panel-body">

            <?php Pjax::begin(['id' => 'customer-load']) ?>

            <?= $form->field($model, 'customer_id')->widget(Select2::class, [
                'data' => Customer::find()
                    ->select(['CONCAT(firstname, " ", lastname, " (", email , ")") AS name'])
                    ->orderBy('name')
                    ->indexBy('id')
                    ->column(),
                'options' => [
                    'placeholder' => 'Select a customer ...',
                    'id' => 'assigned-customer'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->hint('If no user found click "Create Customer"') ?>

            <?php Pjax::end() ?>

            <?= Html::button('Create customer', [
                'class' => 'btn btn-sm btn-default add-customer',
                'data' => ['toggle' => 'modal', 'target' => '#add-customer-modal']
            ]) ?>

        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">Payment</div>
        <div class="panel-body">

            <label for="payment-type">Payment Type</label>

            <?= Html::radioList('payment_type', null,
                [PaymentMethod::TYPE_CASH => 'Cash', PaymentMethod::TYPE_CREDIT_CARD => 'Credit Card'],
                ['id' => 'payment-type']
            ) ?>

            <?= Html::hiddenInput('payment_amount', null) ?>
            <?= Html::hiddenInput('payment_method', null) ?>
            <?= Html::hiddenInput('payment_card_number', null) ?>
        </div>
    </div>

<?= Html::submitButton('Checkout') ?>
<?php ActiveForm::end() ?>

<?php Modal::begin([
    'header' => Html::tag('h3', 'Payment - Cash'),
    'id' => 'payment-modal',
    'size' => 'modal-md',
]) ?>
    <div>Total Due <span class="total-due"><?= $total ?></span></div>

    <div class="payment-by-type"></div>

    <div class="form-group">
        <?= Html::button('Ok', ['class' => 'btn btn-primary assign-payment']) ?>
    </div>
<?php Modal::end() ?>

<?php
$paymentTypeCredit = PaymentMethod::TYPE_CREDIT_CARD;
$url = Url::to(['/cart/load-form']);
$script = <<< JS
$(() => {
    $('#payment-type').on('change', e => {
        const type = e.target.value
        const headerText = type === '$paymentTypeCredit' ? 'External Terminal' : 'Cash'
        $.ajax({
            type: 'POST',
            url: '$url',
            data: {type: type}
        })
            .done(data => {
                const modal = $('#payment-modal')
                modal.find('.payment-by-type').html(data)
                modal.find('.modal-header h3').text('Payment - ' + headerText)
                modal.modal()
            })
            .fail(failHandler)
    })
    
    $('.assign-payment').on('click', e => {
        e.preventDefault()
        const form = $('.checkout-form')
        const modal = $('#payment-modal')
        
        const amount = '[name=payment_amount]'
        const method = '[name=payment_method]'
        const cardNumber = '[name=payment_card_number]'
        
        assignValue(modal.find(amount), form.find(amount))
        assignValue(modal.find(method), form.find(method))
        assignValue(modal.find(cardNumber), form.find(cardNumber))
        
        modal.modal('hide')
    })
})

function assignValue(from, to) {
    const value = from.val()
    if (!value) return;
    to.val(value)
}
JS;

$this->registerJs($script, $this::POS_END);

$this->registerJsFile('@web/js/checkout.js', [
    'depends' => [\yii\web\JqueryAsset::class],
]);
*/ ?>
<?php
$payments = Yii::$app->session->get('location.' . $location->id . '.payments', []);
$paid = array_reduce($payments, function ($total, $payment) {
    return $total + $payment['price'];
}, 0);

$remain = $total - $paid;
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
                            <span>Add customer</span>
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
                        <span class="change-due <?= $remain >= 0 ? 'negative' : '' ?>"><?= Yii::$app->formatter->asCurrency(abs($remain)) ?></span>
                    </div>
                </section>
                <?php Pjax::end() ?>
            </div>

            <div class="col-sm-7 col-right">
                <div class="wrapper">
                    <section class="checkout-section payment">
                        <h3 class="text-center">Select Payment</h3>
                        <div class="cards select-payment-type <?= $remain >= 0 ? 'disabled' : ''?>">
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
                    <?php if ($remain >= 0): ?>
                    <section class="checkout-section">
                        <span class="small">You can complete the sale now</span>
                    </section>
                    <?php endif; ?>

                    <section class="checkout-section payment-actions">
                        <button class="card add-payment" <?= $remain >= 0 ? 'disabled' : ''?>>Add payment</button>
                        <button class="card complete-sale" <?= $remain >= 0 ? '' : 'disabled'?>>Complete Sale</button>
                    </section>
                </div>
            </div>
        </div>
    </div>

<?php
// Add customer modal
Modal::begin([
    'header' => Html::tag('h3', 'Add Customer'),
    'id' => 'add-customer-modal',
    'size' => 'modal-md',
]) ?>

<?= $this->render('_add_customer_form') ?>

<?php Modal::end() ?>