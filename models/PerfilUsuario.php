<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "perfil_usuario".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $foto_portada
 * @property string|null $foto_perfil
 * @property float|null $cred_index
 * @property string|null $descripcion
 * @property string|null $pais
 * @property int|null $edad
 * @property string|null $genero
 * @property string|null $gustos
 * @property string|null $motivo
 *
 * @property Usuarios $user
 */
class PerfilUsuario extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'perfil_usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['foto_portada', 'foto_perfil', 'descripcion', 'pais', 'edad', 'genero', 'gustos', 'motivo'], 'default', 'value' => null],
            [['cred_index'], 'default', 'value' => 0],
            [['user_id'], 'required'],
            [['user_id', 'edad'], 'integer'],
            [['cred_index'], 'number'],
            [['descripcion'], 'string'],
            [['gustos', 'motivo'], 'safe'],
            [['foto_portada', 'foto_perfil'], 'string', 'max' => 255],
            [['pais', 'genero'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'foto_portada' => Yii::t('app', 'Foto Portada'),
            'foto_perfil' => Yii::t('app', 'Foto Perfil'),
            'cred_index' => Yii::t('app', 'Cred Index'),
            'descripcion' => Yii::t('app', 'Descripcion'),
            'pais' => Yii::t('app', 'Pais'),
            'edad' => Yii::t('app', 'Edad'),
            'genero' => Yii::t('app', 'Genero'),
            'gustos' => Yii::t('app', 'Gustos'),
            'motivo' => Yii::t('app', 'Motivo'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Usuarios::class, ['id' => 'user_id']);
    }

}
