<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-10-01
 * Time: 12:52 PM
 */

namespace frontend\controllers;


use Yii;
use yii\web\NotFoundHttpException;
use frontend\components\cart\Cart;
use common\models\{Order, Product, Location};
use frontend\controllers\access\CookieController;

class CartController extends CookieController
{

    public function actionIndex()
    {
        return 1;
    }

    public function actionAdd()
    {
        if (($productId = Yii::$app->request->post('product_id'))
                && ($price = Yii::$app->request->post('price'))
                && ($quantity = Yii::$app->request->post('quantity'))) {

            $product = $this->findModel($productId);

            Yii::$app->cart->add($product, $price, $quantity);
            Yii::$app->session->setFlash('success', 'Item added to cart');
        }
        return $this->redirect(Yii::$app->request->referrer ?? ['/site/index']);
    }
    
    public function actionCheckout()
    {
        /* @var Cart $cart */
        $cart = Yii::$app->cart;

        /* @var Location $location */
        $location = Yii::$app->params['location'];

        $items = $cart->getItems();
        if (!$items) return $this->redirect(Yii::$app->request->referrer ?? ['/site/index']);

        $order = new Order();
        foreach ($items as $item) {

        }
    }

    public function actionDelete(int $id)
    {
        Yii::$app->cart->remove($id);

        return $this->redirect(Yii::$app->request->referrer ?? ['/site/index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = Product::find()->where(['id' => $id])->active()->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}