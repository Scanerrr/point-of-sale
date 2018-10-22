<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = 'Order #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <?php if ($model->status !== \common\models\Order::STATUS_REFUND): ?>
        <p>
            <?= Html::a('Refund', ['order/refund', 'id' => $model->id], ['class' => 'btn btn-danger', 'data-method' => 'post']) ?>
        </p>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> Order Details</h3>
                </div>
                <table class="table">
                    <tbody>
                    <tr>
                        <td style="width: 1%;">
                            <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                    data-original-title="Location"><i
                                        class="fa fa-shopping-cart fa-fw"></i></button>
                        </td>
                        <td><?= Html::encode($model->location->name) ?></td>
                    </tr>
                    <tr>
                        <td>
                            <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                    data-original-title="Date Added">
                                <i class="fa fa-calendar fa-fw"></i></button>
                        </td>
                        <td><?= Yii::$app->formatter->asDate($model->created_at) ?></td>
                    </tr>
                    <tr>
                        <td>
                            <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                    data-original-title="Payment Method"><i class="fa fa-credit-card fa-fw"></i>
                            </button>
                        </td>
                        <td><?= Html::encode($model->getOrderPayments()->one()->method->name) ?></td>
                    </tr>
                    <tr>
                        <td>
                            <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                    data-original-title="Employee"><i class="fa fa-truck fa-fw"></i></button>
                        </td>
                        <td><?= Html::encode($model->employee->name) ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($model->customer_id): ?>
            <?php $customer = $model->customer ?>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-user"></i> Customer Details</h3>
                    </div>
                    <table class="table">
                        <tbody>
                        <tr>
                            <td style="width: 1%;">
                                <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                        data-original-title="Customer"><i class="fa fa-user fa-fw"></i></button>
                            </td>
                            <td><?= Html::encode($customer->fullName) ?></td>
                        </tr>
                        <tr>
                            <td>
                                <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                        data-original-title="E-Mail"><i class="fa fa-envelope-o fa-fw"></i></button>
                            </td>
                            <td><a href="mailto:<?= $customer->email ?>"><?= Html::encode($customer->email) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                        data-original-title="Telephone"><i class="fa fa-phone fa-fw"></i></button>
                            </td>
                            <td><?= Html::encode($customer->phone) ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($model->isRefunded): ?>
            <div class="col-md-4">
                <h4 class="text-danger">This order represents a refund.</h4>
            </div>
        <?php endif; ?>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-info-circle"></i> Order (#<?= $model->id ?>)</h3>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="text-left">Product</th>
                    <th class="text-left">Barcode</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Discount</th>
                    <th class="text-right">Tax</th>
                    <th class="text-right">Total</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($model->orderProducts as $orderProduct): ?>
                    <?php $product = $orderProduct->product ?>
                    <tr>
                        <td class="text-left">
                            <?= Html::a($product->name, ['/product/update', 'id' => $product->id], ['class' => 'btn-link']) ?>
                        </td>
                        <td class="text-left"><?= Html::encode($product->barcode) ?></td>
                        <td class="text-right"><?= $orderProduct->quantity ?></td>
                        <td class="text-right"><?= Yii::$app->formatter->asCurrency($orderProduct->price) ?></td>
                        <td class="text-right"><?= Yii::$app->formatter->asCurrency($orderProduct->discount) ?></td>
                        <td class="text-right"><?= Yii::$app->formatter->asCurrency($orderProduct->tax) ?></td>
                        <td class="text-right"><?= Yii::$app->formatter->asCurrency($orderProduct->total) ?></td>
                    </tr>
                <?php endforeach; ?>

                <tr>
                    <td colspan="6" class="text-right">Tax</td>
                    <td class="text-right"><?= Yii::$app->formatter->asCurrency($model->total_tax) ?></td>
                </tr>
                <tr>
                    <td colspan="6" class="text-right">Total</td>
                    <td class="text-right"><?= Yii::$app->formatter->asCurrency($model->total) ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
