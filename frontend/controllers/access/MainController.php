<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-24
 * Time: 1:56 PM
 */

namespace frontend\controllers\access;


use common\components\AccessRule;
use yii\filters\{AccessControl, VerbFilter};
use yii\web\Controller;

class MainController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRule::class,
                ],
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }
}