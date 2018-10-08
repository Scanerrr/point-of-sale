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

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use frontend\models\CreateCustomerForm;
use common\models\{Customer, OrderPayment, PaymentMethod};


$colors = ['primary', 'success', 'danger', 'warning', 'info'];
$total = $cart->total + $cart->tax
?>
<?php $form = ActiveForm::begin() ?>
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

        <?= Html::radioList('payment-type', null,
            [PaymentMethod::TYPE_CASH => 'Cash', PaymentMethod::TYPE_CREDIT_CARD => 'Credit Card'],
            ['id' => 'payment-type']
        ) ?>

        <?php $orderPaymentModel = new OrderPayment() ?>

        <div class="row">
            <div class="col-sm-6 credit-card" style="display: none">
                <?= $form->field($orderPaymentModel, 'method_id')->dropDownList(
                    PaymentMethod::find()
                        ->select('name')
                        ->where(['type_id' => PaymentMethod::TYPE_CREDIT_CARD])
                        ->indexBy('id')
                        ->column()
                ) ?>
            </div>
            <div class="col-sm-6 payment-details" style="display: none">
                <div class="total"><?= $total ?></div>
                <div class="form-group">
                    <label for="total-received">Total Received</label>
                    <?= Html::textInput('received', 0, [
                        'type' => 'number',
                        'step' => 'any',
                        'min' => 0,
                        'class' => 'form-control',
                        'id' => 'total-received'
                    ]) ?>
                </div>
                <h4>Change: <span class="result"><?= Yii::$app->formatter->asCurrency($total) ?></span></h4>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>

<!--Add customer-->
<?php Modal::begin([
    'header' => Html::tag('h3', 'Add Customer'),
    'id' => 'add-customer-modal',
    'size' => 'modal-md',
]) ?>
<?php $customerModel = new CreateCustomerForm();
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


<?php $this->registerJsFile('@web/js/checkout.js', [
    'depends' => [\yii\web\JqueryAsset::class],
]) ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        $('#payment-type').on('change', e => {
            const type = e.target.value
            const cardDiv = $('.credit-card')
            const paymentDetails = $('.payment-details').show()
            if (type === '<?= PaymentMethod::TYPE_CREDIT_CARD ?>') {
                paymentDetails.hide()
                cardDiv.show()
            } else {
                cardDiv.hide()
                paymentDetails.show()
            }
        })
        $('#total-received').on('change', e => {
            const value = e.target.value
            const result = (<?= $total ?> - value).toLocaleString()
            $('.result').text('$' + result)
        })
    })
</script>