 <?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Supplier */
/* @var $form yii\widgets\ActiveForm */

 $options = [
     'template' => "{label}\n<div class='col-md-6 col-sm-6 col-xs-12'>{input}</div>\n{hint}\n{error}",
     'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
     'inputOptions' => ['class' => 'form-control col-md-7 col-xs-12']
 ];
?>

<div class="supplier-form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-label-left']]); ?>

    <?= $form->field($model, 'name', $options)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email', $options)->textInput(['maxlength' => true]) ?>

    <div class="col-md-7 col-xs-12 col-sm-offset-3">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
