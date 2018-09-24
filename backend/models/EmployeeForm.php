<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;

/**
 * Signup form
 */
class EmployeeForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $repeat_password;
    public $name;
    public $avatar;
    public $phone;
    public $position;
    public $country;
    public $state;
    public $city;
    public $address;
    public $zip;
    public $role;
    public $status;

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
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2],

            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'This email address has already been taken.'],

            ['password', 'required', 'on' => 'create'],
            [['password', 'repeat_password'], 'required', 'skipOnEmpty' => true, 'on' => 'update'],
            ['repeat_password', 'compare', 'compareAttribute' => 'password'],
            ['password', 'string', 'min' => 6],

            [['role', 'status'], 'integer'],
            [['username', 'email', 'name', 'avatar', 'phone', 'position', 'country', 'state', 'city', 'address'], 'string', 'max' => 255],
            [['zip'], 'string', 'max' => 5],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg'],

            ['status', 'default', 'value' => User::STATUS_ACTIVE],
            ['status', 'in', 'range' => [User::STATUS_ACTIVE, User::STATUS_DELETED]],
            ['role', 'default', 'value' => User::ROLE_USER],
            ['role', 'in', 'range' => [
                User::ROLE_USER,
                User::ROLE_MANAGER,
                User::ROLE_ADMIN
            ]],
        ];
    }

    /**
     * insert user to db.
     *
     * @return User
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
     * Upload avatar
     * @param int $id
     * @return bool
     * @throws \yii\base\Exception
     */
    public function upload(int $id)
    {
        if ($this->validate('imageFile')) {
            $directory = User::UPLOAD_PATH . $id;
            FileHelper::createDirectory($directory);

            $fileName = $this->imageFile->baseName . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($directory . '/' . $fileName);
            $user = User::findOne($id);
            if (!$user) return false;
            $user->avatar = $fileName;
            $user->save(false);
            return true;
        }
        return false;
    }
}
