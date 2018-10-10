<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\{Location, Product};

/* @var $this yii\web\View */
/* @var $model common\models\Inventory */
/* @var $form yii\widgets\ActiveForm */

$locationId = Yii::$app->request->get('id');
$options = [
    'template' => "{label}\n<div class='col-md-6 col-sm-6 col-xs-12'>{input}</div>\n{hint}\n{error}",
    'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
    'inputOptions' => ['class' => 'form-control col-md-7 col-xs-12']
];
?>

<div class="inventory-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-label-left']]); ?>

    <?php $model->location_id = $locationId ?>

    <?= $form->field($model, 'location_id', $options)->widget(Select2::class, [
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

    <?= $form->field($model, 'product_id', $options)->widget(Select2::class, [
        'data' => Product::find()
            ->select('name')
            ->orderBy('name')
            ->indexBy('id')
            ->column(),
        'options' => [
            'placeholder' => 'Select a product ...',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'quantity', $options)->textInput() ?>

    <div class="col-md-7 col-xs-12 col-sm-offset-3">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
