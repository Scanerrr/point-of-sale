<?php
namespace frontend\controllers;

use common\models\Location;
use common\models\LocationUser;
use frontend\controllers\access\MainController;
use Yii;
use yii\base\InvalidArgumentException;
use yii\captcha\CaptchaAction;
use yii\web\{BadRequestHttpException, ErrorAction, ForbiddenHttpException};
use common\models\form\LoginForm;
use frontend\models\{PasswordResetRequestForm, ResetPasswordForm};

/**
 * Site controller
 */
class SiteController extends MainController
{

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs']['actions']['assign-location'] = ['post'];
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $locationIds = LocationUser::findAll(['user_id' => Yii::$app->user->id]);

        $locations = array_map(function ($locationId) {
            return $locationId->getLocation()->active()->one();
        }, $locationIds);

        $this->view->registerCssFile('/css/catalog.css');

        return $this->render('index', [
            'locations' => array_filter($locations)
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionAssignLocation(int $id)
    {
        $location = Location::find()->where(['id' => $id])->active()->one();

        // check if user belongs to location
        if (!$location || !$location->getLocationUsers()->forUser(Yii::$app->user->id)->one()) {
            throw new ForbiddenHttpException('User does not belong to this location');
        }

        $session = Yii::$app->session;

        if ($location->id !== $session->get('user.location')) Yii::$app->cart->clear();;

        $session->set('user.location', $location->id);

        return $this->redirect(['/location/index']);
    }
}
