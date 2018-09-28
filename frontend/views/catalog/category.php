<?php
/* @var $this yii\web\View */

/* @var $categories \common\models\Category */

/* @var $products \common\models\Product */

use yii\helpers\Html;
use Scanerrr\Image;
use kartik\popover\PopoverX;
use yii\bootstrap\ActiveForm;

?>
<div class="catalog-category">
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
                            <!--                            --><?php //if ($category->image): ?>
                            <?= Html::img(Image::resize($category->imageUrl, 120), [
                                'width' => 120,
                                'class' => 'card-main-thumb'
                            ]) ?>
                            <!--                            --><?php //endif; ?>
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
                <div class="card" data-toggle="">
                    <div class="card-header"><?= $product->name ?></div>
                    <div class="card-main">
                        <?= Html::img(Image::resize($product->imageUrl, 120), [
                            'width' => 120,
                            'class' => 'card-main-thumb'
                        ]) ?>
                        <?php PopoverX::begin([
                            'header' => Html::tag('h2', 'Add an Item'),
                            'footer' => Html::button('Add', ['class' => 'btn btn-primary']) .
                                Html::button('Cancel', ['class' => 'btn btn-default']),
                            'type' => PopoverX::TYPE_PRIMARY,
                            'size' => PopoverX::SIZE_LARGE,
                            'toggleButton' => ['class' => 'btn btn-primary'],
                        ]) ?>
                        <div class="card-main-description">

                            <div class="product">
                                <div class="product-img">
                                    <?= Html::img(Image::resize($product->imageUrl, 120), [
                                        'width' => 120,
                                        'class' => 'card-main-thumb'
                                    ]) ?>
                                </div>
                                <div class="product-info">
                                    <div class="info-barcode">
                                        <?= $product->barcode ?>
                                    </div>
                                    <div class="info-name">
                                        <?= $product->name ?>
                                    </div>
                                </div>
                                <div class="product-actions">
                                    <button class="btn btn-default btn-sm">Discount</button>
                                    <button class="btn btn-default btn-sm">Discount</button>
                                </div>
                                <?= $product->size ?>
                                <?php ActiveForm::begin() ?>
                                    <?= Html::input('number', 'price', $product->markup_price, ['min' => 0, 'step' => 'any']) ?>
                                    <?= Html::input('number', 'quantity', 1, ['min' => 1]) ?>
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
