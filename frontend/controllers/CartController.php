<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-10-01
 * Time: 12:52 PM
 */

namespace frontend\controllers;

use Yii;
use frontend\components\cart\Cart;
use frontend\controllers\access\CookieController;
use yii\web\{JqueryAsset, NotFoundHttpException, Response};
use common\models\{Order, OrderPayment, OrderProduct, PaymentMethod, Product, Location};

/**
 * Class CartController
 * @package frontend\controllers
 */
class CartController extends CookieController
{

    public $layout = 'afterLocation';

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs']['actions']['load-form'] = ['post'];
        $behaviors['verbs']['actions']['assign-payment'] = ['post'];
        return $behaviors;
    }

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

        $session = Yii::$app->session;

        /* @var Location $location */
        $location = Yii::$app->params['location'];

        $items = $cart->getItems();
        if (!$items) return $this->redirect(['/catalog/index']);

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $total = $cart->total;

            $paid = $this->getPaidPrice();

            $order->status = Order::STATUS_NEW;
            $order->location_id = $location->id;
            $order->employee_id = Yii::$app->user->id;
            $order->customer_id = $post['customer'] ?? null;
            $order->total_tax = $cart->totalTax;
            $order->total = $total; //todo: check for payment

            if ($paid <= $total) return $this->asJson(['error' => 'Paid amount not matching total price!']);

            $transaction = Yii::$app->db->beginTransaction();

            if (!$order->save()) {
                $transaction->rollback();
                $session->setFlash('error', 'Order was not created');
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
                    $session->setFlash('warning', 'Order product was not created');
                }
            }

            $payments = $session->get('location.' . $location->id . '.payments', []);

            foreach ($payments as $payment) {

                $orderPayment = new OrderPayment();
                $orderPayment->order_id = $order->id;
                $orderPayment->method_id = $payment['method_id'];
                $orderPayment->amount = $payment['price'];

                $details = [];

                switch (PaymentMethod::getTypeIdById($orderPayment->method_id)) {
                    case PaymentMethod::TYPE_CASH:
                        $details = [
                            'tendered' => $post['price'],
                            'change' => $total - $post['price']
                        ];
                        break;
                    case PaymentMethod::TYPE_CREDIT_CARD:
                        $details = [
                            'last_digits' => $payment['card_number']
                        ];
                        break;
                    default:
                        break;
                }

                $orderPayment->details = json_encode($details);

                if (!$orderPayment->save()) {
                    $transaction->rollBack();
                    $session->setFlash('warning', 'Order payment was not created');
                }

            }

            $transaction->commit();
            $cart->clear();
            $this->resetPayment();

            $session->setFlash('success', 'Order #' . $order->id . ' created');

            return $this->asJson(Yii::$app->request->referrer ?? ['/site/index']);

        }

        $this->view->registerJsFile('/js/checkout.js', ['depends' => JqueryAsset::class]);
        $this->view->registerCssFile('/css/checkout.css');

        return $this->render('checkout', [
            'cart' => $cart,
            'location' => $location,
            'model' => $order
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

    public function actionAssignPayment()
    {
        $post = Yii::$app->request->post();

        $method = $post['method'];

        $payment = [
            'price' => $post['price'],
            'method_id' => $method,
            'name' => PaymentMethod::find()->select('name')->where(['id' => $method])->scalar()
        ];

        if (PaymentMethod::getTypeIdById($method) === PaymentMethod::TYPE_CREDIT_CARD) {
            $payment['card_number'] = $post['card_number'] ?? '';
        }

        $this->setPayment($payment);

        $allowCheckout = false;

        if (Yii::$app->cart->total - $this->getPaidPrice() <= 0) $allowCheckout = true;

        return $this->asJson(['success' => true, 'allowCheckout' => $allowCheckout]);
    }

    protected function getPaidPrice()
    {
        $payments = Yii::$app->session->get('location.' . Yii::$app->params['location']->id . '.payments', []);
        return array_reduce($payments, function ($total, $payment) {
            return $total + $payment['price'];
        }, 0);
    }

    protected function setPayment(array $payment): void
    {
        $session = Yii::$app->session;
        $key = 'location.' . Yii::$app->params['location']->id . '.payments';
        if ($payments = $session->get($key)) {
            array_push($payments, $payment);
            $session->set($key, $payments);
        } else {
            $session->set($key, [$payment]);
        }
    }

    protected function resetPayment(): void
    {
        Yii::$app->session->remove('location.' . Yii::$app->params['location']->id . '.payments');
    }
}