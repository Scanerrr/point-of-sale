<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-10-01
 * Time: 12:52 PM
 */

namespace frontend\controllers;


use frontend\models\CreateCustomerForm;
use Yii;
use frontend\components\cart\Cart;
use frontend\controllers\access\CookieController;
use yii\web\{NotFoundHttpException, Response};
use common\models\{Order, OrderPayment, OrderProduct, PaymentMethod, Product, Location};

/**
 * Class CartController
 * @package frontend\controllers
 */
class CartController extends CookieController
{

    public $layout = 'afterLocation';

    public function actionIndex()
    {
        $this->view->registerCssFile('/css/checkout-index.css');
        return $this->render('index');
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionUpdate(int $id): Response
    {

        $quantity = abs(Yii::$app->request->post('quantity'));

        Yii::$app->cart->update($id, $quantity);

        return $this->asJson([
            'success' => true,
        ]);
    }

    /**
     * @return string|Response
     * @throws \yii\db\Exception
     */
    public function actionCheckout()
    {
        $order = new Order();

        /* @var Cart $cart */
        $cart = Yii::$app->cart;

        /* @var Location $location */
        $location = Yii::$app->params['location'];

        $items = $cart->getItems();
        if (!$items) return $this->redirect(['/catalog/index']);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $order->load($post);

            $total = $cart->total;

            $order->status = Order::STATUS_NEW;
            $order->location_id = $location->id;
            $order->employee_id = Yii::$app->user->id;
            $order->total_tax = $cart->totalTax;
            $order->total = $total; //todo: check for payment

            $transaction = Yii::$app->db->beginTransaction();

            if (!$order->save()) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', 'Order was not created');
            }

            foreach ($items as $item) {
                /* @var Product $product */
                $product = $item['product'];
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $product->id;
                $orderProduct->quantity = $item['quantity'];
                $orderProduct->price = $item['price'];
                $orderProduct->discount = $item['discount'] * $item['quantity'];

                $tax = ($item['price'] * $location->tax_rate) / 100; // get tax in $
                $orderProduct->tax = $tax;
                $orderProduct->total = ($item['price'] + $tax) * $item['quantity'];

                if (!$orderProduct->save()) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('warning', 'Order product was not created');
                }
            }

            $orderPayment = new OrderPayment();
            $orderPayment->order_id = $order->id;
            $orderPayment->method_id = $post['payment_method'];
            $orderPayment->amount = $post['payment_amount'];

            $details = [];

            switch ($post->payment_type) {
                case PaymentMethod::TYPE_CASH:
                    $orderPayment->method_id = PaymentMethod::find()->select('id')->where(['type_id' => PaymentMethod::TYPE_CASH])->scalar();
                    $details = [
                        'tendered' => $post['payment_amount'],
                        'change' => $total - $post['payment_amount']
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
                Yii::$app->session->setFlash('success', 'Order #' . $order->id . ' created');

                return $this->redirect(Yii::$app->request->referrer ?? ['/site/index']);
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Order payment was not created');
            }

        }

        return $this->render('checkout', [
            'cart' => $cart,
            'location' => $location,
            'model' => $order,
            'customerModel' => new CreateCustomerForm()
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

        return $this->renderAjax($view, [
            'total' => Yii::$app->cart->total
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
            return $this->asJson(['success' => true]);
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