<?php

use yii\helpers\{Html, Json, Url};
use yii\widgets\ActiveForm;
use common\models\User;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $user User */

$this->title = $user->name;
$salary = Json::decode($user->salary_settings);
?>
    <div class="user-details">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Update', ['update', 'id' => $user->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $user->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?php $form = ActiveForm::begin(['options' => ['class' => 'salary-setting-form']]) ?>

        <!-- COMMISSION STEPS -->
        <div>
            <label class="control-label">
                <input type="checkbox" name="steps"> Commission Steps
            </label>
            <table>
                <thead>
                <tr>
                    <th></th>
                    <th>From ($)</th>
                    <th>To ($)</th>
                    <th>Commission (%)</th>
                </tr>
                </thead>
                <tbody>
                <tr class="step-0">
                    <td></td>
                    <td><input type="text"="0"></td>
                    <td><input name="steps[0][to]" type="number" min="0" max="99999" step="any"></td>
                    <td><input name="steps[0][rate]" type="number" min="0" max="100" step="any"></td>
                </tr>
                <tr class="step-1">
                    <td>
                        <label>
                            <input type="checkbox" name="steps][1]"> Step 2
                        </label>
                    </td>
                    <td><input type="text"></td>
                    <td><input name="steps[1][to]" type="number" min="0" max="99999" step="any"></td>
                    <td><input name="steps[1][rate]" type="number" min="0" max="100" step="any"></td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- SALARY HOURLY -->
        <div>
            <label class="control-label">
                <input type="checkbox" name="hourly" <?= $salary['hourly'] ? 'checked' : null ?>> Hourly
            </label>

            <input type="number" min="0" max="99999"
                   name="hourly[rate]" <?= isset($salary['hourly']['rate']) ? 'value="' . $salary['hourly']['rate'] . '"' : null ?>>

            <label class="control-label">
                <input type="checkbox"
                       name="hourly[notIncludeBreaks]" <?= isset($salary['hourly']['notIncludeBreaks']) && $salary['hourly']['notIncludeBreaks'] ? 'checked' : null ?>>
                Do not include breaks in total hours count
            </label>
        </div>

        <!-- COMMISSION FLAT -->
        <div>
            <label class="control-label">
                <input type="checkbox" name="flat" <?= $salary['flat'] ? 'checked' : null ?>> Flat commission
            </label>

            <input type="number" min="0" max="100" step="any"
                   name="flat[rate]" <?= isset($salary['flat']['rate']) ? 'value="' . $salary['flat']['rate'] . '"' : null ?>>
        </div>

        <!-- COMMISSION PRODUCT -->
        <div>
            <label class="control-label">
                <input type="checkbox" name="product" <?= $salary['product'] ? 'checked' : null ?>> Products commission
            </label>
        </div>

        <!-- COMMISSION or Hourly -->
        <div>
            <label class="control-label">
                <input type="checkbox" name="commissions[compare]"> Higher of Commission or Hourly
            </label>
        </div>

        <!-- SALARY BASE -->
        <div>
            <label class="control-label">
                <input type="checkbox" name="base" <?= $salary['base'] ? 'checked' : null ?>> Base Salary
            </label>

            <label for="salary_base_rate" class="control-label">$</label>
            <input id="salary_base_rate" type="number" min="0" max="999999"
                   name="base[rate]" <?= isset($salary['base']['rate']) ? 'value="' . $salary['base']['rate'] . '"' : null ?>>

            <label for="salary_added" class="control-label">Added To Salary</label>
            <select id="salary_added"
                    name="base[added]" <?= isset($salary['base']['added']) ? 'value="' . $salary['base']['added'] . '"' : null ?>>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>

            <label for="salary_on" class="control-label">on</label>
            <select id="salary_on"
                    name="base[on]" <?= isset($salary['base']['on']) ? 'value="' . $salary['base']['on'] . '"' : null ?>>
                <option value="">Monday</option>
                <option value="">Tuesday</option>
                <option value="">Wednesday</option>
                <option value="">Thursday</option>
                <option value="">Friday</option>
                <option value="">Saturday</option>
                <option value="">Sunday</option>
            </select>
        </div>

        <div>
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end() ?>

        <div class="col-sm-6">
            <!-- COPY SALARY SETTINGS -->
            <div>
                <label class="control-label">Copy salary information from:</label>
                <?= Select2::widget([
                    'name' => 'user_settings',
                    'data' => User::find()->select('name')->indexBy('id')->column(),
                    'theme' => Select2::THEME_DEFAULT,
                    'options' => [
                        'placeholder' => 'Select a user ...',
                    ],
                ]) ?>
                <?= Html::button('Copy', ['class' => 'btn btn-sm btn-primary copy-salary-info']) ?>
            </div>

        </div>
    </div>
<?php
$copyUrl = Url::to(['/employee/copy']);
$script = <<< JS
$('.copy-salary-info').on('click', e => {
    e.preventDefault()
    $.ajax({
        type: 'POST',
        url: '$copyUrl',
        data: {id: $('[name=user_settings]').val()},
        dataType: 'json'
    })
        .done(({base, flat, product, hourly}) => {
            clearForm($('.salary-setting-form').get(0))
            
            if (flat && flat.rate) {
                $('[name=flat]').prop('checked', true)
                $('[name="flat[rate]"]').val(flat.rate)
            }
            
            //todo: finish copying data 
        })
        .fail(err => console.error(err.responseText))
})
function clearForm(form) {

    form.reset();
    
    Array.from(form.elements).forEach(el => {
        const fieldType = el.type.toLowerCase();
        
        switch(fieldType) {
        
            case 'text':
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
$this->registerJs($script, $this::POS_READY);