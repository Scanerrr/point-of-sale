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
use yii\widgets\{ActiveForm, Pjax};
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use frontend\models\CreateCustomerForm;
use common\models\{Customer, OrderPayment, PaymentMethod};


$colors = ['default', 'primary', 'success', 'danger', 'warning', 'info'];
$total = $cart->total
?>
    <div class="panel panel-default">
        <div class="panel-body">
            <h4>Total Due: <span class="total-due"><?= Yii::$app->formatter->asCurrency($total) ?></span></h4>
            <h4>Total: <span class="total"><?= Yii::$app->formatter->asCurrency($total) ?></span></h4>
        </div>
    </div>
<?php $form = ActiveForm::begin(['options' => ['class' => 'checkout-form']]) ?>
    <div class="panel panel-<?= $colors[array_rand($colors)] ?>">
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
                'class' => 'btn btn-sm btn-success add-customer',
                'data' => ['toggle' => 'modal', 'target' => '#add-customer-modal']
            ]) ?>

        </div>
    </div>
    <div class="panel panel-<?= $colors[array_rand($colors)] ?>">
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
<!--TODO: MAKE SEVERAL PAYMENTS AND ADD CONDITION IF WHOLE PRICE IS PAID-->
        </div>
    </div>

    <?= Html::submitButton('Checkout') ?>
<?php ActiveForm::end() ?>

    <!--Add customer-->
<?php Modal::begin([
    'header' => Html::tag('h3', 'Add Customer'),
    'id' => 'add-customer-modal',
    'size' => 'modal-md',
]);

$customerModel = new CreateCustomerForm();

$form = ActiveForm::begin([
    'action' => ['/customer/create'],
    'options' => ['class' => 'create_customer-form']
]) ?>
    <div class="col-sm-6">
        <?= $form->field($customerModel, 'firstname')->textInput(['class' => 'form-control customer-firstname']) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($customerModel, 'lastname')->textInput(['class' => 'form-control customer-lastname']) ?>
    </div>
    <div class="col-sm-12">
        <?= $form->field($customerModel, 'email')->textInput() ?>
    </div>
    <div class="col-sm-8">
        <?= $form->field($customerModel, 'phone')->textInput() ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($customerModel, 'gender')->dropDownList(['male' => 'Male', 'female' => 'Female']) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($customerModel, 'country')->textInput(['value' => $location->country]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($customerModel, 'state')->textInput(['value' => $location->state]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($customerModel, 'city')->textInput(['value' => $location->city]) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($customerModel, 'address')->textInput(['value' => $location->address]) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($customerModel, 'zip')->textInput(['value' => $location->zip]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-primary', 'name' => 'create-button']) ?>
    </div>
<?php ActiveForm::end() ?>
<?php Modal::end() ?>


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