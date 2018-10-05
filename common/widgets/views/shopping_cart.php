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
$location = Yii::$app->params['location'];
$items = $cart->getItems();
$cart->setTax($location->tax_rate);
?>

    <div class="shopping-cart" style="display: none">
        <div class="shopping-cart-header text-right">
            <div>
                <span class="lighter-text"><strong>Tax:</strong></span>
                <span class="main-color-text"><?= Yii::$app->formatter->asCurrency($cart->tax) ?></span>
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
        <?php if ($items): ?>
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
    'header' => Html::tag('h3', 'Checkout Details'),
    'id' => 'checkout-modal',
    'size' => 'modal-lg',
]) ?>
    <div class="modal-first">
        <div>
            <span class="lighter-text"><strong>Total:</strong></span>
            <span class="main-color-text total"><?= Yii::$app->formatter->asCurrency($cart->total + $cart->tax) ?></span>
        </div>
        <div>
            <button class="btn btn-sm btn-success add-customer" data-toggle="modal" data-target="#customer-modal"><i
                        class="fa fa-plus"></i> Add Customer
            </button>
        </div>
    </div>
    <div class="cart">
        <ul class="cartWrap">
            <?php $idx = 0;
            foreach ($items as $item): ?>
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
        <div class="text-right">
            <?= Html::a('Create Order', ['/cart/checkout'], [
                'class' => 'btn btn-lg btn-primary create-order',
                'data-href' => Url::to(['/cart/checkout']),
            ]) ?>
        </div>
    </div>
    <div class="payment-section">
        <label for="payment-type">Payment Type</label>
        <?= Html::radioList('payment-type', null, [0 => 'Cash', 1 => 'Credit Card'], ['id' => 'payment-type']) ?>
    </div>
    <div class="customer-info">
        <?php if ($customer): ?>
            <div>
                <span class="lighter-text"><strong>Customer:</strong></span>
                <span class="main-color-text"><?= $customer->firstname ?> <?= $customer->lastname ?></span>
            </div>
            <div>
                <span class="lighter-text"><strong>Email receipt to:</strong></span>
                <span class="main-color-text"><?= $customer->email ?></span>
            </div>
        <?php endif; ?>
    </div>
<?php Modal::end() ?>

    <!--Find customer-->
<?php Modal::begin([
    'header' => Html::tag('h3', 'Customer Search'),
    'id' => 'customer-modal',
    'size' => 'modal-md',
]) ?>
    <div class="modal-first">
        <?= Html::beginForm(['/customer/search'], 'POST', ['class' => 'form-horizontal search_customer-form']) ?>
        <div class="form-group">
            <div class="col-sm-8 col-sm-offset-1" style="padding-right: 0">
                <?= Html::input('text', 'query', '', ['class' => 'form-control', 'autocomplete' => 'off']) ?>
            </div>
            <div class="col-sm-3" style="padding-left: 0">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?= Html::endForm() ?>
    </div>
    <div class="modal-second">
        <table class="table found-customers" style="display: none">
            <thead>
            <tr>
                <th>First</th>
                <th>Last</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Date Added</th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
<?php Modal::end() ?>

    <!--Add customer-->
<?php Modal::begin([
    'header' => Html::tag('h3', 'Add Customer'),
    'id' => 'add-customer-modal',
    'size' => 'modal-md',
]) ?>
<?php $customerModel = new \frontend\models\CreateCustomerForm();
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


    <!--payment type-->
<?php Modal::begin([
    'header' => Html::tag('h3', 'Payment'),
    'id' => 'payment-modal',
    'size' => 'modal-md',
]) ?>
    <div class="modal-first">
        <div class="total"></div>
        <?php Html::beginForm(['/cart/set-payment'], 'post', [
            'class' => 'set-payment-form'
        ]) ?>
        <div class="total-charged">
            <?= Html::input('number', 'total-charged', 0, [
                'min' => 1,
                'step' => 'any',
                'class' => 'form-control'
            ]) ?>
        </div>
        <div class="credit-cards">
            <div class="credit-card">
                <label for="credit-card-type">Credit Card Type</label>
                <?= Html::radioList('credit-card-type', null, [0 => 'visa', 1 => 'master card'], ['id' => 'credit-card-type']) ?>
            </div>
        </div>
        <div class="last-digits">
            <label for="last-digits">Last 4 Digits</label>
            <?= Html::input('text', 'last-digits', null, [
                'maxlength' => 4,
                'placeholder' => '0000',
                'class' => 'form-control',
                'id' => 'last-digits',
            ]) ?>
        </div>
        <?= Html::submitButton('OK', ['class' => 'btn btn-md btn-primary']) ?>
        <?php Html::endForm() ?>
    </div>
<?php Modal::end() ?>

<?php $this->registerJsFile('@web/js/shopping_cart.js', [
    'depends' => [\yii\web\JqueryAsset::class]
]) ?>