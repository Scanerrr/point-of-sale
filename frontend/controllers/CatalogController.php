<?php

namespace frontend\controllers;

use Yii;
use frontend\controllers\access\CookieController;
use common\models\{Category, Product};

class CatalogController extends CookieController
{
    /**
     * @param int $id
     * @return string
     */
    public function actionCategory(int $id)
    {
        $location = Yii::$app->params['location'];

        $categories = Category::find()->active()->forParent($id)->orderBy('name')->all();
        $products = Product::find()->active()->forCategory($id)->orderBy('name')->all();

        return $this->render('category', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
