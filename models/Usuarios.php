<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property int $rol_id
 * @property string $user
 * @property string $pwd
 * @property string $birthday
 * @property string|null $created_at
 * @property string $auth_key
 *
 * @property Notificaciones[] $notificaciones
 * @property PayHistoric[] $payHistorics
 * @property PerfilUsuario[] $perfilUsuarios
 * @property Posts[] $posts
 */
class Usuarios extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rol_id'], 'default', 'value' => 1316],
            [['rol_id'], 'integer'],
            [['user', 'pwd', 'birthday', 'auth_key'], 'required'],
            [['birthday', 'created_at'], 'safe'],
            [['user', 'pwd'], 'string', 'max' => 180],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'rol_id' => Yii::t('app', 'Rol ID'),
            'user' => Yii::t('app', 'User'),
            'pwd' => Yii::t('app', 'Pwd'),
            'birthday' => Yii::t('app', 'Birthday'),
            'created_at' => Yii::t('app', 'Created At'),
            'auth_key' => Yii::t('app', 'Auth Key'),
        ];
    }

    /**
     * Gets query for [[Notificaciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotificaciones()
    {
        return $this->hasMany(Notificaciones::class, ['receptor_id' => 'id']);
    }

    /**
     * Gets query for [[PayHistorics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayHistorics()
    {
        return $this->hasMany(PayHistoric::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[PerfilUsuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPerfilUsuarios()
    {
        return $this->hasMany(PerfilUsuario::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Posts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Posts::class, ['usuario_id' => 'id']);
    }

}
