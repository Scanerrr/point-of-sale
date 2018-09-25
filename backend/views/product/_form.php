<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Product;
use kartik\select2\Select2;
use common\models\Category;
use common\models\Supplier;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */

$options = [
    'template' => "{label}\n<div class='col-md-6 col-sm-6 col-xs-12'>{input}</div>\n{hint}\n{error}",
    'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
    'inputOptions' => ['class' => 'form-control col-md-7 col-xs-12']
];
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-label-left']]); ?>

    <?= $form->field($model, 'category_id', $options)->widget(Select2::class, [
        'data' => Category::find()->select('name')->indexBy('id')->column(),
        'options' => ['placeholder' => 'Select a category ...'],
        'theme' => Select2::THEME_DEFAULT,
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'supplier_id', $options)->widget(Select2::class, [
        'data' => Supplier::find()->select('name')->indexBy('id')->column(),
        'options' => ['placeholder' => 'Select a supplier ...'],
        'theme' => Select2::THEME_DEFAULT,
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'name', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description', $options)->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'cost_price', $options)->textInput([
        'type' => 'number',
        'min' => 0,
        'step' => 'any',
        'maxlength' => true])->label($model->getAttributeLabel('cost_price') . ', $') ?>

    <?= $form->field($model, 'markup_price', $options)->textInput([
        'type' => 'number',
        'min' => 0,
        'step' => 'any',
        'maxlength' => true])->label($model->getAttributeLabel('markup_price') . ', $') ?>

    <?= $form->field($model, 'max_price', $options)->textInput([
        'type' => 'number',
        'min' => 0,
        'step' => 'any',
        'maxlength' => true])->label($model->getAttributeLabel('max_price') . ', $') ?>

    <?= $form->field($model, 'tax', $options)->textInput([
        'type' => 'number',
        'min' => 0,
        'step' => 'any',
        'maxlength' => true])->label($model->getAttributeLabel('tax') . ', %') ?>

    <?= $form->field($model, 'commission_policy_id', $options)->dropDownList([
        Product::COMMISSION_REGULAR => 'Commission Regular',
        Product::COMMISSION_NO => 'No Commission'
    ]) ?>

    <?= $form->field($model, 'imageFile', $options)->fileInput([
        'class' => 'form-control avatar-input',
    ]) ?>
    <div class="image-preview col-md-7 col-xs-12 col-sm-offset-3">
        <?= Html::img($model->image ? '/' . Product::UPLOAD_PATH . $model->id . '/' . $model->image : null, ['width' => 150]) ?>
    </div>

    <?= $form->field($model, 'barcode', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'size', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sku', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status', $options)->dropDownList([
        Product::STATUS_ACTIVE => 'Active',
        Product::STATUS_DELETED => 'Disable'
    ]) ?>

    <div class="col-md-7 col-xs-12 col-sm-offset-3">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJsFile('@web/js/image-preview.js', [
    'depends' => [
        \yii\web\JqueryAsset::class
    ]
]); ?>