<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property int $usuario_id
 * @property int|null $padre_id
 * @property string $contenido
 * @property string|null $created_at
 * @property int|null $likes
 * @property int|null $dislikes
 * @property float|null $cred_index
 * @property int|null $isGob
 * @property int|null $isCel
 * @property int|null $isNews
 * @property int|null $isUser
 * @property int|null $isAdmin
 *
 * @property Notificaciones[] $notificaciones
 * @property Notificaciones[] $notificaciones0
 * @property Posts $padre
 * @property Posts[] $posts
 * @property Usuarios $usuario
 */
class Posts extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['padre_id'], 'default', 'value' => null],
            [['isAdmin'], 'default', 'value' => 0],
            [['usuario_id', 'contenido'], 'required'],
            [['usuario_id', 'padre_id', 'likes', 'dislikes', 'isGob', 'isCel', 'isNews', 'isUser', 'isAdmin'], 'integer'],
            [['created_at'], 'safe'],
            [['cred_index'], 'number'],
            [['contenido'], 'string', 'max' => 480],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['usuario_id' => 'id']],
            [['padre_id'], 'exist', 'skipOnError' => true, 'targetClass' => Posts::class, 'targetAttribute' => ['padre_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'usuario_id' => Yii::t('app', 'Usuario ID'),
            'padre_id' => Yii::t('app', 'Padre ID'),
            'contenido' => Yii::t('app', 'Contenido'),
            'created_at' => Yii::t('app', 'Created At'),
            'likes' => Yii::t('app', 'Likes'),
            'dislikes' => Yii::t('app', 'Dislikes'),
            'cred_index' => Yii::t('app', 'Cred Index'),
            'isGob' => Yii::t('app', 'Is Gob'),
            'isCel' => Yii::t('app', 'Is Cel'),
            'isNews' => Yii::t('app', 'Is News'),
            'isUser' => Yii::t('app', 'Is User'),
            'isAdmin' => Yii::t('app', 'Is Admin'),
        ];
    }

    /**
     * Gets query for [[Notificaciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotificaciones()
    {
        return $this->hasMany(Notificaciones::class, ['post_original_id' => 'id']);
    }

    /**
     * Gets query for [[Notificaciones0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotificaciones0()
    {
        return $this->hasMany(Notificaciones::class, ['comentario_id' => 'id']);
    }

    /**
     * Gets query for [[Padre]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPadre()
    {
        return $this->hasOne(Posts::class, ['id' => 'padre_id']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Posts::class, ['padre_id' => 'id']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::class, ['id' => 'usuario_id']);
    }

}
