<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-10-01
 * Time: 12:52 PM
 */

namespace frontend\controllers;


use Yii;
use yii\helpers\VarDumper;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use frontend\components\cart\Cart;
use frontend\controllers\access\CookieController;
use common\models\{Customer, Order, OrderPayment, OrderProduct, PaymentMethod, Product, Location};

/**
 * Class CartController
 * @package frontend\controllers
 */
class CartController extends CookieController
{

    public $layout = 'afterLocation';

    public function actionIndex()
    {
        return $this->render('index', [
            'customer' => Customer::findOne(Yii::$app->session->get('customer'))
        ]);
    }

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
     * @return string|Response
     * @throws \yii\db\Exception
     */
    public function actionCheckout()
    {
        $model = new Order();

        /* @var Cart $cart */
        $cart = Yii::$app->cart;

        /* @var Location $location */
        $location = Yii::$app->params['location'];

        $items = $cart->getItems();
        if (!$items) return $this->redirect(['/catalog/index']);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $model->load($post);

            $model->status = 0;
            $model->location_id = $location->id;
            $model->employee_id = Yii::$app->user->id;
            $model->total_tax = 0;
            $model->total = 0;

            $transaction = Yii::$app->db->beginTransaction();

            if (!$model->save()) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', 'Order was not created');
            }

            $orderTotal = $orderTotalTax = 0;
            foreach ($items as $item) {
                /* @var Product $product */
                $product = $item['product'];
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $model->id;
                $orderProduct->product_id = $product->id;
                $orderProduct->quantity = $item['quantity'];
                $orderProduct->price = $item['price'];

                $orderTotalTax += $tax = ($item['price'] * $location->tax_rate) / 100; // get tax in $
                $orderTotal += $total = ($item['price'] + $tax) * $item['quantity'];
                $orderProduct->tax = $tax;
                $orderProduct->total = $total;

                if (!$orderProduct->save()) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('warning', 'Order product was not created');
                }
            }

            $model->status = 1;
            $model->total = $orderTotal;
            $model->total_tax = $orderTotalTax;

            if (!$model->save(false)) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Order was not updated');
            }

            $orderPayment = new OrderPayment();
            $orderPayment->order_id = $model->id;
            $orderPayment->method_id = $post['payment_method'];
            $orderPayment->amount = $post['payment_amount'];

            $details = [];

            switch ($post->payment_type) {
                case PaymentMethod::TYPE_CASH:
                    $orderPayment->method_id = PaymentMethod::find()->select('id')->where(['type_id' => PaymentMethod::TYPE_CASH])->scalar();
                    $details = [
                        'tendered' => $post['payment_amount'],
                        'change' => $orderTotal - $post['payment_amount']
                    ];
                    break;
                case PaymentMethod::TYPE_CREDIT_CARD:
                    $details = [
                        'last_digits' => $post['payment_card_number']
                    ];
                    break;
                default:
                    break;
            }

            $orderPayment->details = json_encode($details);

            if ($orderPayment->save()) {
                $transaction->commit();
                $cart->clear();
                Yii::$app->session->setFlash('success', 'Order ' . $model->id . ' created');

                return $this->redirect(Yii::$app->request->referrer ?? ['/site/index']);
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Order payment was not created');
            }

        }

        return $this->render('checkout', [
            'cart' => $cart,
            'location' => $location,
            'model' => $model,
        ]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionLoadForm()
    {
        if (!Yii::$app->request->isAjax || !Yii::$app->request->isPost) throw new NotFoundHttpException();

        $type = Yii::$app->request->post('type');

        switch ($type) {
            case PaymentMethod::TYPE_CASH:
                $view = '_cash_form';
                break;
            case PaymentMethod::TYPE_CREDIT_CARD:
                $view = '_credit_card_form';
                break;
            default:
                throw new NotFoundHttpException();
                break;
        }

        $cart = Yii::$app->cart;

        return $this->renderAjax($view, [
            'total' => $cart->total + $cart->tax
        ]);
    }

    /**
     * @param int $id
     * @return array|Response
     */
    public function actionDelete(int $id)
    {
        Yii::$app->cart->remove($id);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => true];
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