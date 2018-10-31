<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\{Location, Product, InventoryReport};

/* @var $this yii\web\View */
/* @var $model common\models\InventoryReport */
/* @var $form yii\widgets\ActiveForm */

$locationId = Yii::$app->request->get('id');
$options = [
    'template' => "{label}\n<div class='col-md-6 col-sm-6 col-xs-12'>{input}</div>\n{hint}\n{error}",
    'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
    'inputOptions' => ['class' => 'form-control col-md-7 col-xs-12']
];
?>

<div class="inventory-report-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-label-left']]); ?>

    <?php $model->location_id = $locationId ?>

    <?= $form->field($model, 'location_id', $options)->widget(Select2::class, [
        'data' => Location::find()
            ->select('name')
            ->orderBy('name')
            ->indexBy('id')
            ->column(),
        'theme' => Select2::THEME_DEFAULT,
        'options' => [
            'placeholder' => 'Select a location ...',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'product_id', $options)->widget(Select2::class, [
        'data' => Product::find()
            ->select('name')
            ->orderBy('name')
            ->indexBy('id')
            ->column(),
        'hideSearch' => true,
        'theme' => Select2::THEME_DEFAULT,
        'options' => [
            'placeholder' => 'Select a product ...',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'reason_id', $options)->widget(Select2::class, [
        'data' => InventoryReport::reasonList(),
        'theme' => Select2::THEME_DEFAULT,
        'options' => [
            'placeholder' => 'Select a reason ...',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'quantity', $options)->textInput() ?>

    <?php $model->user_id = Yii::$app->user->id ?>

    <?= Html::activeHiddenInput($model, 'user_id') ?>

    <?= $form->field($model, 'comment', $options)->textInput(['maxlength' => true]) ?>

    <div class="col-md-7 col-xs-12 col-sm-offset-3">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
