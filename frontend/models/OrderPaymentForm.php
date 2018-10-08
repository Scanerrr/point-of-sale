<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Create customer form
 */
class OrderPaymentForm extends Model
{

    public $order_id;
    public $type_id;
    public $method_id;
    public $details;
    public $amount;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

        ];
    }

}
