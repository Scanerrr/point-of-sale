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
use common\models\{Order, OrderPayment, OrderProduct, PaymentMethod, Product, Location, UserCommission};

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

        $payments = $session->get('location.' . $location->id . '.payments', []);

        $paid = $this->getPaidPrice();

        if (Yii::$app->request->isPost) {
            $total = $cart->total;

            $user = Yii::$app->user;

            $order->status = Order::STATUS_NEW;
            $order->location_id = $location->id;
            $order->employee_id = $user->id;
            $order->customer_id = Yii::$app->request->post('customer') ?? null;
            $order->total_tax = $cart->totalTax;
            $order->total = $total;

            if ($paid < $total) {
                Yii::debug('paid: ' . $paid);
                Yii::debug('total: ' . $total);
                return $this->asJson(['error' => 'Paid amount not matching total price!']);
            }

            if (!$order->save()) {
                Yii::debug($order->getErrors());
                return $this->asJson(['error' => 'Order was not created!']);
            }

            // save ordered products
            $productsCommissions = 0;
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
                $productTotal = ($item['price'] + $tax) * $item['quantity'];
                $orderProduct->tax = $tax;
                $orderProduct->total = $productTotal;

                if (!$orderProduct->save()) {
                    Yii::debug($orderProduct->getErrors());
                    return $this->asJson(['error' => 'Order was not created!']);
                }
                $productsCommissions += (($productTotal * $product->commission) / 100)  * $item['quantity'];
            }

            // save payment methods
            foreach ($payments as $payment) {

                $orderPayment = new OrderPayment();
                $orderPayment->order_id = $order->id;
                $orderPayment->method_id = $payment['method_id'];
                $orderPayment->amount = $payment['price'];

                $details = [];

                switch (PaymentMethod::getTypeIdById($orderPayment->method_id)) {
                    case PaymentMethod::TYPE_CASH:
                        $details = [
                            'tendered' => $payment['price'],
                            'change' => $paid - $total
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
                    Yii::debug($orderPayment->getErrors());
                    return $this->asJson(['error' => 'Order payment was not created!']);
                }
            }

            // save user commission
            $userCommission = new UserCommission();
            $userCommission->order_id = $order->id;
            $userCommission->user_id = $user->id;

            $commissions = $user->identity->salaryCommissions;

            if ($commissions['flat'] && !$commissions['product']) {
                $commissionType = UserCommission::COMMISSION_TYPE_FLAT;
                $commissionValue = ($cart->subTotal * $commissions['flat']['rate']) / 100;
            } elseif ($commissions['flat'] && $commissions['product']) {
                $commissionType = UserCommission::COMMISSION_TYPE_FLAT_PRODUCT;
                $commissionValue = (($cart->subTotal * $commissions['flat']['rate']) / 100) + $productsCommissions;
            } else {
                $commissionType = UserCommission::COMMISSION_TYPE_PRODUCT;
                $commissionValue = $productsCommissions;
            }
            $userCommission->commission_type = $commissionType;
            $userCommission->commission_value = $commissionValue;
            if (!$userCommission->save()) {
                Yii::debug($userCommission->getErrors());
                return $this->asJson(['error' => 'User commission was not created!']);
            }

            // update order status
            $order->status = Order::STATUS_COMPLETE;
            if (!$order->save(false)) {
                return $this->asJson(['error' => 'Order status was not updated!']);
            }

            $cart->clear();
            $this->resetPayment();

            $session->setFlash('success', 'Order #' . $order->id . ' created');

            return $this->asJson(['success' => 'Order #' . $order->id . ' created']);
        }

        $this->view->registerJsFile('/js/checkout.js', ['depends' => JqueryAsset::class]);
        $this->view->registerCssFile('/css/checkout.css');

        return $this->render('checkout', [
            'cart' => $cart,
            'paid' => $paid,
            'payments' => $payments
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

        $total = Yii::$app->cart->total;
        $paid = $this->getPaidPrice();

        return $this->renderAjax($view, [
            'total' => $total - $paid
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

    protected function getPaidPrice(): float
    {
        $payments = Yii::$app->session->get('location.' . Yii::$app->params['location']->id . '.payments', []);
        return round(array_reduce($payments, function ($total, $payment) {
            return $total + $payment['price'];
        }, 0), 2);
    }

    protected function setPayment(array $payment): void
    {
        $session = Yii::$app->session;
        $key = 'location.' . Yii::$app->params['location']->id . '.payments';
        if ($payments = $session->get($key)) {
            array_push($payments, $payment);
            $session->set($key, $this->getFormattedPayments($payments, $payment));
        } else {
            $session->set($key, [$payment]);
        }
    }

    /**
     * if payments have several items with the same method
     * return it as one item with summary price
     *
     * @param array $payments
     * @param array $payment
     * @return array
     */
    protected function getFormattedPayments(array $payments, array $payment): array
    {
        $filteredPayments = array_filter($payments, function ($paid) use ($payment) {
            return $paid['method_id'] === $payment['method_id'];
        });

        if (empty($filteredPayments)) return $payments;

        $newPayments = array_diff_assoc($payments, $filteredPayments);

        $newPayment = array_reduce(
            $filteredPayments,
            function ($newPayment, $payment) {
                return [
                    'price' => isset($newPayment['price']) ? $payment['price'] + $newPayment['price'] : $payment['price'],
                    'method_id' => $payment['method_id'],
                    'name' => $payment['name']
                ];
            }
        );
        array_push($newPayments, $newPayment);

        return $newPayments;
    }

    protected function resetPayment(): void
    {
        Yii::$app->session->remove('location.' . Yii::$app->params['location']->id . '.payments');
    }
}