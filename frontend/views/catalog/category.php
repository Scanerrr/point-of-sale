<?php
/* @var $this yii\web\View */
/* @var $categories \common\models\Category */
/* @var $model \frontend\models\AddToCartForm */

/* @var $products \common\models\Product */

use yii\helpers\Html;
use Scanerrr\Image;
use kartik\popover\PopoverX;
use yii\bootstrap\ActiveForm;

?>
    <div class="catalog-category">
        <?php if (!$categories && !$products): ?>
            Category <?= Yii::$app->request->get('id') ?> is empty
        <?php endif; ?>
        <?php if ($categories): ?>
            <div class="page-header">
                <h2>Sub Categories</h2>
            </div>
            <div class="cards">
                <?php foreach ($categories as $category): ?>
                    <a href="<?= \yii\helpers\Url::to(['catalog/category', 'id' => $category->id]) ?>">
                        <div class="card" data-toggle="">
                            <div class="card-header"><?= $category->name ?></div>
                            <div class="card-main">
                                <?= Html::img($category->image ? Image::resize($category->imageUrl, 120) : null, [
                                    'width' => 120,
                                    'class' => 'card-main-thumb'
                                ]) ?>
                                <div class="card-main-description"><?= $category->name ?></div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($products): ?>
            <div class="page-header">
                <h2>Products in category</h2>
            </div>
            <div class="cards">
                <?php foreach ($products as $product): ?>
                    <div class="card">
                        <div class="card-header"><?= $product->name ?></div>
                        <div class="card-main">
                            <?= Html::img($product->image ? Image::resize($product->imageUrl, 120) : null, [
                                'width' => 120,
                                'class' => 'card-main-thumb'
                            ]) ?>
                            <?php PopoverX::begin([
                                'header' => Html::tag('h3', 'Product: ' . $product->name),
                                'type' => PopoverX::TYPE_PRIMARY,
                                'size' => PopoverX::SIZE_LARGE,
                                'toggleButton' => ['label' => 'Add to Cart', 'class' => 'btn btn-primary'],
                            ]) ?>
                            <div class="card-main-description">

                                <div class="product">
                                    <div class="product-info">
                                        <div class="info-barcode">
                                            <i class="fa fa-barcode"></i> <?= $product->barcode ?>
                                        </div>
                                        <?php if ($product->size): ?>
                                            <div class="info-size">
                                                <i class="fa fa-text-height"></i> <?= $product->size ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php $form = ActiveForm::begin() ?>

                                    <?= $form->field($model, 'productId')->hiddenInput(['value' => $product->id])->label(false) ?>

                                    <div class="row">
                                        <div class="col-sm-8">
                                            <?= $form->field($model, 'discount')->textInput([
                                                'type' => 'number',
                                                'min' => 0,
                                                'max' => $product->markup_price,
                                                'step' => 'any',
                                            ]) ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?= $form->field($model, 'discountType')->dropDownList($model::DISCOUNT_TYPES, [
                                                'data-markup-price' => $product->markup_price,
                                                'class' => 'discount-select form-control'
                                            ]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <?= $form->field($model, 'price')->textInput([
                                                'type' => 'number',
                                                'min' => 0,
//                                            'max' => $product->markup_price,
                                                'value' => $product->markup_price,
                                                'step' => 'any',
                                            ]) ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?= $form->field($model, 'quantity')->textInput(['type' => 'number', 'min' => 1]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h5 style="margin-top: 10px">Price per item: <span
                                                        class="total-price">$<?= $product->markup_price ?></span></h5>
                                        </div>
                                        <div class="col-sm-6 text-right">
                                            <?= Html::submitButton('Add', ['class' => 'btn btn-primary']) ?>
                                        </div>
                                    </div>

                                    <?php ActiveForm::end() ?>

                                </div>

                            </div>
                            <?php PopoverX::end() ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php
$script = <<< 'JS'

$('#addtocartform-discounttype').on('change', e => {
    e.preventDefault()
    const $this = $(e.target)
    const discountInput = $('#addtocartform-discount')
    const productPrice = $this.data('markup-price')
    switch ($this.val()) {
        case 'percent':
            discountInput.attr('max', 100)
            break;
        case 'currency':
            discountInput.attr('max', productPrice)
            break;
        default:
            break;
    }
    if (!discountInput.val()) return;
    discountInput.trigger('change')
})

$('#addtocartform-discount').on('change', e => {
    e.preventDefault()
    const $this = $(e.target)
    const discountTypeInput = $('#addtocartform-discounttype')
    const priceInput = $('#addtocartform-price')
    const totalPriceSpan = $('h5 .total-price')
    const productPrice = discountTypeInput.data('markup-price')
    
    const newPrice = getDiscountPrice(productPrice, discountTypeInput.val())
    
    priceInput.val(newPrice)
    totalPriceSpan.text('$' + newPrice)
})

$('#addtocartform-price').on('change', e => {
    e.preventDefault()
    const $this = $(e.target)
    const discountTypeInput = $('#addtocartform-discounttype')
    const discountInput = $('#addtocartform-discount')
    const totalPriceSpan = $('h5 .total-price')
    const productPrice = discountTypeInput.data('markup-price')
    const newPrice = $(e.target).val()
    
    if (newPrice <= productPrice) {
        discountTypeInput.val('currency')
        discountInput.val((productPrice - newPrice).toFixed(2))
    }
    
    totalPriceSpan.text('$' + newPrice);
})

function getDiscountPrice(price, discountType) {
    const discount = $('#addtocartform-discount').val()
    
    if (!discount) return price;
    
    switch (discountType) {
        case 'percent':
            price -= (price * discount) / 100
            break;
        case 'currency':
            price -= discount
            break;
        default:
            break;
    }
    return price.toFixed(2)
}
JS;
$this->registerJs($script);