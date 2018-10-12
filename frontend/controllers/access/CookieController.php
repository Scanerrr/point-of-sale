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
                        'matchCallback' => function () {
                            // allow only if user is assigned to location
                            $locationId = Yii::$app->session->get('user.location');
                            if (!$locationId) return false;

                            $location = Location::find()->where(['id' => $locationId])->active()->one();
                            if (!$location) return false;

                            $locationUser = $location->getLocationUsers()->forUser(Yii::$app->user->id)->one();
                            if (!$locationUser) return false;

                            Yii::$app->params['location'] = $location;
                            Yii::$app->params['location_user'] = $locationUser;

                            return true;
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