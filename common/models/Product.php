<?php

namespace common\models;

use common\models\query\ProductQuery;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $category_id
 * @property int $supplier_id
 * @property string $name
 * @property string $description
 * @property string $cost_price
 * @property string $markup_price
 * @property string $max_price
 * @property string $tax
 * @property string $commission
 * @property int $commission_policy_id
 * @property string $image
 * @property string $barcode
 * @property string $size
 * @property string $sku
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property string|null $imageUrl
 *
 * @property Inventory[] $inventories
 * @property Category $category
 * @property Supplier $supplier
 */
class Product extends ActiveRecord
{

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    const COMMISSION_REGULAR = 1;
    const COMMISSION_NO = 2;

    const UPLOAD_PATH = 'upload/product/';

    /**
     * \yii\web\UploadedFile
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'supplier_id', 'commission_policy_id', 'status'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['cost_price', 'markup_price', 'max_price', 'tax', 'commission'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'image'], 'string', 'max' => 255],
            [['barcode', 'size', 'sku'], 'string', 'max' => 64],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplier_id' => 'id']],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['commission_policy_id', 'default', 'value' => self::COMMISSION_REGULAR],
            ['commission_policy_id', 'in', 'range' => [self::COMMISSION_REGULAR, self::COMMISSION_NO]],

            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category',
            'supplier_id' => 'Supplier',
            'name' => 'Name',
            'description' => 'Description',
            'cost_price' => 'Cost Price',
            'markup_price' => 'Markup Price',
            'max_price' => 'Max Price',
            'tax' => 'Tax',
            'commission_policy_id' => 'Commission Policy',
            'commission' => 'Commission',
            'image' => 'Image',
            'barcode' => 'Barcode',
            'size' => 'Size',
            'sku' => 'Sku',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInventories()
    {
        return $this->hasMany(Inventory::class, ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::class, ['id' => 'supplier_id']);
    }

    /**
     * {@inheritdoc}
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }


    /**
     * Upload avatar
     * @return bool
     * @throws \yii\base\Exception
     */
    public function upload()
    {
        if (!$this->validate('imageFile'))  return false;

        $directory = Product::UPLOAD_PATH . $this->id;
        FileHelper::createDirectory($directory);

        $fileName = $this->imageFile->baseName . '.' . $this->imageFile->extension;
        $this->imageFile->saveAs($directory . '/' . $fileName);

        return true;
    }

    /**
     * get valid image url link
     *
     * @return null|string
     */
    public function getImageUrl(): ?string
    {
        return $this->image ? self::UPLOAD_PATH . $this->id . '/' . $this->image : null;
    }
}
