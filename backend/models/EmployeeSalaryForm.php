<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 11/2/2018
 * Time: 4:32 PM
 */

namespace backend\models;


use common\models\User;
use yii\base\Model;
use yii\helpers\Json;
use yii\helpers\VarDumper;

/* @property bool $hourlyStatus */
/* @property bool $flatStatus */
/* @property bool $productStatus */
/* @property bool $productOrCommissionStatus */
/* @property bool $baseStatus */
/* @property bool $hourlyIncludeBreaks */
/* @property int $hourlyRate */
/* @property int $baseRate */
/* @property string $flatRate */
/* @property string $baseAdded */
/* @property string $baseAddedOn */
class EmployeeSalaryForm extends Model
{
    public $hourlyStatus = false;
    public $hourlyRate;
    public $hourlyIncludeBreaks = false;
    public $flatStatus = false;
    public $flatRate;
    public $productStatus = false;
    public $productOrCommissionStatus = false;
    public $baseStatus = false;
    public $baseRate;
    public $baseAdded;
    public $baseAddedOn;

    public function rules()
    {
        return [
            [['hourlyRate', 'baseRate'], 'integer', 'min' => 0],
            [['hourlyStatus', 'flatStatus', 'productStatus', 'productOrCommissionStatus', 'baseStatus', 'hourlyIncludeBreaks'], 'boolean'],
            [['baseAdded', 'baseAddedOn'], 'string'],
            ['flatRate', 'number', 'min' => 0, 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'hourlyStatus' => 'Hourly',
            'flatStatus' => 'Flat Commission',
            'productStatus' => 'Products Commission',
            'productOrCommissionStatus' => 'Higher of Commission or Hourly',
            'baseStatus' => 'Base Salary',
            'hourlyIncludeBreaks' => 'Do not include breaks in total hours count',
        ];
    }

    public function save(User $user): bool
    {
        if (!$this->validate()) return false;

        $salary = [
            'flat' => [
                'status' => $this->flatStatus,
                'rate' => $this->flatStatus ? $this->flatRate : ''
            ],
            'product' => [
                'status' => $this->productStatus
            ],
            'product_or_commission' => [
                'status' => $this->productOrCommissionStatus
            ],
            'hourly' => [
                'status' => $this->hourlyStatus,
                'rate' => $this->hourlyStatus ? $this->hourlyRate : '',
                'include_breaks' => $this->hourlyStatus ? $this->hourlyIncludeBreaks : '',
            ],
            'base' => [
                'status' => $this->baseStatus,
                'rate' => $this->baseStatus ? $this->baseRate : '',
                'added' => $this->baseAdded,
                'added_on' => $this->baseAddedOn
            ]
        ];

        $user->salary_settings = Json::encode($salary);

        return $user->save(false);
    }
}