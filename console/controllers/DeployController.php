<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 10/10/2018
 * Time: 6:49 PM
 */

namespace console\controllers;


use Yii;
use yii\base\Module;
use yii\console\Controller;

class DeployController extends Controller
{

    public function __construct(string $id, Module $module, array $config = [])
    {
        $this->flushSchemaCache();
        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        Yii::$app->runAction('migrate', ['interactive' => false]);
    }

    public function actionAll()
    {
        $this->actionIndex();
        Yii::$app->runAction('fixture', [
            '*',
            'interactive' => false
        ]);
    }

    protected function flushSchemaCache()
    {
        Yii::$app->runAction('cache/flush-schema', [
            'interactive' => false
        ]);
    }


    public function __destruct()
    {
        $this->flushSchemaCache();
    }
}