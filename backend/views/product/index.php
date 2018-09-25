<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\grid\GridView;
use yii\widgets\Pjax;
use common\models\Product;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
//            TODO: fix filter for currency
            'cost_price:currency',
            'markup_price:currency',
            //'max_price',
            //'tax',
            //'commission_policy_id',
            //'image',
            //'barcode',
            //'size',
            //'sku',
            [
                'filter' => Html::dropDownList('ProductSearch[status]', $searchModel->status, [
                    '' => 'All',
                    Product::STATUS_ACTIVE => 'Active',
                    Product::STATUS_DELETED => 'Disabled'
                ], ['class' => 'form-control']),
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status === Product::STATUS_ACTIVE ? 'Active' : 'Disabled';
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
