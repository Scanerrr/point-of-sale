<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-24
 * Time: 1:56 PM
 */

namespace frontend\controllers\access;


use common\components\AccessRule;
use Yii;
use yii\base\Module;
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
                        'actions' => ['login', 'reset-password', 'request-password-reset', 'error'],
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

    protected function _clear()
    {
        Yii::$app->session->remove('payment');
        Yii::$app->session->remove('customer');
        Yii::$app->cart->clear();
    }
}