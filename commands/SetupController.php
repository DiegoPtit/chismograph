<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\FileHelper;

class SetupController extends Controller
{
    public function actionCreateUploadsDir()
    {
        $uploadsDir = \Yii::getAlias('@app/web/uploads');
        
        if (!file_exists($uploadsDir)) {
            if (FileHelper::createDirectory($uploadsDir, 0777, true)) {
                echo "Directorio de uploads creado exitosamente.\n";
            } else {
                echo "Error al crear el directorio de uploads.\n";
                return ExitCode::UNSPECIFIED_ERROR;
            }
        } else {
            echo "El directorio de uploads ya existe.\n";
        }
        
        return ExitCode::OK;
    }
} 