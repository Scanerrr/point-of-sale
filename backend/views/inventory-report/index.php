<?php

use yii\helpers\{Html, Url};
use yii\widgets\Pjax;
use common\models\{User, InventoryReport};
use kartik\select2\Select2;
use yiister\gentelella\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\InventoryReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inventory Reports - ' . $location->name . (!$location->status ? ' (disabled)' : '');
$this->params['breadcrumbs'][] = $this->title;
$addButton = Html::a('Add Report', ['create', 'id' => $location->id], ['class' => 'btn btn-success']);
?>
<div class="inventory-report-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?= $this->render('/partials/_search', ['model' => $searchModel]) ?>

    <p>
        <?= $addButton ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => Html::tag('p', 'No products found!', ['class' => 'text-center']) .
            Html::tag('p', $addButton, ['class' => 'text-center']),
        'columns' => [
            [
                'attribute' => 'barcode',
                'value' => 'product.barcode'
            ],
            [
                'attribute' => 'product',
                'value' => 'product.name'
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
            [
                'attribute' => 'reason_id',
                'value' => function ($model) {
                    return InventoryReport::reasonName($model->reason_id);
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'reason_id',
                    'data' => InventoryReport::reasonList(),
                    'value' => $searchModel->reason_id,
                    'theme' => Select2::THEME_DEFAULT,
                    'hideSearch' => true,
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => 'Select Reason ...'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
            ],
            'quantity',
            'comment',
            'created_at',

            [
                'class' => \yii\grid\ActionColumn::class,
                'template' => '{delete}'
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
