<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use app\models\Usuarios;
use app\models\PerfilUsuario;

$this->title = 'Iniciar Sesión';
$this->params['breadcrumbs'][] = $this->title;

// Lista de países
$paises = [
    'AR' => 'Argentina',
    'BO' => 'Bolivia',
    'BR' => 'Brasil',
    'CL' => 'Chile',
    'CO' => 'Colombia',
    'CR' => 'Costa Rica',
    'CU' => 'Cuba',
    'EC' => 'Ecuador',
    'SV' => 'El Salvador',
    'ES' => 'España',
    'GT' => 'Guatemala',
    'HN' => 'Honduras',
    'MX' => 'México',
    'NI' => 'Nicaragua',
    'PA' => 'Panamá',
    'PY' => 'Paraguay',
    'PE' => 'Perú',
    'PR' => 'Puerto Rico',
    'DO' => 'República Dominicana',
    'UY' => 'Uruguay',
    'VE' => 'Venezuela',
];

$generos = [
    0 => 'Prefiero no decir',
    1 => 'Hombre',
    2 => 'Mujer',
];

// Registrar estilos CSS
$this->registerCss(<<<CSS
    /* Estilos base para el modal y backdrop */
    .modal-backdrop {
        position: fixed !important;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: transparent !important;
        z-index: 1040 !important;
        pointer-events: none !important;
    }

    .modal {
        z-index: 1050 !important;
        padding-top: 80px !important;
    }

    .modal-dialog {
        z-index: 1051 !important;
    }

    .modal-content {
        z-index: 1052 !important;
        position: relative;
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Asegurar que el backdrop no interfiera con los clics */
    .modal-backdrop.show {
        pointer-events: none !important;
        background-color: transparent !important;
    }

    /* Hacer que el contenido del modal sea clickeable */
    .modal, .modal-dialog, .modal-content {
        pointer-events: auto !important;
    }

    /* Animaciones */
    .modal.fade .modal-dialog {
        transform: scale(0.8);
        transition: transform 0.3s ease-in-out;
    }

    .modal.show .modal-dialog {
        transform: scale(1);
    }

    /* Asegurar que el modal esté por encima del backdrop */
    .modal.show {
        z-index: 1050 !important;
    }

    .modal.show .modal-dialog {
        z-index: 1051 !important;
    }

    .modal.show .modal-content {
        z-index: 1052 !important;
    }

    /* Ajustes para móviles */
    @media (max-width: 767px) {
        .modal {
            padding-top: 60px !important;
        }
        
        .modal-dialog {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }
        
        .modal-body {
            padding: 1rem;
            max-height: 80vh;
        }
        
        .modal-header,
        .modal-footer {
            padding: 1rem;
        }
    }
CSS
);
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Por favor complete los siguientes campos para ingresar:</p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton('Ingresar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    <?= Html::button('Registrarse', ['class' => 'btn btn-success', 'data-bs-toggle' => 'modal', 'data-bs-target' => '#registroModal']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<?php Modal::begin([
    'id' => 'registroModal',
    'title' => '<h4>Registro de Usuario</h4>',
    'size' => Modal::SIZE_LARGE,
]); ?>

<div class="registro-wizard">
    <?php $form = ActiveForm::begin([
        'id' => 'registro-form',
        'action' => ['site/register'],
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <?php $modelRegistro = new Usuarios(); ?>
    <?php $modelPerfil = new PerfilUsuario(); ?>

    <!-- Fase 1: Información básica -->
    <div class="fase" id="fase1">
        <h5>Paso 1: Información de Cuenta</h5>
        <?= $form->field($modelRegistro, 'user')->textInput(['maxlength' => true]) ?>
        <?= $form->field($modelRegistro, 'pwd')->passwordInput(['maxlength' => true]) ?>
        <?= $form->field($modelRegistro, 'birthday')->input('date') ?>
        <div class="form-group mt-3">
            <?= Html::button('Siguiente', ['class' => 'btn btn-primary', 'onclick' => 'siguienteFase(1)']) ?>
        </div>
    </div>

    <!-- Fase 2: Información de perfil -->
    <div class="fase" id="fase2" style="display: none;">
        <h5>Paso 2: Información de Perfil</h5>
        <?= $form->field($modelPerfil, 'foto_perfil')->fileInput(['accept' => 'image/*']) ?>
        <?= $form->field($modelPerfil, 'pais')->dropDownList($paises, ['prompt' => 'Seleccione un país']) ?>
        <?= $form->field($modelPerfil, 'genero')->dropDownList($generos, ['prompt' => 'Seleccione su género']) ?>
        <div class="form-group mt-3">
            <?= Html::button('Anterior', ['class' => 'btn btn-secondary', 'onclick' => 'anteriorFase(2)']) ?>
            <?= Html::submitButton('Registrar', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
.fase {
    transition: all 0.3s ease;
}
</style>

<script>
function siguienteFase(fase) {
    document.getElementById('fase' + fase).style.display = 'none';
    document.getElementById('fase' + (fase + 1)).style.display = 'block';
}

function anteriorFase(fase) {
    document.getElementById('fase' + fase).style.display = 'none';
    document.getElementById('fase' + (fase - 1)).style.display = 'block';
}
</script>

<?php
// Registrar scripts para manejar el modal
$this->registerJs(<<<JS
    $(document).ready(function() {
        // Limpiar modales y backdrops al cargar la página
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        
        // Asegurar que los modales sean clickeables
        $('.modal').css('pointer-events', 'auto');
        $('.modal .modal-content').css('pointer-events', 'auto');
        
        // Manejar clics en los modales
        $('.modal').on('click', function(e) {
            e.stopPropagation();
        });
        
        // Manejar clics en el contenido de los modales
        $('.modal .modal-content').on('click', function(e) {
            e.stopPropagation();
        });
        
        // Manejar la apertura de modales
        $('.modal').on('show.bs.modal', function() {
            // Remover cualquier backdrop existente
            $('.modal-backdrop').remove();
            
            // Crear un nuevo backdrop transparente
            $('<div>')
                .addClass('modal-backdrop fade show')
                .css({
                    'z-index': '1040',
                    'background-color': 'transparent',
                    'pointer-events': 'none'
                })
                .appendTo('body');
        });
        
        // Manejar el cierre de modales
        $('.modal').on('hidden.bs.modal', function() {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        });
    });
JS
);
?>

<?php Modal::end(); ?>