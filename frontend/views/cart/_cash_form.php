<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 10/9/2018
 * Time: 1:22 PM
 */

use yii\helpers\Html;

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

    <div>Change Due <span class="change-due"><?= $total ?></span></div>
<?php

$script = <<< JS
$('input[name=payment_amount]').on('change', e => {
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    })
    const value = e.target.value
    const result = formatter.format($total - value)
    $('.change-due').text(result)
})
JS;

$this->registerJs($script);