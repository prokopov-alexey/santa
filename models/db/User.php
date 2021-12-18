<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $name
 * @property string $public_id
 * @property string $secret_id
 * @property int|null $target_user_id
 * @property string|null $wishlist
 *
 * @property User $targetUser
 * @property User $user
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['public_id', 'secret_id'], 'required'],
            [['santa_id'], 'integer'],
            [['wishlist'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['public_id', 'secret_id'], 'string', 'max' => 32],
            [['public_id'], 'unique'],
            [['secret_id'], 'unique'],
            [['name'], 'unique'],
            [['santa_id'], 'unique'],
            [['santa_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['santa_id' => 'id']],
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
            'public_id' => 'Public ID',
            'secret_id' => 'Secret ID',
            'santa_id' => 'Santa ID',
            'wishlist' => 'Wishlist',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSanta()
    {
        return $this->hasOne(User::className(), ['id' => 'santa_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTarget()
    {
        return $this->hasOne(User::className(), ['santa_id' => 'id']);
    }
    
    public function validateAuthKey($authKey) {
        return $authKey === $this->secret_id;
    }
    
    public static function findIdentity($id) {
        return User::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return User::findOne(['secret_id' => $token]);
    }

    public function getAuthKey() {
        return $this->secret_id;
    }

    public function getId() {
        return $this->id;
    }
    
    public function findOneByPublicId($key) {
        return User::findOne(['public_id' => $key]);
    }
    
    public function isSanta(): bool {
        return $this->getTarget()->exists();
    }

    public function hasSanta(): bool {
        return $this->getSanta()->exists();
    }
    
}
