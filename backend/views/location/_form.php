<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Region;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Location */
/* @var $form yii\widgets\ActiveForm */
$options = [
    'template' => "{label}\n<div class='col-md-6 col-sm-6 col-xs-12'>{input}</div>\n{hint}\n{error}",
    'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
    'inputOptions' => ['class' => 'form-control col-md-7 col-xs-12']
];
$regions = Region::find()->select('name')->indexBy('id')->column();
?>

<div class="location-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-label-left']]); ?>

    <?= $form->field($model, 'prefix', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'region_id', $options)->dropDownList($regions) ?>

    <?= $form->field($model, 'email', $options)->textInput(['type' => 'email', 'maxlength' => true]) ?>

    <?= $form->field($model, 'phone', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'country', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'state', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zip', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tax_rate', $options)->textInput(['type' => 'number', 'min' => 0, 'step' => 1, 'maxlength' => true, 'value' => 0]) ?>

    <?= $form->field($model, 'status', $options)->dropDownList([User::STATUS_ACTIVE => 'Active', User::STATUS_DELETED => 'Disable']) ?>

    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
