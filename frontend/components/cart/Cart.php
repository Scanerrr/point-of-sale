<?php

namespace frontend\components\cart;

use common\models\Product;
use Yii;
use yii\base\{Component, InvalidArgumentException};


/**
 * Class Cart
 * @property float totalDiscount
 * @property float totalTax
 * @property float subTotal
 * @property mixed total
 * @package frontend\components\cart
 */
class Cart extends Component
{

    public $storageClass = SessionStorage::class;

    /**
     * @var SessionStorage
     */
    private $_storage;

    protected $items;

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param int $id
     * @param float $discount
     */
    public function addDiscount(int $id, float $discount): void
    {
        if ($discount > 0) {
            $this->items[$id]['discount'] = $discount;
            $this->saveItems();
        }
    }

    /**
     * @param Product $product
     * @param int $quantity
     */
    public function add(Product &$product, int $quantity = 1): void
    {
        if (isset($this->items[$product->id])) {
            $this->plus($product->id, $quantity);
        } else {
            $this->items[$product->id] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
            $this->saveItems();
        }
    }

    /**
     * Adding item quantity in the cart
     * @param integer $id
     * @param integer $quantity
     * @return void
     */
    public function plus(int $id, int $quantity): void
    {
        if (isset($this->items[$id])) {
            $this->items[$id]['quantity'] = $quantity + $this->items[$id]['quantity'];
        }
        $this->saveItems();
    }

    /**
     * @param int $id
     * @param int $quantity
     */
    public function update(int $id, int $quantity = null): void
    {
        if ($quantity) $this->items[$id]['quantity'] = $quantity;

        $this->saveItems();
    }

    /**
     * @param $id
     */
    public function remove($id): void
    {
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
        }

        $this->saveItems();
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->getItems());
    }

    /**
     * @return float
     */
    public function getSubTotal(): float
    {
        return array_sum(array_map(function ($item) {
            return $item['product']->markup_price * $item['quantity'];
        }, $this->items));
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->subTotal + $this->totalTax - $this->totalDiscount;
    }

    /**
     * @return float
     */
    public function getTotalTax(): float
    {
        $tax_rate = Yii::$app->params['location']->tax_rate ?? 1;

        return ($this->subTotal * $tax_rate) / 100;
    }

    /**
     * @return float
     */
    public function getTotalDiscount(): float
    {
        //sum every item with discount
        return array_sum(
            array_map(function ($item) {
                return $item['discount'] * $item['quantity'];
            }, array_filter($this->items, function ($item) {
                return isset($item['discount']);
            }))
        );
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->clear(false);
        $this->setStorage(Yii::createObject($this->storageClass));
        $this->items = $this->_storage->load($this);
    }

    /**
     * @param mixed $storage
     */
    public function setStorage($storage): void
    {
        $this->_storage = $storage;
    }

    /**
     * @return SessionStorage
     */
    public function getStorage(): SessionStorage
    {
        return $this->_storage;
    }

    /**
     * @param bool $save
     * @return Cart
     */
    public function clear(bool $save = true): self
    {
        $this->items = [];
        $save && $this->saveItems();
        return $this;
    }

    private function saveItems()
    {
        $this->_storage->save($this->items);
    }
}
