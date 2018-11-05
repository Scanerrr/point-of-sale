<?php

use common\models\User;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\{Html, Url};

/* @var $this yii\web\View */
/* @var $user User */
/* @var $model \backend\models\EmployeeSalaryForm */

$this->title = 'Salary settings';
$salary = $user->salarySettings;

$options = [
    'template' => "{label}\n<div class='col-md-6 col-sm-6 col-xs-12'>{input}</div>\n{hint}\n{error}",
    'labelOptions' => ['class' => 'control-label col-md-3 col-sm-3 col-xs-12'],
    'inputOptions' => ['class' => 'form-control col-md-7 col-xs-12']
];
?>
    <div class="user-details">

        <h1><?= Html::encode($this->title) ?></h1>
        <h2><?= Html::encode($user->name) ?></h2>

        <?php $form = ActiveForm::begin(['options' => ['class' => 'salary-setting-form form-horizontal form-label-left']]) ?>

        <!-- SALARY HOURLY -->
        <?php $hourly = $salary->hourly ?>
        <div>
            <?php $model->hourlyStatus = $hourly['status'] ?>
            <?= $form->field($model, 'hourlyStatus', $options)->checkbox() ?>

            <?php $model->hourlyRate = $hourly['rate'] ?>
            <?= $form->field($model, 'hourlyRate', $options)->textInput([
                'type' => 'number',
                'min' => '0',
                'max' => '99999'
            ]) ?>

            <?php $model->hourlyRate = $hourly['include_break'] ?>
            <?= $form->field($model, 'hourlyIncludeBreaks', $options)->checkbox() ?>

        </div>

        <!-- COMMISSION FLAT -->
        <?php $flat = $salary->flat ?>
        <div>
            <?php $model->flatStatus = $flat['status'] ?>
            <?= $form->field($model, 'flatStatus')->checkbox() ?>

            <?php $model->flatRate = $flat['rate'] ?>
            <?= $form->field($model, 'flatRate')->textInput([
                'type' => 'number',
                'step' => 'any'
            ]) ?>
        </div>

        <!-- COMMISSION PRODUCT -->
        <?php $product = $salary->product ?>
        <div>
            <?php $model->productStatus = $product['status'] ?>
            <?= $form->field($model, 'productStatus')->checkbox() ?>
        </div>

        <!-- COMMISSION or Hourly -->
        <?php $productOrCommission = $salary->product ?>
        <div>
            <?php $model->productOrCommissionStatus = $productOrCommission['status'] ?>
            <?= $form->field($model, 'productOrCommissionStatus')->checkbox() ?>
        </div>

        <!-- SALARY BASE -->
        <?php $base = $salary->base ?>
        <div>
            <?php $model->baseStatus = $base['status'] ?>
            <?= $form->field($model, 'baseStatus')->checkbox() ?>

            <?php $model->baseRate = $base['rate'] ?>
            <?= $form->field($model, 'baseRate')->textInput(['type' => 'number']) ?>

            <?php $model->baseAdded = $base['added'] ?>
            <?= $form->field($model, 'baseAdded')->dropDownList([
                'weekly' => 'Weekly',
                'monthly' => 'Monthly'
            ]) ?>

            <?php $model->baseAddedOn = $base['added_on'] ?>
            <?= $form->field($model, 'baseAddedOn')->dropDownList([
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
                7 => 'Sunday',
            ]) ?>
        </div>

        <div>
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <!-- COPY SALARY SETTINGS -->
                <div>
                    <label class="control-label">Copy salary information from:</label>
                    <?= Select2::widget([
                        'name' => 'user_settings',
                        'data' => User::find()->select('name')->orderBy('name')->indexBy('id')->column(),
                        'theme' => Select2::THEME_DEFAULT,
                        'options' => [
                            'placeholder' => 'Select a user ...',
                        ],
                    ]) ?>
                    <?= Html::button('Copy', ['class' => 'btn btn-sm btn-primary copy-salary-info']) ?>
                </div>

            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
<?php
// TODO: finish copying of settings
$copyUrl = Url::to(['/employee/copy']);
$script = <<< JS
$('.copy-salary-info').on('click', e => {
    e.preventDefault()
    const id = $('[name=user_settings]').val()
    if (!id) return;
    $.ajax({
        type: 'POST',
        url: '$copyUrl',
        data: {id: id},
        dataType: 'json'
    })
        .done(({base, flat, product, hourly}) => {
            clearForm($('.salary-setting-form').get(0))
            
            if (flat && flat.rate) {
                $('[name=flat]').prop('checked', true)
                $('[name="flat[rate]"]').val(flat.rate)
            }

            if (product) {
                $('[name=product]').prop('checked', true)
            }

            if (base && base.rate) {
                $('[name=base]').prop('checked', true)
                $('[name="base[rate]"]').val(base.rate)
                $('[name="base[added]"]').val(base.added)
                $('[name="base[on]"]').val(base.on)
            }

            if (hourly && hourly.rate) {
                $('[name=hourly]').prop('checked', true)
                $('[name="hourly[rate]"]').val(hourly.rate)
                $('[name="hourly[notIncludeBreaks]"]').val(hourly.notIncludeBreaks)
            }
        })
        .fail(err => console.error(err.responseText))
})
function clearForm(form) {

    form.reset();
    
    Array.from(form.elements).forEach(el => {
        const fieldType = el.type.toLowerCase();
        
        switch(fieldType) {
        
            case 'text':
            case 'number':
            case 'password':
            case 'textarea':
            // case "hidden":
                el.value = '';

                break;
            
            case 'radio':
            case 'checkbox':
                if (el.checked) {
                  el.checked = false;
                }
                break;
            
            case 'select-one':
            case 'select-multi':
                el.selectedIndex = -1;
                break;
            
            default:
                break;
        }
    })
}
JS;
$this->registerJs($script);