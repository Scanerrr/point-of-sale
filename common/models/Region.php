<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "region".
 *
 * @property int $id
 * @property string $name
 *
 * @property Location[] $locations
 */
class Region extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'region';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::class, ['region_id' => 'id']);
    }
}
