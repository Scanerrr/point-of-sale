<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\grid\GridView;
use yii\widgets\Pjax;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function (Order $model) {
            if ($model->status === Order::STATUS_REFUND) {
                return ['class' => 'danger'];
            }
        },
        'columns' => [
            'id',
            [
                'filter' => Html::dropDownList('OrderSearch[status]', $searchModel->status, ['' => 'All'] + Order::statusList(), ['class' => 'form-control']),
                'attribute' => 'status',
                'value' => function (Order $model) {
                    return Order::statusName($model->status);
                },
            ],
            [
                'attribute' => 'location_id',
                'value' => 'location.name',
            ],
            [
                'attribute' => 'employee_id',
                'value' => 'employee.name',
            ],
            [
                'attribute' => 'customer_id',
                'value' => 'customer.fullName',
            ],
            'total:currency',
            'created_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {delete}'
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
