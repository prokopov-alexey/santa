<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $name
 * @property string $sex
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
    public const SCENARIO_CREATE = 'create';
    public const SCENARIO_WISHLIST = 'wishlist';
    public const SCENARIO_PAIR = 'pair';
    
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
            [['public_id', 'secret_id'], 'required', 'on' => self::SCENARIO_CREATE],
            [['santa_id'], 'integer', 'on' => self::SCENARIO_PAIR],
            [['wishlist'], 'string', 'on' => self::SCENARIO_WISHLIST],
            [['name'], 'string', 'max' => 255, 'on' => self::SCENARIO_CREATE],
            [['public_id', 'secret_id'], 'string', 'max' => 32, 'on' => self::SCENARIO_CREATE],
            [['public_id'], 'unique', 'on' => self::SCENARIO_CREATE],
            [['secret_id'], 'unique', 'on' => self::SCENARIO_CREATE],
            [['name'], 'unique', 'on' => self::SCENARIO_CREATE],
            [['santa_id'], 'unique', 'on' => self::SCENARIO_PAIR],
            [['santa_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['santa_id' => 'id'], 'on' => self::SCENARIO_PAIR],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'sex' => 'Пол',
            'public_id' => 'Открытый ID',
            'secret_id' => 'Секретный ключ',
            'santa_id' => 'ID Санты',
            'wishlist' => 'Вишлист',
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
    
    public function isMan(): bool {
        return $this->sex == 'M';
    }
}
