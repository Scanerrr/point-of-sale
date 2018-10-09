<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 10/9/2018
 * Time: 1:22 PM
 */

use yii\helpers\Html;
use common\models\PaymentMethod;

?>
<div class="form-group">
    <label for="">Total Charged</label>
    <?= Html::textInput('payment_amount', $total, [
        'type' => 'number',
        'step' => 'any',
        'min' => 0,
        'max' => $total,
        'class' => 'form-control'
    ]) ?>
</div>

<div class="form-group">
    <label for="">Credit Card Type</label>
    <?= Html::dropDownList('payment_method', null,
        PaymentMethod::find()
            ->select('name')
            ->where(['type_id' => PaymentMethod::TYPE_CREDIT_CARD])
            ->orderBy('name')
            ->indexBy('id')
            ->column(),
        ['class' => 'form-control']
    ) ?>
</div>

<div class="form-group">
    <label for="">Last 4 digits</label>
    <?= Html::textInput('payment_card_number', null, [
        'class' => 'form-control',
        'placeholder' => '0000',
        'maxlength' => 4
    ]) ?>
</div>
