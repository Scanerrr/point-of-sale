<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\grid\GridView;
use yii\widgets\Pjax;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            'email:email',
            'name',

            [
                'filter' => Html::dropDownList('UserSearch[role]', $searchModel->role, [
                    '' => 'All',
                    User::ROLE_ADMIN => 'Admin',
                    User::ROLE_MANAGER => 'Manager',
                    User::ROLE_USER => 'User'
                ], ['class' => 'form-control']),
                'attribute' => 'role',
                'value' => function($model) {
                    $role = '';
                    if ($model->role === User::ROLE_ADMIN) $role = 'Admin';
                    elseif ($model->role === User::ROLE_MANAGER) $role = 'Manager';
                    elseif ($model->role === User::ROLE_USER) $role = 'User';
                    return $role;
                }
            ],
            [
                'filter' => Html::dropDownList('UserSearch[status]', $searchModel->status, [
                    '' => 'All',
                    User::STATUS_ACTIVE => 'Active',
                    User::STATUS_DELETED => 'Disabled'
                ], ['class' => 'form-control']),
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status === User::STATUS_ACTIVE ? 'Active' : 'Disabled';
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'details' => function ($url) {
                        return Html::a('<span class="glyphicon glyphicon-user"></span>', $url, [
                            'title' => 'Details',
                            'aria-label' => 'Details',
                        ]);
                    }
                ],
                'template' => '{details} {update} {delete}'
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
