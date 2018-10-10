<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 10/10/2018
 * Time: 6:49 PM
 */

namespace console\controllers;


use Yii;
use yii\console\Controller;

class DeployController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->runAction('migrate');
    }

    public function actionAll()
    {
        $this->actionIndex();
        Yii::$app->runAction('fixture', ['*']);
    }
}