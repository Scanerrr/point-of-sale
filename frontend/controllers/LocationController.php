<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-21
 * Time: 3:15 PM
 */

namespace frontend\controllers;


use Yii;
use common\models\{Category, Location, LocationWorkHistory};
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
        $model = $this->findLocationModel($id);

        if ($model->getLocationUsers()->forUser($userId)->one() !== null) {
            return $model;
        }
        throw new NotFoundHttpException('User doesn\'t have permissions!');
    }

    /**
     * Finds the Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Location the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findLocationModel(int $id): Location
    {
        if (($model = Location::find()->where(['id' => $id])->active()->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionChangeStatus(int $id)
    {
        $location = $this->findLocationModel($id);

        $location->is_open = (int) !$location->is_open;

        if (!$location->save()) return $this->asJson(['error' => $location->getErrors()]);

        $locationHistory = new LocationWorkHistory();
        $locationHistory->location_id = $location->id;
        $locationHistory->user_id = Yii::$app->user->id;
        $locationHistory->event = $location->is_open;

        if (!$locationHistory->save()) return $this->asJson(['error' => $locationHistory->getErrors()]);


        return $this->asJson(['error' => false, 'status' => $location->is_open]);
    }
}