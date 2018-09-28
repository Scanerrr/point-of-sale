<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 2018-09-28
 * Time: 1:59 PM
 */

namespace common\widgets;


use common\models\Category;
use yii\base\Widget;

class Categories extends Widget
{
    /**
     * @var Category
     */
    public $category;

    public function run()
    {
        $categories = Category::find()->active()->forParent()->orderBy('name')->all();
        $categories = $this->processData($categories);

//        $items = $this->getItemsRecursive($categories, null, $this->category);

        return $this->render('categories', [
            'items' => $categories
        ]);
    }

    private function processData($categories)
    {
        $items = [];
        foreach ($categories as $category) {
            $items[] = [
                'label' => $category->name,
                'url' => ['/catalog/category', 'id' => $category->id],
            ];
        }
        return $items;
    }

    private function getItemsRecursive(&$categories, $parentID, $current)
    {
        $items = [];
        foreach ($categories as $category) {
            if ($category->parent_id !== $parentID) continue;
            $items[] = [
                'label' => $category->name,
                'url' => ['/catalog/category', 'id' => $category->id],
                'items' => $this->getItemsRecursive($categories, $category->id, $current)
            ];
        }
        return $items;
    }
}