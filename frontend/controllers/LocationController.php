<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-21
 * Time: 3:15 PM
 */

namespace frontend\controllers;


use Yii;
use yii\helpers\VarDumper;
use yii\web\{ForbiddenHttpException, NotFoundHttpException, Response};
use frontend\controllers\access\CookieController;
use common\models\{Category, Location, LocationUser, LocationWorkHistory, User};

class LocationController extends CookieController
{

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs']['actions']['change-status'] = ['post'];
        $behaviors['verbs']['actions']['clock'] = ['post'];
        return $behaviors;
    }

    /**
     * Displays homepage.
     *
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        $location = Yii::$app->params['location'];

        if (!$location) throw new ForbiddenHttpException('Location not found!');

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

        return $this->asJson(['error' => []]);
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
        /* @var $locationUser LocationUser */
        $user = Yii::$app->user->identity;
        $locationUser = $user->getLocationUsers()->forLocation($location->id)->one();
        $locationUser->is_working = (int) !$locationUser->is_working;

        /*TODO: redo change user to locationuser*/
        VarDumper::dump($locationUser,10,1); die();

        if (!$locationUser->save()) return $this->asJson(['error' => $locationUser->getErrors()]);
        die();

        $workedTime = null;
        if (!$locationUser->is_working) {
            $workStart = $locationUser->getLocationWorkHistories()
                ->select('created_at')
                ->forEvent(LocationWorkHistory::EVENT_WORKING)
                ->orderBy('created_at DESC')
                ->scalar();
            $now = new \DateTime();
            $interval = (new \DateTime($workStart))
                ->diff($now);
            $workedTime = $interval->format('%h Hours %i Minutes %s Seconds');
        } else {
            // check if user is clocked in from another location...
            $signedLocation = $user->getLocationWorkHistories()
                ->forEvent(LocationWorkHistory::EVENT_WORKING)
                ->orderBy('created_at DESC')
                ->one();
            if ($location->id !== $signedLocation->location_id) {
                return $this->asJson(['error' => 'User Clocked In from another location']);
            }
        }

        return $this->asJson(['error' => [], 'workedTime' => $workedTime]);
    }
}