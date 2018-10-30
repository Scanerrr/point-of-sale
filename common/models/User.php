<?php

namespace common\models;

use Yii;
use yii\helpers\Json;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use common\models\query\UserQuery;

/**
 * User model
 *
 * @property int $id
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $username
 * @property string $email
 * @property string $name
 * @property string $avatar
 * @property string $phone
 * @property string $position
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $zip
 * @property string $address
 * @property int $role
 * @property int $status
 * @property array $salary_settings
 * @property string $created_at
 * @property string $updated_at
 *
 * @property string|null $avatarUrl
 * @property array $salaryCommission
 *
 * @property LocationUser[] $locationUsers
 * @property LocationWorkHistory[] $locationWorkHistories
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    const ROLE_USER = 1;
    const ROLE_MANAGER = 2;
    const ROLE_ADMIN = 3;

    const UPLOAD_PATH = 'upload/user/';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => [
                self::ROLE_USER,
                self::ROLE_MANAGER,
                self::ROLE_ADMIN
            ]],
            [['username', 'email'], 'required'],
            [['role', 'status'], 'integer'],
            [['salary_settings', 'created_at', 'updated_at'], 'safe'],
            [['username', 'email', 'name', 'avatar', 'phone', 'position', 'country', 'state', 'city', 'address'], 'string', 'max' => 255],
            [['zip'], 'string', 'max' => 5],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'name' => 'Name',
            'avatar' => 'Avatar',
            'imageFile' => 'Avatar',
            'phone' => 'Phone',
            'position' => 'Position',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'zip' => 'Zip',
            'address' => 'Address',
            'role' => 'Role',
            'status' => 'Status',
            'salary_settings' => 'Salary Settings',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     * @throws \yii\base\Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationUsers()
    {
        return $this->hasMany(LocationUser::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * get valid image url link
     *
     * @return null|string
     */
    public function getAvatarUrl(): ?string
    {
        return $this->avatar ? self::UPLOAD_PATH . $this->id . '/' . $this->avatar : null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocationWorkHistories()
    {
        return $this->hasMany(LocationWorkHistory::class, ['user_id' => 'id']);
    }

    public function validateSalaryInfo($salary)
    {

        $flat = isset($salary['flat']['rate']) && !empty($salary['flat']['rate']) ? ['rate' => $salary['flat']['rate']] : false;

        $product = isset($salary['product']) && $salary['product'] === 'on';

        $hourly = isset($salary['hourly']['rate']) && !empty($salary['hourly']['rate']) ? [
            'rate' => $salary['hourly']['rate'],
            'notIncludeBreaks' => true
        ] : false;

        if ($hourly && isset($salary['hourly']['notIncludeBreaks']) && $salary['hourly']['notIncludeBreaks'] === 'on') {
            $hourly['notIncludeBreaks'] = false;
        }

        $base = isset($salary['base']['rate']) && !empty($salary['base']['rate']) ? [
            'rate' => rand(1000, 5000),
            'added' => 'Weekly',
            'on' => 'Monday'
        ] : false;

        if ($base && isset($salary['base']['added'])) {
            $base['added'] = $salary['base']['added'];
        }

        if ($base && isset($salary['base']['on'])) {
            $base['on'] = $salary['base']['on'];
        }

        $commissionOrHourly = isset($salary['commissionOrHourly']) && $salary['commissionOrHourly'] === 'on';

        // TODO: finish up steps
        return Json::encode([
//                'steps' => [
//                    0 => ['from' => 0, 'to' => 500, 'commission' => rand(5, 10)],
//                    1 => ['from' => 501, 'to' => 1000, 'commission' => rand(11, 15)],
//                ],
            'flat' => $flat,
            'product' => $product,
            'hourly' => $hourly,
            'base' => $base,
            'commissionOrHourly' => $commissionOrHourly
        ]);
    }

    /**
     * @return array
     */
    public function getSalaryCommissions(): array
    {
        $settings = Json::decode($this->salary_settings);
        return [
            'flat' => $settings['flat'],
            'product' => $settings['product'],
        ];
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();

        // удаляем поля, содержащие конфиденциальную информацию
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);

        return $fields;
    }

}
