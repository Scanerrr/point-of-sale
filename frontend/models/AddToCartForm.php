<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Product;

/**
 * Create customer form
 */
class AddToCartForm extends Model
{
    public $productId;
    public $quantity = 1;
    public $discount = false;
    public $discountType = false;

    const DISCOUNT_TYPES = ['currency' => '$', 'percent' => '%'];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity'], 'required'],
            [['quantity', 'productId'], 'integer'],
            [['discount'], 'number'],
            [['discountType'], 'string'],
        ];
    }

    /**
     * add to cart.
     * @return bool
     */
    public function add(): bool
    {

        if (!$this->validate()) return false;

        $product = $this->getProduct();

        if (!$product) return false;

        $cart = Yii::$app->cart;

        $cart->add($product, $this->quantity);

        if (!$this->discount || !$this->discountType) return true;

        if ($discount = $this->getDiscountByType($product->markup_price)) {
            $cart->addDiscount($product->id, $discount);
        }

        return true;
    }

    /**
     * @return Product|null
     */
    private function getProduct(): ?Product
    {
        return Product::find()->where(['id' => $this->productId])->active()->one();
    }

    /**
     * @param float $price
     * @return bool|float|int
     */
    private function getDiscountByType(float $price)
    {
        switch ($this->discountType) {
            case 'currency':
                $discount = $this->discount;
                break;
            case 'percent':
                $discount = ($price * $this->discount) / 100;
                break;
            default:
                $discount = false;
                break;
        }
        return $discount;
    }
}
