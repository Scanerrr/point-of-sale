<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use common\models\{InventoryLog, User};
use yiister\gentelella\widgets\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\InventoryLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inventory Logs - ' . $location->name . (!$location->status ? ' (disabled)' : '');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin() ?>
    <?= $this->render('/partials/_search', ['model' => $searchModel]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function (InventoryLog $model) {
            if ($model->quantity < 0) {
                return ['class' => 'danger'];
            } else {
                return ['class' => 'success'];
            }
        },
        'columns' => [
            'created_at',
            [
                'attribute' => 'barcode',
                'value' => 'product.barcode'
            ],
            [
                'attribute' => 'product',
                'value' => 'product.name'
            ],
            [
                'attribute' => 'size',
                'value' => 'product.size'
            ],
            [
                'attribute' => 'user_id',
                'value' => 'user.name',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'user_id',
                    'data' => User::find()->select('name')->indexBy('id')->column(),
                    'value' => $searchModel->user_id,
                    'theme' => Select2::THEME_DEFAULT,
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => 'Select User ...'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
            ],

            'quantity',
            'comment',
        ],
    ]); ?>
    <?php Pjax::end() ?>
</div>
