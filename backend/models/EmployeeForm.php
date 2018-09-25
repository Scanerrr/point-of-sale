<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;

/**
 * create/update user form
 */
class EmployeeForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $name;
    public $phone;
    public $position;
    public $country;
    public $state;
    public $city;
    public $address;
    public $zip;
    public $role;
    public $status;

    public $_user;

    public function __construct(array $config = [], User $user = null)
    {
        $this->_user = $user;
        parent::__construct($config);
    }

    /**
     * \yii\web\UploadedFile
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'name', 'phone', 'position', 'country', 'state', 'city', 'address'], 'trim'],
            [['username', 'email'], 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.', 'on' => 'create'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.', 'filter' => ['!=', 'username', $this->_user->username]],
            ['username', 'string', 'min' => 2],

            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.', 'on' => 'create'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.', 'filter' => ['!=', 'email', $this->_user->email]],

            [['password', 'password_repeat'], 'required', 'on' => 'create'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
            ['password', 'string', 'min' => 6],

            [['role', 'status'], 'integer'],
            [['username', 'email', 'name', 'phone', 'position', 'country', 'state', 'city', 'address'], 'string', 'max' => 255],
            [['zip'], 'string', 'max' => 5],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 10],
        ];
    }

    /**
     * insert user to db.
     *
     * @return User|null
     * @throws \yii\base\Exception
     */
    public function create()
    {
        if (!$this->validate()) return null;

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->phone = $this->phone;
        $user->position = $this->position;
        $user->country = $this->country;
        $user->state = $this->state;
        $user->city = $this->city;
        $user->zip = $this->zip;
        $user->address = $this->address;
        $user->role = $this->role;
        $user->status = $this->status;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }


    /**
     * updating user
     *
     * @param User $user
     * @return bool
     * @throws \yii\base\Exception
     */
    public function update(User $user)
    {
        if (!$user || !$this->validate()) return false;

        $user->username = $this->username;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->phone = $this->phone;
        $user->position = $this->position;
        $user->country = $this->country;
        $user->state = $this->state;
        $user->city = $this->city;
        $user->zip = $this->zip;
        $user->address = $this->address;
        $user->role = $this->role;
        $user->status = $this->status;
        if ($this->password) {
            $user->setPassword($this->password);
            $user->generateAuthKey();
        }

        return $user->save();
    }

    /**
     * Upload avatar
     * @param User $user
     * @return bool
     * @throws \yii\base\Exception
     */
    public function upload(User $user)
    {
        if (!$this->validate('imageFile'))  return false;

        $directory = User::UPLOAD_PATH . $user->id;
        FileHelper::createDirectory($directory);

        $fileName = $this->imageFile->baseName . '.' . $this->imageFile->extension;
        $this->imageFile->saveAs($directory . '/' . $fileName);

        $user->avatar = $fileName;
        $user->save(false);
        return true;
    }
}
