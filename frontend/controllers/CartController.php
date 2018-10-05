<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-10-01
 * Time: 12:52 PM
 */

namespace frontend\controllers;


use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use frontend\components\cart\Cart;
use frontend\controllers\access\CookieController;
use common\models\{Order, OrderProduct, Product, Location};

/**
 * Class CartController
 * @package frontend\controllers
 */
class CartController extends CookieController
{
    /**
     * @return Response
     * @throws NotFoundHttpException
     */
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

    /**
     * @param int $id
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate(int $id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $cart = Yii::$app->cart;

        $quantity = abs(Yii::$app->request->post('quantity'));

        $cart->update($id, null, $quantity);

        $updatedItem = $cart->items[$id];

        return ['success' => true, 'total' => Yii::$app->formatter->asCurrency($updatedItem['price'] * $updatedItem['quantity'])];
    }

    /**
     * @return Response
     */
    public function actionCheckout()
    {
        /* @var Cart $cart */
        $cart = Yii::$app->cart;

        /* @var Location $location */
        $location = Yii::$app->params['location'];

        $items = $cart->getItems();
        if (!$items) return $this->redirect(Yii::$app->request->referrer ?? ['/site/index']);

        $session = Yii::$app->session;

        $order = new Order();
        $order->invoice = 0;
        $order->status = 0;
        $order->location_id = $location->id;
        $order->customer_id = $session->get('customer');
        $order->employee_id = Yii::$app->user->id;
        $order->total_tax = 0;
        $order->total = 0;
        if ($order->save()) {

            $orderTotal = $orderTotalTax = 0;
            foreach ($items as $item) {
                /* @var Product $product */
                $product = $item['product'];
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $product->id;
                $orderProduct->quantity = $item['quantity'];
                $orderProduct->price = $item['price'];

                $orderTotalTax += $tax = ($item['price'] * $location->tax_rate) / 100; // get tax in $
                $orderTotal += $total = ($item['price'] + $tax) * $item['quantity'];
                $orderProduct->tax = $tax;
                $orderProduct->total = $total;

                if (!$orderProduct->save()) Yii::$app->session->setFlash('warning', 'Order was not created');
            }

            $order->status = 1;
            $order->total = $orderTotal;
            $order->total_tax = $orderTotalTax;
            $order->save(false);
            $cart->clear();
            Yii::$app->session->remove('customer');
            Yii::$app->session->setFlash('success', 'Order ' . $order->id . ' created');
        }
        return $this->redirect(Yii::$app->request->referrer ?? ['/site/index']);
    }

    public function actionSetPayment()
    {
        $success = false;
        if (Yii::$app->request->isAjax) {
            $request = Yii::$app->request;
            $data = [
                'type' => $request->post('type'),
                'price' => $request->post('price')
            ];
            Yii::$app->session->set('payment', serialize($data));
            $success = true;
        }
        return ['success' => $success];
    }

    /**
     * @param int $id
     * @return array|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDelete(int $id)
    {
        $cart = Yii::$app->cart;
        $cart->remove($id);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            // TODO: total with tax
            return ['total' => Yii::$app->formatter->asCurrency($cart->total)];
        }

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