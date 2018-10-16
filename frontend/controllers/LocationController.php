<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-21
 * Time: 3:15 PM
 */

namespace frontend\controllers;


use Yii;
use frontend\controllers\access\CookieController;
use yii\web\{ForbiddenHttpException, NotFoundHttpException, Response};
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

        if (isset(Yii::$app->params['location']) && Yii::$app->params['location']->id === $id) return Yii::$app->params['location'];

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

        // Check if user is signed in another location
        if (!$locationUser->is_working) {
            $signedLocation = $user->getLocationUsers()
                ->forLocation($location->id, true)
                ->isWorking()
                ->one();

            // If signed then not clock in
            if ($signedLocation !== null) {
                return $this->asJson(['error' => 'User Clocked In from another location']);
            }
        }

        $locationUser->is_working = (int) !$locationUser->is_working;

        if (!$locationUser->save()) return $this->asJson(['error' => $locationUser->getErrors()]);

        $workedTime = null;
        if (!$locationUser->is_working) {
            $workStart = $user->getLocationWorkHistories()
                ->select('created_at')
                ->forEvent(LocationWorkHistory::EVENT_WORKING)
                ->orderBy('created_at DESC')
                ->scalar();
            $now = new \DateTime();
            $interval = (new \DateTime($workStart))
                ->diff($now);
            $workedTime = $interval->format('%h Hours %i Minutes %s Seconds');
        }

        return $this->asJson(['error' => [], 'workedTime' => $workedTime]);
    }
}