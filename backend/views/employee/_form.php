<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$options = [
    'template' => "{label}\n<div class='col-md-6 col-sm-6 col-xs-12'>{input}</div>\n{hint}\n{error}",
    'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
    'inputOptions' => ['class' => 'form-control col-md-7 col-xs-12']
];
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-label-left']]); ?>

    <?= $form->field($model, 'username', $options)->textInput([
        'maxlength' => true,
        'value' => $user->username ?? ''
    ]) ?>

    <?= $form->field($model, 'password', $options)->passwordInput(['value' => '']) ?>

    <?= $form->field($model, 'password_repeat', $options)->passwordInput(['value' => '']) ?>

    <?= $form->field($model, 'email', $options)->textInput([
        'maxlength' => true,
        'value' => $user->email ?? ''
    ]) ?>

    <?= $form->field($model, 'name', $options)->textInput([
        'maxlength' => true,
        'value' => $user->name ?? ''
    ]) ?>

    <?= $form->field($model, 'imageFile', $options)->fileInput([
        'class' => 'form-control avatar-input',
        'value' => $user->avatar ?? ''
    ]) ?>
    <div class="image-preview col-md-7 col-xs-12 col-sm-offset-3">
        <?= Html::img($user->avatar ? '/' . User::UPLOAD_PATH . $user->id . '/' . $user->avatar : null, ['width' => 150]) ?>
    </div>

    <?= $form->field($model, 'phone', $options)->textInput([
        'maxlength' => true,
        'value' => $user->phone ?? ''
    ]) ?>

    <?= $form->field($model, 'position', $options)->textInput([
        'maxlength' => true,
        'value' => $user->position ?? ''
    ]) ?>

    <?= $form->field($model, 'country', $options)->textInput([
        'maxlength' => true,
        'value' => $user->country ?? ''
    ]) ?>

    <?= $form->field($model, 'state', $options)->textInput([
        'maxlength' => true,
        'value' => $user->state ?? ''
    ]) ?>

    <?= $form->field($model, 'city', $options)->textInput([
        'maxlength' => true,
        'value' => $user->city ?? ''
    ]) ?>

    <?= $form->field($model, 'zip', $options)->textInput([
        'maxlength' => true,
        'value' => $user->zip ?? ''
    ]) ?>

    <?= $form->field($model, 'address', $options)->textInput([
        'maxlength' => true,
        'value' => $user->address ?? ''
    ]) ?>

    <?= $form->field($model, 'role', $options)->dropDownList([
        User::ROLE_ADMIN => 'Admin',
        User::ROLE_MANAGER => 'Manager',
        User::ROLE_USER => 'User',
    ], ['value' => $user->role ?? '']) ?>

    <?= $form->field($model, 'status', $options)->dropDownList([
        User::STATUS_ACTIVE => 'Active',
        User::STATUS_DELETED => 'Disable'
    ], ['value' => $user->status ?? '']) ?>

    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php $this->registerJsFile('@web/js/image-preview.js', [
    'depends' => [
        \yii\web\JqueryAsset::class
    ]
]); ?>