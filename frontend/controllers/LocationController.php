<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-21
 * Time: 3:15 PM
 */

namespace frontend\controllers;


use Yii;
use common\models\{Category, Location};
use yii\helpers\VarDumper;
use yii\web\{NotFoundHttpException, ErrorAction};
use frontend\controllers\access\MainController;

class LocationController extends MainController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
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
        $location = $this->findLocationModelForUser($id, Yii::$app->user->id);

        $session = Yii::$app->session;

        // TODO: save cart data to temp var to resurrect when open again
        if ($location->id !== $session->get('user.location')) $this->_clear();

        $session->set('user.location', $location->id);

        Yii::$app->params['location'] = $location; // set variable for cart in layout

        $this->layout = 'withCategories';

        $categories = Category::find()->active()->forParent()->all();

        return $this->render('index', [
            'location' => $location,
            'categories' => $categories
        ]);
    }

    /**
     * Finds the Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @param int $userId
     * @return Location the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findLocationModelForUser(int $id, int $userId): Location
    {
        if (($model = Location::find()->where(['id' => $id])->active()->one()) !== null) {
            if ($model->getLocationUsers()->forUser($userId)->one() === null) {
                throw new NotFoundHttpException('User doesn\'t have permissions!');
            }
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}