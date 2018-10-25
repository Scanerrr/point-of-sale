<?php

namespace frontend\controllers;

use Yii;
use frontend\models\AddToCartForm;
use common\models\{Category, Product};
use frontend\controllers\access\CookieController;

class CatalogController extends CookieController
{
    /**
     * @param int $id
     * @return string
     */
    public function actionCategory(int $id)
    {
        $model = new AddToCartForm();

        if ($model->load(Yii::$app->request->post()) && $model->add()) {
            Yii::$app->session->setFlash('success', 'Product added to cart');
            return $this->refresh();
        }

        $categories = Category::find()->active()->forParent($id)->orderBy('name')->all();
        $products = Product::find()->active()->forCategory($id)->orderBy('name')->all();

        $this->view->registerCssFile('/css/catalog.css');

        return $this->render('category', [
            'categories' => $categories,
            'products' => $products,
            'model' => $model
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
