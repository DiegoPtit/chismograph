<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tabulador".
 *
 * @property int $nivel
 * @property string $nombre
 */
class Tabulador extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tabulador';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nivel', 'nombre'], 'required'],
            [['nivel'], 'integer'],
            [['nombre'], 'string', 'max' => 20],
            [['nivel'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nivel' => Yii::t('app', 'Nivel'),
            'nombre' => Yii::t('app', 'Nombre'),
        ];
    }

}
