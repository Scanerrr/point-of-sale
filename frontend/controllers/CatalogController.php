<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\Product;

class CatalogController extends AccessController
{

    public $layout = 'catalog';


    /**
     * @param int $id
     * @return string
     */
    public function actionCategory(int $id)
    {
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
