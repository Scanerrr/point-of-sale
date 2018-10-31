<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Location;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model yii\base\Model */
?>

<div class="inventory-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'class' => 'search-form form-horizontal'
        ],
    ]); ?>

    <?php $model->location_id = Yii::$app->request->get('id') ?>

    <div class="row">
        <div class="col-sm-6">
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
                    'allowClear' => true,
                ],
            ])->label('Change Location') ?>

            <div class="form-group">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
