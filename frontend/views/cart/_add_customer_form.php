<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 10/25/2018
 * Time: 1:53 PM
 */

/* @var $model \frontend\models\CreateCustomerForm */

use frontend\models\CreateCustomerForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$model = new CreateCustomerForm();

$form = ActiveForm::begin([
    'action' => ['/customer/create'],
    'options' => ['class' => 'create_customer-form']
]) ?>
    <div class="col-sm-6">
        <?= $form->field($model, 'firstname')->textInput(['class' => 'form-control customer-firstname']) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'lastname')->textInput(['class' => 'form-control customer-lastname']) ?>
    </div>
    <div class="col-sm-12">
        <?= $form->field($model, 'email')->textInput() ?>
    </div>
    <div class="col-sm-8">
        <?= $form->field($model, 'phone')->textInput() ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'gender')->dropDownList(['male' => 'Male', 'female' => 'Female']) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'country')->textInput(['value' => $location->country]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'state')->textInput(['value' => $location->state]) ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'city')->textInput(['value' => $location->city]) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'address')->textInput(['value' => $location->address]) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'zip')->textInput(['value' => $location->zip]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Create', ['class' => 'btn btn-primary', 'name' => 'create-button']) ?>
    </div>
<?php ActiveForm::end() ?>