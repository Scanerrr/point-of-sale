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
        <?= Html::textInput('payment_amount', 0, [
            'type' => 'number',
            'step' => 'any',
            'min' => 0,
            'max' => $total + 1000,
            'class' => 'form-control'
        ]) ?>
    </div>

    <?= Html::hiddenInput('payment_method', PaymentMethod::find()->select('id')->where(['type_id' => PaymentMethod::TYPE_CASH])->scalar()) ?>

    <div class="item checkout-section-flex">
        Change Due <span class="change-due negative"><?= Yii::$app->formatter->asCurrency($total) ?></span>
    </div>
<?php

$script = <<< JS
$('input[name=payment_amount]').on('change', e => {
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    })
    const value = e.target.value
    const result = $total - value
    const formatted = formatter.format(Math.abs(result))
    const changeDueSpan = $('.change-due');
    changeDueSpan.text(formatted)

    if (result >= 0) {
        changeDueSpan.addClass('negative')
    } else {
        changeDueSpan.removeClass('negative')
    }
})
JS;

$this->registerJs($script);