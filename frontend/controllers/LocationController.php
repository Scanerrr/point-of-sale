<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-21
 * Time: 3:15 PM
 */

namespace frontend\controllers;


use Yii;
use yii\web\{NotFoundHttpException, Response};
use frontend\controllers\access\MainController;
use common\models\{Category, Location, LocationWorkHistory, User};

class LocationController extends MainController
{

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
        if ($location->id !== $session->get('user.location')) Yii::$app->cart->clear();;

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
    protected function findLocationModel(int $id = null): Location
    {
        if (!$id) $id = Yii::$app->session->get('user.location');

        if (($model = Location::find()->where(['id' => $id])->active()->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionChangeStatus(): Response
    {
        $location = $this->findLocationModel();

        $location->is_open = (int) !$location->is_open;

        if (!$location->save()) return $this->asJson(['error' => $location->getErrors()]);

//        $error = LocationWorkHistory::saveHistory($location->id, Yii::$app->user->id, $location->is_open);

        return $this->asJson(['error' => [], 'status' => $location->is_open]);
    }

    /**
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionClock(): Response
    {
        $location = $this->findLocationModel();

        if (!$location->is_open) return $this->asJson(['error' => 'Location must be open to work there']);

        /* @var $user User */
        $user = Yii::$app->user->identity;
        $user->is_working = (int) !$user->is_working;

        if (!$user->save()) return $this->asJson(['error' => $user->getErrors()]);

//        $error = LocationWorkHistory::saveHistory($location->id, $user->id, $user->is_working);

        return $this->asJson(['error' => [], 'status' => $user->is_working]);
    }
}