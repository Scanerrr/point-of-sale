<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $user User */
/* @var $model \backend\models\EmployeeSalaryForm */

$this->title = $user->name;
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

    <?php $form = ActiveForm::begin() ?>

    <!-- COMMISSION STEPS -->
    <div>
        <label class="control-label">
            <input type="checkbox" name="commission[steps]"> Commission Steps
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
            <tr>
                <td></td>
                <td><input type="text" value="0" disabled></td>
                <td><input name="commission[steps][0][to]" type="number" min="0" max="99999" step="any"></td>
                <td><input name="commission[steps][0][rate]" type="number" min="0" max="100" step="any"></td>
            </tr>
            <tr>
                <td>
                    <label>
                        <input type="checkbox" name="commission[steps][1]"> Step 2
                    </label>
                </td>
                <td><input type="text" disabled></td>
                <td><input name="commission[steps][1][to]" type="number" min="0" max="99999" step="any"></td>
                <td><input name="commission[steps][1][rate]" type="number" min="0" max="100" step="any"></td>
            </tr>
            </tbody>
        </table>
    </div>

    <!-- SALARY HOURLY -->
    <div>
        <label class="control-label">
            <input type="checkbox" name="salary[hourly]"> Hourly
        </label>

        <input type="number" min="0" max="99999" name="salary[hourly][rate]">

        <label class="control-label">
            <input type="checkbox" name="salary[hourly][break]"> Do not include breaks in total hours count
        </label>
    </div>

    <!-- COMMISSION FLAT -->
    <div>
        <label class="control-label">
            <input type="checkbox" name="commission[flat]"> Flat commission
        </label>

        <input type="number" min="0" max="100" step="any" name="commission[flat][rate]">
    </div>

    <!-- COMMISSION PRODUCT -->
    <div>
        <label class="control-label">
            <input type="checkbox" name="commission[products]"> Products commission
        </label>
    </div>

    <!-- COMMISSION or Hourly -->
    <div>
        <label class="control-label">
            <input type="checkbox" name="commission[compare]"> Higher of Commission or Hourly
        </label>
    </div>

    <!-- SALARY BASE -->
    <div>
        <label class="control-label">
            <input type="checkbox" name="salary[base]"> Base Salary
        </label>

        <label for="salary_base_rate" class="control-label">$</label>
        <input id="salary_base_rate" type="number" min="0" max="999999" name="salary[base][rate]">

        <label for="salary_added" class="control-label">Added To Salary</label>
        <select id="salary_added" name="salary[base][added]">
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
        </select>

        <label for="salary_on" class="control-label">on</label>
        <select id="salary_on" name="salary[base][on]">
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
            <?= Html::button('Copy', ['class' => 'btn btn-sm btn-primary']) ?>
        </div>

    </div>
</div>
