<?php

use yii\helpers\{Html, Url};
use yiister\gentelella\widgets\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\InventorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $location \common\models\Location */

$this->title = 'Inventory Management - ' . $location->name . (!$location->status ? ' (disabled)' : '');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['id' => 'inventory-list']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Inventory', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => \yii\grid\SerialColumn::class],

            [
                'attribute' => 'category',
                'value' => 'product.category.name'
            ],
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

            'quantity',

            [
                'class' => \yii\grid\ActionColumn::class,
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>',
                            Url::to(['/inventory/update', 'id' => $key, 'location' => Yii::$app->request->get('id')])
                        );
                    }
                ],
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
