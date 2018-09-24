<?php /** @noinspection PhpUnhandledExceptionInspection */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Region;
use common\models\Location;
use common\models\User;
use common\models\LocationUser;
use kartik\select2\Select2;

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

    <?= $form->field($model, 'region_id', $options)->dropDownList($regions, ['prompt' => 'Select Region']) ?>

    <?= $form->field($model, 'email', $options)->textInput(['type' => 'email', 'maxlength' => true]) ?>

    <?= $form->field($model, 'phone', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'country', $options)->dropDownList([
        'USA' => 'USA',
        'Not USA' => 'Not USA'],
        ['prompt' => 'Select Country']) ?>

    <?= $form->field($model, 'state', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zip', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tax_rate', $options)
            ->textInput([
                'type' => 'number',
                'min' => 0,
                'step' => 'any',
                'value' => $model->tax_rate ?? 0,
                'maxlength' => true])
            ->label($model->getAttributeLabel('tax_rate') . ', %') ?>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">Employees</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <?= Select2::widget([
                'name' => 'employees',
                'value' => $model->id
                    ? LocationUser::find()->select('user_id')->where(['location_id' => $model->id])->indexBy('id')->column()
                    : Yii::$app->user->id,
                'data' => User::find()->select('name')->indexBy('id')->column(),
                'theme' => Select2::THEME_DEFAULT,
                'options' => [
                    'class' => 'form-control col-md-7 col-xs-12',
                    'placeholder' => 'Select a color ...',
                    'multiple' => true
                ],
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'status', $options)->dropDownList([
            Location::STATUS_ACTIVE => 'Active',
        Location::STATUS_DELETED => 'Disable'
    ]) ?>

    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
