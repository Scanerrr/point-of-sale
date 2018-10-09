<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\{Location, Product};

/* @var $this yii\web\View */
/* @var $model common\models\Inventory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventory-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'location_id')->widget(Select2::class, [
        'data' => Location::find()
            ->select('name')
            ->orderBy('name')
            ->indexBy('id')
            ->column(),
        'options' => [
            'placeholder' => 'Select a location ...',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'product_id')->widget(Select2::class, [
        'data' => Product::find()
            ->select('name')
            ->orderBy('name')
            ->indexBy('id')
            ->column(),
        'options' => [
            'placeholder' => 'Select a location ...',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
