<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-21
 * Time: 3:15 PM
 */

namespace frontend\controllers;


use common\models\Location;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class LocationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
//                        'actions' => ['login', 'error'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
//                    [
//                        'actions' => ['logout', 'index'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
//                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex(int $id)
    {
        $location = Location::findOne($id);

        if (!$location) throw new NotFoundHttpException('Location not found!');

        return $this->render('index', [
            'location' => $location
        ]);
    }
}