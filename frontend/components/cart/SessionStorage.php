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
     * @return array|mixed
     */
    public function load()
    {
        return Yii::$app->session->get($this->key, []);
    }

    /**
     * @param array $items
     */
    public function save($items)
    {
        Yii::$app->session->set($this->key, $items);
    }
}