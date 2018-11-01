<?php

namespace frontend\controllers;

use Yii;
use frontend\models\AddToCartForm;
use common\models\{Category, InventoryReport, Product};
use frontend\controllers\access\CookieController;
use yii\web\JqueryAsset;
use yii\web\NotFoundHttpException;

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
        $this->view->registerJsFile('/js/catalog.js', ['depends' => JqueryAsset::class]);

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

    public function actionReportDamaged(int $id)
    {
        $product = $this->findProductModel($id);

        $quantity = Yii::$app->request->bodyParams['quantity'] ?? 1;

        $report = new InventoryReport();
        $report->location_id = Yii::$app->params['location']->id;
        $report->product_id = $product->id;
        $report->user_id = Yii::$app->user->id;
        $report->quantity = $quantity;
        $report->reason_id = $report::REASON_DAMAGED;

        if ($report->save()) {
            Yii::$app->session->setFlash('success', 'Report was created');
        } else {
            Yii::$app->session->setFlash('error', 'Report was not created');
        }
        return $this->redirect('index');
    }

    protected function findProductModel(int $id)
    {
        if (($model = Product::find()->where(['id' => $id])->active()->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Product not found');
    }
}
