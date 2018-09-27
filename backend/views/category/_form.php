<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Category;
use kartik\select2\Select2;
use Scanerrr\Image;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */

$options = [
    'template' => "{label}\n<div class='col-md-6 col-sm-6 col-xs-12'>{input}</div>\n{hint}\n{error}",
    'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
    'inputOptions' => ['class' => 'form-control col-md-7 col-xs-12']
];
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-label-left']]); ?>

    <?= $form->field($model, 'parent_id', $options)->widget(Select2::class, [
        'data' => Category::find()->select('name')->indexBy('id')->column(),
        'options' => ['placeholder' => 'Select a parent category ...'],
        'theme' => Select2::THEME_DEFAULT,
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'name', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imageFile', $options)->fileInput([
        'class' => 'form-control avatar-input',
    ]) ?>
    <div class="image-preview col-md-7 col-xs-12 col-sm-offset-3">
        <?= Html::img(Image::resize($model->imageUrl), ['width' => 150]) ?>
    </div>

    <?= $form->field($model, 'status', $options)->dropDownList([
        Category::STATUS_ACTIVE => 'Active',
        Category::STATUS_DELETED => 'Disable'
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