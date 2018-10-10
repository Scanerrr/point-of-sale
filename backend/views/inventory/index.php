<?php

use yii\widgets\Pjax;
use yii\helpers\{Html, Url};
use yii2mod\editable\EditableColumn;
use yiister\gentelella\widgets\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\InventorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $location \common\models\Location */

$this->title = 'Inventory Management - ' . $location->name . (!$location->status ? ' (disabled)' : '');
$this->params['breadcrumbs'][] = $this->title;
$locationId = Yii::$app->request->get('id');
$addButton = Html::a('Add Product', ['create', 'id' => $locationId], ['class' => 'btn btn-success']);
?>
<div class="inventory-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(['id' => 'inventory-list']) ?>
    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <p class="text-right">
        <?= $addButton ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'emptyText' => Html::tag('p', 'No products found!', ['class' => 'text-center']) .
            Html::tag('p', $addButton, ['class' => 'text-center']),
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

            [
                'class' => EditableColumn::class,
                'attribute' => 'quantity',
                'url' => ['change-quantity'],
                'value' => function ($model) {
                    return Html::tag('span', $model->quantity, [
                        'data' => [
                            'toggle' => 'tooltip',
                            'placement' => 'left',
                            'title' => 'Click to change the quantity'
                        ],
                    ]);
                }
            ],

            [
                'class' => \yii\grid\ActionColumn::class,
                'buttons' => [
                    'update' => function ($url, $model, $key) use ($locationId) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>',
                            Url::to(['/inventory/update', 'id' => $key, 'location' => $locationId])
                        );
                    }
                ],
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
