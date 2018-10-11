<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-24
 * Time: 1:56 PM
 */

namespace frontend\controllers\access;


use Yii;
use common\components\AccessRule;
use common\models\Location;
use yii\filters\{AccessControl, VerbFilter};
use yii\helpers\VarDumper;
use yii\web\Controller;

class CookieController extends Controller
{

    public $layout = 'withCategories';

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
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            // allow only if user is assigned to location
                            if (($locationId = Yii::$app->session->get('user.location')) !== null) {
                                if (($model = Location::find()->where(['id' => $locationId])->active()->one()) !== null) {
                                    if ($model->getLocationUsers()->forUser(Yii::$app->user->id)->one() !== null) {
                                        Yii::$app->params['location'] = $model;
                                        return true;
                                    }
                                }
                            }
                            return false;
                        }
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