<?php

namespace frontend\components\cart;

use common\models\Product;
use Yii;
use yii\base\{Component, InvalidArgumentException};


/**
 * Class Cart
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

    public $tax;

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Product $product
     * @param float $price
     * @param int $quantity
     * @return Cart
     */
    public function add(Product &$product, float $price, int $quantity = 1): self
    {
        // TODO: add same item with different price
        $this->items[$product->id] = [
            'product' => $product,
            'price' => $price,
            'quantity' => $quantity,
        ];
        $this->_storage->save($this);

        return $this;
    }

    /**
     * @param int $productId
     * @param float $price
     * @param int $quantity
     * @return Cart
     */
    public function update(int $productId, float $price = null, int $quantity = null): self
    {
        if ($price)  $this->items[$productId]['price'] = $price;
        if ($quantity)  $this->items[$productId]['quantity'] = $quantity;

        $this->_storage->save($this);

        return $this;
    }

    /**
     * @param $id
     * @return Cart
     */
    public function remove($id): self
    {
        if (!isset($this->items[$id])) {
            throw new InvalidArgumentException('Item not found');
        }

        unset($this->items[$id]);

        $this->_storage->save($this);

        return $this;
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
    public function getTotal(): float
    {
        // TODO: add discount
        return array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $this->items));
    }

    /**
     * @return float
     */
    public function getTax(): float
    {
        return $this->tax;
    }

    /**
     * @param float $rate
     * @return void
     */
    public function setTax(float $rate): void
    {
        $this->tax = ($this->total * $rate) / 100;
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
        $save && $this->_storage->save($this);
        return $this;
    }
}
