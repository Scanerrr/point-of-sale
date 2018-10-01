<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-10-01
 * Time: 10:04 AM
 */

namespace frontend\components\cart;


use Yii;
use yii\base\BaseObject;

/**
 * Class SessionStorage
 * @property \yii\web\Session session
 * @package Scanerrr\Cart
 */
class SessionStorage extends BaseObject
{
    /**
     * @var string
     */
    public $key = 'cart';

    /**
     * @param Cart $cart
     * @return array|mixed
     */
    public function load(Cart $cart)
    {
        $cartData = [];

        if (false !== ($session = $this->session->get($this->key, false))) {
            $cartData = unserialize($session);
        }
        return $cartData;
    }

    public function save(Cart $cart)
    {
        $sessionData = serialize($cart->getItems());

        $this->session->set($this->key, $sessionData);
    }

    /**
     * @return null|object
     * @throws \yii\base\InvalidConfigException
     */
    public function getSession()
    {
        return Yii::$app->get('session');
    }
}