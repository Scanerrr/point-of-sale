<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\grid\GridView;
use yii\widgets\Pjax;
use common\models\Region;
use kartik\select2\Select2;
use common\models\Location;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\LocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Locations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Location', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'prefix',
            'name',
            [
                'label' => 'Region',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'region_id',
                    'data' => Region::find()->select('name')->indexBy('id')->column(),
                    'value' => $searchModel->region_id,
                    'theme' => Select2::THEME_DEFAULT,
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => 'Select Region'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]),
                'attribute' => 'region.name',
            ],
            'email:email',
            //'phone',
            //'country',
            //'state',
            //'city',
            //'address',
            //'zip',
            //'tax_rate',
            [
                'filter' => Html::dropDownList('LocationSearch[status]', $searchModel->status, ['' => 'All', Location::STATUS_ACTIVE => 'Active', Location::STATUS_DELETED => 'Disabled'], ['class' => 'form-control']),
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status === Location::STATUS_ACTIVE ? 'Active' : 'Disabled';
                }
            ],
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
