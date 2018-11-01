<?php
/* @var $this yii\web\View */
/* @var $categories \common\models\Category */
/* @var $model \frontend\models\AddToCartForm */

/* @var $products \common\models\Product */

use yii\helpers\{Html, Url};
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
                                    <div class="barcode">
                                        <i class="fa fa-barcode"></i> <?= $product->barcode ?>
                                    </div>
                                    <?php if ($product->size): ?>
                                        <div class="size">
                                            <i class="fa fa-text-height"></i> <?= $product->size ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="exchange">
                                        <?php $form = ActiveForm::begin(['action' => Url::to(['catalog/report-damaged', 'id' => $product->id])]) ?>
                                        <?= Html::hiddenInput('quantity', 1, ['class' => 'hidden-quantity-input']) ?>
                                        <?= Html::submitButton('Report as damaged', ['class' => 'btn btn-danger btn-sm']) ?>
                                        <?php ActiveForm::end() ?>
                                    </div>
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
                                            'class' => 'discount-input form-control'
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
                                            'value' => $product->markup_price,
                                            'step' => 'any',
                                            'class' => 'price-input form-control'
                                        ]) ?>
                                    </div>
                                    <div class="col-sm-4">
                                        <?= $form->field($model, 'quantity')->textInput([
                                            'type' => 'number',
                                            'min' => 1,
                                            'class' => 'quantity-input form-control'
                                        ]) ?>
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