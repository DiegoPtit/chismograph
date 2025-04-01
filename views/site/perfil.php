<?php
/* @var $this yii\web\View */
/* @var $model app\models\PerfilUsuario */
/* @var $posts app\models\Posts[] */

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use kartik\date\DatePicker;

$this->title = 'Mi Perfil';
$this->params['breadcrumbs'][] = $this->title;

// Generar la URL para la actualización de la foto de perfil
$updatePhotoUrl = Yii::$app->urlManager->createUrl(['site/update-profile-photo']);
$updateProfileInfoUrl = Yii::$app->urlManager->createUrl(['site/update-profile-info']);
$csrfToken = Yii::$app->request->csrfToken;

// Generar la URL base para las imágenes
$baseImageUrl = Yii::getAlias('@web/uploads/');

// URL para descripción
$updateDescriptionUrl = Yii::$app->urlManager->createUrl(['site/update-profile-info']);

// Preparar los datos JSON para JavaScript
$gustosJson = $model->gustos ? $model->gustos : '[]';
$motivosJson = $model->motivo ? $model->motivo : '[]';
$descripcion = $model->descripcion ? addslashes($model->descripcion) : '';

// Registrar CSS
$this->registerCss(<<<CSS
    .profile-header {
        position: relative;
        margin-bottom: 2rem;
    }
    
    .cover-photo {
        height: 300px;
        background-size: cover;
        background-position: center;
        border-radius: 15px 15px 0 0;
        position: relative;
        z-index: 1;
    }
    
    .profile-info-container {
        background: #fff;
        border-radius: 0 0 15px 15px;
        padding: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        position: relative;
        z-index: 2;
        gap: 1rem;
    }
    
    .profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid #fff;
        margin-top: -60px;
        margin-left: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        flex-shrink: 0;
        position: relative;
        z-index: 3;
    }
    
    @media (max-width: 768px) {
        .profile-photo {
            width: 100px;
            height: 100px;
            margin-top: -50px;
            margin-left: 10px;
        }
        
        .user-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
            width: 100%;
        }
        
        .user-name {
            margin-top: -50px;
            margin-left: 10px;
        }
        
        .user-name h1 {
            font-size: 1.5rem;
            margin-bottom: 0;
        }
        
        .user-badges {
            width: 100%;
            justify-content: flex-start;
            flex-wrap: wrap;
            margin-left: 10px;
        }
        
        .profile-info-container {
            padding: 0.75rem;
        }
    }
    
    .user-info {
        flex-grow: 1;
        margin-left: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    
    .user-name {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .user-name h1 {
        margin: 0;
        font-size: 1.75rem;
    }
    
    .user-name img {
        height: 20px;
        width: auto;
    }
    
    .user-badges {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem;
        border-radius: 25px;
        backdrop-filter: blur(5px);
        flex-wrap: wrap;
    }
    
    .credibility-section {
        margin-top: 1rem;
    }
    
    .credibility-bar {
        height: 20px;
        background: linear-gradient(to right, #ff4444, #ffbb33, #00C851);
        border-radius: 10px;
        position: relative;
        overflow: visible;
        margin: 1rem 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    .credibility-indicator {
        position: absolute;
        width: 10px;
        height: 100%;
        background: #fff;
        border: 2px solid #333;
        border-radius: 5px;
        cursor: pointer;
        transform: translateX(-50%);
        left: 0;
        transition: left 0.3s ease;
        box-sizing: border-box;
    }
    
    .credibility-text {
        position: absolute;
        top: -25px;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .credibility-text-left {
        left: 10px;
        color: #ff4444;
    }
    
    .credibility-text-right {
        right: 10px;
        color: #00C851;
    }
    
    .golden-bar {
        background: linear-gradient(45deg, #FFD700, #FFA500);
    }
    
    .credibility-tooltip {
        position: absolute;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
        white-space: nowrap;
        pointer-events: none;
        z-index: 1000;
    }
    
    .profile-sidebar {
        background: #fff;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .interests-list {
        list-style-type: none;
        padding: 0;
    }
    
    .interests-list li {
        margin-bottom: 0.5rem;
        padding-left: 1.5rem;
        position: relative;
    }
    
    .interests-list li:before {
        content: "•";
        position: absolute;
        left: 0;
        color: #4a90e2;
    }
    
    .motives-badge {
        display: inline-block;
        margin: 0.25rem;
        padding: 0.5rem 1rem;
        background: #f8f9fa;
        border-radius: 20px;
        font-size: 0.9rem;
    }
    
    .shield-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 1rem;
        border-radius: 20px;
        font-weight: 500;
        gap: 0.5rem;
    }
    
    .shield-badge i {
        font-size: 1.2rem;
    }
    
    .shield-bronze {
        background: linear-gradient(45deg, #CD7F32, #B87333);
        color: white;
    }
    
    .shield-hierro {
        background: linear-gradient(45deg, #4A4A4A, #2C2C2C);
        color: white;
    }
    
    .shield-plata {
        background: linear-gradient(45deg, #C0C0C0, #A8A8A8);
        color: #333;
    }
    
    .shield-oro {
        background: linear-gradient(45deg, #FFD700, #FFA500);
        color: #333;
    }
    
    .shield-diamante {
        background: linear-gradient(45deg, #B9F2FF, #00BFFF);
        color: #333;
    }
    
    .shield-master {
        background: linear-gradient(45deg, #FF0000, #8B0000);
        color: white;
    }
    
    .shield-info {
        background: linear-gradient(45deg, #6c757d, #495057);
        color: white;
    }

    .btn-outline-primary {
        border-color: #4a90e2;
        color: #4a90e2;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover {
        background-color: #4a90e2;
        color: white;
        transform: translateY(-1px);
    }

    .btn-outline-primary i {
        margin-right: 0.5rem;
    }

    .edit-btn {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        padding: 0;
    }

    .edit-btn i {
        margin: 0;
        font-size: 0.9rem;
    }

    .edit-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .mb-4 {
        position: relative;
    }

    .d-flex {
        width: 100%;
    }
CSS
);

// Registrar JavaScript
$this->registerJs(<<<JS
    console.log('Script iniciado');
    
    $(document).ready(function() {
        console.log('Documento listo');
        
        // Verificar que los elementos existen
        console.log('Botón guardar descripción:', $('#guardarDescripcion').length);
        console.log('Modal descripción:', $('#editDescriptionModal').length);
        
        let gustosSeleccionados = new Set();
        let motivosSeleccionados = new Set();

        // Configurar el token CSRF para todas las llamadas AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': '{$csrfToken}'
            }
        });

        // Función para mostrar mensaje de éxito
        function mostrarMensajeExito(modalId) {
            console.log('Mostrando mensaje de éxito para:', modalId);
            $('#' + modalId + ' .btn-primary').addClass('d-none');
            $('#' + modalId + ' .btn-success').removeClass('d-none').text('¡Guardado! Cerrar');
        }

        // Función para manejar errores
        function manejarError(error) {
            console.error('Error:', error);
            alert('Ha ocurrido un error al guardar los cambios. Por favor, intente nuevamente.');
        }

        // Función para cerrar el modal después de guardar
        function cerrarModal(modalId) {
            console.log('Cerrando modal:', modalId);
            setTimeout(function() {
                $('#' + modalId).modal('hide');
                window.location.reload();
            }, 1500);
        }

        // Guardar descripción
        $('#guardarDescripcion').on('click', function(e) {
            console.log('Botón guardar descripción clickeado');
            e.preventDefault();
            e.stopPropagation();
            
            const descripcion = $('#descripcion').val().trim();
            console.log('Descripción a guardar:', descripcion);
            
            if (!descripcion) {
                alert('Por favor, ingrese una descripción');
                return;
            }
            
            console.log('Enviando petición AJAX');
            $.ajax({
                url: '{$updateProfileInfoUrl}',
                type: 'POST',
                data: {
                    descripcion: descripcion,
                    _csrf: '{$csrfToken}'
                },
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    if (response.success) {
                        mostrarMensajeExito('editDescriptionModal');
                        cerrarModal('editDescriptionModal');
                    } else {
                        manejarError(response.message || 'Error al guardar la descripción');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', xhr.responseText, status, error);
                    manejarError(error);
                }
            });
        });

        // Guardar gustos
        $('#guardarGustos').on('click', function(e) {
            console.log('Botón guardar gustos clickeado');
            e.preventDefault();
            e.stopPropagation();
            
            const gustosArray = Array.from(gustosSeleccionados);
            console.log('Gustos a guardar:', gustosArray);
            
            if (gustosArray.length === 0) {
                alert('Por favor, seleccione al menos un gusto');
                return;
            }
            
            console.log('Enviando petición AJAX');
            $.ajax({
                url: '{$updateProfileInfoUrl}',
                type: 'POST',
                data: {
                    gustos: JSON.stringify(gustosArray),
                    _csrf: '{$csrfToken}'
                },
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    if (response.success) {
                        mostrarMensajeExito('editGustosModal');
                        cerrarModal('editGustosModal');
                    } else {
                        manejarError(response.message || 'Error al guardar los gustos');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', xhr.responseText, status, error);
                    manejarError(error);
                }
            });
        });

        // Guardar motivos
        $('#guardarMotivos').on('click', function(e) {
            console.log('Botón guardar motivos clickeado');
            e.preventDefault();
            e.stopPropagation();
            
            const motivosArray = Array.from(motivosSeleccionados);
            console.log('Motivos a guardar:', motivosArray);
            
            if (motivosArray.length === 0) {
                alert('Por favor, seleccione al menos un motivo');
                return;
            }
            
            console.log('Enviando petición AJAX');
            $.ajax({
                url: '{$updateProfileInfoUrl}',
                type: 'POST',
                data: {
                    motivo: JSON.stringify(motivosArray),
                    _csrf: '{$csrfToken}'
                },
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    if (response.success) {
                        mostrarMensajeExito('editMotivosModal');
                        cerrarModal('editMotivosModal');
                    } else {
                        manejarError(response.message || 'Error al guardar los motivos');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', xhr.responseText, status, error);
                    manejarError(error);
                }
            });
        });

        // Inicializar modales
        var modals = document.querySelectorAll('.modal');
        console.log('Modales encontrados:', modals.length);
        modals.forEach(modal => {
            modal._backdrop = false;
        });

        // Eventos para cargar datos en los modales cuando se abren
        $('#editDescriptionModal').on('show.bs.modal', function (event) {
            console.log('Modal de descripción abierto');
            $('#descripcion').val("{$model->descripcion}");
        });

        $('#editGustosModal').on('show.bs.modal', function (event) {
            console.log('Modal de gustos abierto');
            gustosSeleccionados = new Set();
            $('#gustosInput').val('');
            $('.gustos-buttons button').removeClass('active');
            
            try {
                <?php if ($model->gustos): ?>
                var gustosActuales = <?= $model->gustos ?>;
                if (Array.isArray(gustosActuales)) {
                    gustosActuales.forEach(function(gusto) {
                        gustosSeleccionados.add(gusto);
                        $('.gustos-buttons button[data-gusto="' + gusto + '"]').addClass('active');
                    });
                    $('#gustosInput').val(Array.from(gustosSeleccionados).join(', '));
                }
                <?php endif; ?>
            } catch (e) {
                console.error('Error al cargar gustos:', e);
            }
        });

        $('#editMotivosModal').on('show.bs.modal', function (event) {
            console.log('Modal de motivos abierto');
            motivosSeleccionados = new Set();
            $('#motivosInput').val('');
            $('.motivos-buttons button').removeClass('active');
            
            try {
                <?php if ($model->motivo): ?>
                var motivosActuales = <?= $model->motivo ?>;
                if (Array.isArray(motivosActuales)) {
                    motivosActuales.forEach(function(motivo) {
                        motivosSeleccionados.add(motivo);
                        $('.motivos-buttons button[data-motivo="' + motivo + '"]').addClass('active');
                    });
                    $('#motivosInput').val(Array.from(motivosSeleccionados).join(', '));
                }
                <?php endif; ?>
            } catch (e) {
                console.error('Error al cargar motivos:', e);
            }
        });

        // Manejar gustos
        $('.gustos-buttons button').click(function() {
            console.log('Botón de gusto clickeado:', $(this).data('gusto'));
            const gusto = $(this).data('gusto');
            if (gusto === 'Otros') {
                $('#otrosGustoInput').toggle();
                return;
            }
            
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                gustosSeleccionados.delete(gusto);
            } else {
                $(this).addClass('active');
                gustosSeleccionados.add(gusto);
            }
            
            actualizarGustosInput();
        });

        $('#addCustomGusto').click(function() {
            const customGusto = $('#customGusto').val().trim();
            if (customGusto) {
                gustosSeleccionados.add(customGusto);
                actualizarGustosInput();
                $('#customGusto').val('');
                $('#otrosGustoInput').hide();
            }
        });

        function actualizarGustosInput() {
            $('#gustosInput').val(Array.from(gustosSeleccionados).join(', '));
        }

        // Manejar motivos
        $('.motivos-buttons button').click(function() {
            console.log('Botón de motivo clickeado:', $(this).data('motivo'));
            const motivo = $(this).data('motivo');
            
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                motivosSeleccionados.delete(motivo);
            } else {
                $(this).addClass('active');
                motivosSeleccionados.add(motivo);
            }
            
            actualizarMotivosInput();
        });

        function actualizarMotivosInput() {
            $('#motivosInput').val(Array.from(motivosSeleccionados).join(', '));
        }
    });
JS
);

// Función auxiliar para obtener el badge del nivel
function getNivelBadge($nivel) {
    $badges = [
        0 => ['class' => 'shield-bronze', 'icon' => 'shield-alt'],
        3 => ['class' => 'shield-hierro', 'icon' => 'shield-alt'],
        5 => ['class' => 'shield-plata', 'icon' => 'shield-alt'],
        7 => ['class' => 'shield-oro', 'icon' => 'shield-alt'],
        9 => ['class' => 'shield-diamante', 'icon' => 'shield-alt'],
        10 => ['class' => 'shield-master', 'icon' => 'shield-alt']
    ];
    
    // Encontrar el nivel más cercano menor o igual al nivel actual
    $nivelActual = 0;
    foreach ($badges as $n => $badge) {
        if ($nivel >= $n) {
            $nivelActual = $n;
        }
    }
    
    if (isset($badges[$nivelActual])) {
        $badge = $badges[$nivelActual];
        return Html::tag('span', 
            '<i class="fas fa-' . $badge['icon'] . '"></i>', 
            ['class' => 'shield-badge ' . $badge['class']]
        );
    }
    
    return '';
}

// Función para obtener el ícono de género
function getGenderIcon($genero) {
    switch ($genero) {
        case 1:
            return '<i class="fas fa-mars text-primary"></i>';
        case 2:
            return '<i class="fas fa-venus text-danger"></i>';
        default:
            return '<i class="fas fa-user-secret text-secondary"></i>';
    }
}

// Función auxiliar para obtener el nombre del rango
function getRangoNombre($nivel) {
    if ($nivel >= 10) return 'Nivel de Credibilidad: VIP';
    if ($nivel >= 9) return 'Nivel de Credibilidad: DIAMANTE';
    if ($nivel >= 7) return 'Nivel de Credibilidad: ORO';
    if ($nivel >= 5) return 'Nivel de Credibilidad: PLATA';
    if ($nivel >= 3) return 'Nivel de Credibilidad: HIERRO';
    return 'Nivel de Credibilidad: BRONCE';
}

// Obtener el nivel basado en cred_index
$nivel = floor($model->cred_index);
$nivelBadge = getNivelBadge($nivel);
$rangoNombre = getRangoNombre($nivel);

// Calcular el porcentaje para la barra de credibilidad
$porcentaje = 0;
if ($nivel >= 10) {
    $porcentaje = 100;
} elseif ($nivel >= 9) {
    $porcentaje = 90;
} elseif ($nivel >= 7) {
    $porcentaje = 70;
} elseif ($nivel >= 5) {
    $porcentaje = 50;
} elseif ($nivel >= 3) {
    $porcentaje = 30;
} elseif ($nivel >= 0) {
    $porcentaje = 0;
}
?>

<div class="profile-view">
    <div class="profile-header">
        <div class="cover-photo" style="background-image: url('<?= $model->foto_portada ? Yii::getAlias('@web/uploads/') . $model->foto_portada : 'none' ?>'); background-color: <?= $model->foto_portada ? 'transparent' : '#2c3e50' ?>">
            <div class="cover-camera-icon" id="coverCameraIcon">
                <i class="fas fa-camera"></i>
                <div class="cover-dropdown-menu">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#changeCoverPhotoModal">
                        <i class="fas fa-image"></i> Cambiar foto de portada
                    </a>
                    <a href="#" id="deleteCoverPhoto">
                        <i class="fas fa-trash"></i> Eliminar foto de portada
                    </a>
                </div>
            </div>
        </div>
        <div class="profile-info-container">
            <div class="profile-photo">
                <?php if ($model->foto_perfil): ?>
                    <img src="<?= Yii::getAlias('@web/uploads/') . $model->foto_perfil ?>" 
                         alt="Foto de perfil"
                         class="img-fluid rounded-circle"
                         style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <img src="<?= Yii::getAlias('@web/uploads/default-avatar.jpg') ?>" 
                         alt="Foto de perfil por defecto"
                         class="img-fluid rounded-circle"
                         style="width: 100%; height: 100%; object-fit: cover;">
                <?php endif; ?>
                <div class="camera-icon" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-camera"></i>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changeProfilePhotoModal">
                                <i class="fas fa-user-circle"></i> Cambiar foto de perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" id="deleteProfilePhoto">
                                <i class="fas fa-trash"></i> Eliminar foto de perfil
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="user-info">
                <div class="user-name">
                    <h1>
                        <?= Html::encode($model->user->user) ?>
                        <?= Html::tag('span', 
                            $nivelBadge, 
                            [
                                'data-toggle' => 'tooltip',
                                'data-placement' => 'top',
                                'title' => $rangoNombre
                            ]
                        ) ?>
                    </h1>
                </div>
                
                <div class="user-badges">
                    <img src="https://flagcdn.com/24x18/<?= strtolower($model->pais) ?>.png" 
                         alt="<?= Html::encode($model->pais) ?>" 
                         title="<?= Html::encode($model->pais) ?>"
                         class="ml-2">
                    <span class="shield-badge shield-info">
                        <?= getGenderIcon($model->genero) ?> <?= $model->edad ?> años
                    </span>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="fas fa-edit"></i> Editar Información del Perfil
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="profile-sidebar">
                <div class="mb-4">
                    <h5>Nivel de Credibilidad</h5>
                    <br>
                    <div class="credibility-bar <?= $nivel >= 10 ? 'golden-bar' : '' ?>">
                        <span class="credibility-text credibility-text-left">No fiable</span>
                        <span class="credibility-text credibility-text-right">Fiable</span>
                        <div class="credibility-indicator" 
                             style="left: <?= $porcentaje ?>%"
                             data-toggle="tooltip"
                             data-placement="top"
                             title="<?= 100 - $porcentaje ?>% No Fiable / <?= $porcentaje ?>% Fiable">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5>Sobre mí</h5>
                    <?php if ($model->descripcion): ?>
                        <p style="white-space: pre-line;"><?= Html::encode($model->descripcion) ?></p>
                        <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-2" id="editDescriptionBtn" data-bs-toggle="modal" data-bs-target="#editDescriptionModal">
                            <i class="fas fa-edit"></i> Editar descripción
                        </button>
                    <?php else: ?>
                        <div class="text-center">
                            <p class="text-muted mb-2">No hay descripción</p>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addDescriptionBtn">
                                Añadir Descripción
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <h5>Mis gustos</h5>
                    <?php if ($model->gustos): ?>
                        <ul class="interests-list">
                            <?php 
                            $gustos = json_decode($model->gustos, true);
                            if (is_array($gustos)) {
                                foreach ($gustos as $gusto): 
                                    echo '<li>' . Html::encode($gusto) . '</li>';
                                endforeach;
                            }
                            ?>
                        </ul>
                        <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-2" id="editGustosBtn" data-bs-toggle="modal" data-bs-target="#editGustosModal">
                            <i class="fas fa-edit"></i> Editar gustos
                        </button>
                    <?php else: ?>
                        <div class="text-center">
                            <p class="text-muted mb-2">No hay gustos definidos</p>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addGustosBtn">
                                Añadir Gustos
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <h5>Mis motivos</h5>
                    <?php if ($model->motivo): ?>
                        <div class="motives-container">
                            <?php foreach (json_decode($model->motivo, true) as $motivo): ?>
                                <span class="motives-badge">
                                    <?= Html::encode($motivo) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-2" id="editMotivosBtn" data-bs-toggle="modal" data-bs-target="#editMotivosModal">
                            <i class="fas fa-edit"></i> Editar motivos
                        </button>
                    <?php else: ?>
                        <div class="text-center">
                            <p class="text-muted mb-2">No hay motivos definidos</p>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addMotivosBtn">
                                Añadir Motivos
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="posts-section">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <?= $this->render('_post', ['post' => $post]) ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        No hay publicaciones para mostrar.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar foto de perfil -->
<div class="modal fade" id="changeProfilePhotoModal" tabindex="-1" aria-labelledby="changeProfilePhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeProfilePhotoModalLabel">Cambiar foto de perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Indicador de progreso -->
                <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <!-- Contenedor de fases -->
                <div class="phase-container">
                    <!-- Fase 1: Selección de archivo -->
                    <div class="phase active" id="profilePhase1">
                        <div class="upload-area" id="dropZone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Arrastre o de clic para adjuntar una foto de perfil</p>
                            <input type="file" id="profilePhotoInput" accept="image/jpeg,image/png" style="display: none;">
                        </div>
                    </div>

                    <!-- Fase 2: Recorte de imagen -->
                    <div class="phase" id="profilePhase2">
                        <div class="cropper-container">
                            <img id="profileCropperImage" src="" alt="Imagen para recortar" style="max-width: 100%;">
                        </div>
                    </div>

                    <!-- Fase 3: Previsualización y confirmación -->
                    <div class="phase" id="profilePhase3">
                        <div class="preview-container text-center">
                            <img id="profilePreview" src="" alt="Preview" style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover;">
                        </div>
                    </div>
                </div>

                <!-- Navegación entre fases -->
                <div class="phase-navigation mt-3">
                    <button type="button" class="btn btn-secondary" id="profilePrevPhase" style="display: none;">Anterior</button>
                    <button type="button" class="btn btn-primary" id="profileNextPhase">Siguiente</button>
                    <button type="button" class="btn btn-success" id="profilesavePhoto" style="display: none;">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar foto de portada -->
<div class="modal fade" id="changeCoverPhotoModal" tabindex="-1" aria-labelledby="changeCoverPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeCoverPhotoModalLabel">Cambiar foto de portada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Indicador de progreso -->
                <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <!-- Contenedor de fases -->
                <div class="phase-container">
                    <!-- Fase 1: Selección de archivo -->
                    <div class="phase active" id="coverPhase1">
                        <div class="upload-area" id="coverDropZone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Arrastre o de clic para adjuntar una foto de portada</p>
                            <input type="file" id="coverPhotoInput" accept="image/jpeg,image/png" style="display: none;">
                        </div>
                    </div>

                    <!-- Fase 2: Recorte de imagen -->
                    <div class="phase" id="coverPhase2">
                        <div class="cropper-container">
                            <img id="coverCropperImage" src="" alt="Imagen para recortar" style="max-width: 100%;">
                        </div>
                    </div>

                    <!-- Fase 3: Previsualización y confirmación -->
                    <div class="phase" id="coverPhase3">
                        <div class="preview-container text-center">
                            <img id="coverPreview" src="" alt="Preview" style="width: 100%; max-height: 300px; object-fit: cover;">
                        </div>
                    </div>
                </div>

                <!-- Navegación entre fases -->
                <div class="phase-navigation mt-3">
                    <button type="button" class="btn btn-secondary" id="coverPrevPhase" style="display: none;">Anterior</button>
                    <button type="button" class="btn btn-primary" id="coverNextPhase">Siguiente</button>
                    <button type="button" class="btn btn-success" id="coversavePhoto" style="display: none;">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar información del perfil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Editar Información del Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm">
                    <div class="mb-3">
                        <label for="pais" class="form-label">País</label>
                        <select class="form-select" id="pais" name="pais" required>
                            <option value="">Seleccione un país</option>
                            <?php
                            $paises = [
                                'AR' => 'Argentina',
                                'BO' => 'Bolivia',
                                'CL' => 'Chile',
                                'CO' => 'Colombia',
                                'CR' => 'Costa Rica',
                                'CU' => 'Cuba',
                                'DO' => 'República Dominicana',
                                'EC' => 'Ecuador',
                                'SV' => 'El Salvador',
                                'GT' => 'Guatemala',
                                'HN' => 'Honduras',
                                'MX' => 'México',
                                'NI' => 'Nicaragua',
                                'PA' => 'Panamá',
                                'PY' => 'Paraguay',
                                'PE' => 'Perú',
                                'PR' => 'Puerto Rico',
                                'ES' => 'España',
                                'UY' => 'Uruguay',
                                'VE' => 'Venezuela'
                            ];
                            foreach ($paises as $codigo => $nombre) {
                                $selected = ($model->pais === $codigo) ? 'selected' : '';
                                echo "<option value=\"$codigo\" $selected>$nombre</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                               max="<?= date('Y-m-d') ?>" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveProfileInfo">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar descripción -->
<div class="modal fade" id="editDescriptionModal" tabindex="-1" aria-labelledby="editDescriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDescriptionModalLabel">Editar Descripción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDescriptionForm">
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">¡Preséntate!</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required 
                                  data-current-value="<?= Html::encode($model->descripcion) ?>"><?= Html::encode($model->descripcion) ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarDescripcion">Guardar</button>
                <button type="button" class="btn btn-success d-none" id="guardadoDescripcion" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar gustos -->
<div class="modal fade" id="editGustosModal" tabindex="-1" aria-labelledby="editGustosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGustosModalLabel">Editar Gustos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="gustosInput" readonly 
                           data-current-value='<?= $model->gustos ?: '[]' ?>'>
                </div>
                <div class="gustos-buttons">
                    <button type="button" class="btn btn-outline-primary m-2" data-gusto="Deportes"><i class="fas fa-running"></i> Deportes</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-gusto="Música"><i class="fas fa-music"></i> Música</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-gusto="Cine"><i class="fas fa-film"></i> Cine</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-gusto="Lectura"><i class="fas fa-book"></i> Lectura</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-gusto="Cocina"><i class="fas fa-utensils"></i> Cocina</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-gusto="Viajes"><i class="fas fa-plane"></i> Viajes</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-gusto="Fotografía"><i class="fas fa-camera"></i> Fotografía</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-gusto="Arte"><i class="fas fa-palette"></i> Arte</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-gusto="Tecnología"><i class="fas fa-laptop"></i> Tecnología</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-gusto="Juegos"><i class="fas fa-gamepad"></i> Juegos</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-gusto="Otros"><i class="fas fa-plus"></i> Otros</button>
                </div>
                <div class="mt-3" id="otrosGustoInput" style="display: none;">
                    <input type="text" class="form-control" id="customGusto" placeholder="Escribe tu gusto personalizado">
                    <button type="button" class="btn btn-primary mt-2" id="addCustomGusto">Agregar</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarGustos">Guardar</button>
                <button type="button" class="btn btn-success d-none" id="guardadoGustos" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar motivos -->
<div class="modal fade" id="editMotivosModal" tabindex="-1" aria-labelledby="editMotivosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMotivosModalLabel">Editar Motivos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="motivosInput" readonly 
                           data-current-value='<?= $model->motivo ?: '[]' ?>'>
                </div>
                <div class="motivos-buttons">
                    <button type="button" class="btn btn-outline-primary m-2" data-motivo="Amigos"><i class="fas fa-users"></i> Amigos</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-motivo="Informar"><i class="fas fa-newspaper"></i> Informar</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-motivo="Relaciones Formales"><i class="fas fa-heart"></i> Relaciones Formales</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-motivo="Chistes"><i class="fas fa-laugh"></i> Chistes</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-motivo="Bromas"><i class="fas fa-theater-masks"></i> Bromas</button>
                    <button type="button" class="btn btn-outline-primary m-2" data-motivo="Convivencia Sana"><i class="fas fa-handshake"></i> Convivencia Sana</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarMotivos">Guardar</button>
                <button type="button" class="btn btn-success d-none" id="guardadoMotivos" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
.camera-icon {
    position: absolute;
    bottom: 0;
    right: 0;
    background: #4a90e2;
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.camera-icon:hover {
    transform: scale(1.1);
    background: #357abd;
}

.camera-icon i {
    font-size: 1.2rem;
}

.dropdown-menu {
    min-width: 200px;
    padding: 0.5rem 0;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    color: #333;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: #f8f9fa;
    color: #4a90e2;
}

.dropdown-item i {
    margin-right: 0.5rem;
    color: #4a90e2;
}

.upload-area {
    border: 2px dashed #4a90e2;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.upload-area:hover {
    background: #e9ecef;
    border-color: #357abd;
}

.upload-area i {
    font-size: 3rem;
    color: #4a90e2;
    margin-bottom: 1rem;
}

.upload-area p {
    margin: 0;
    color: #6c757d;
}

#previewContainer {
    margin-top: 1rem;
    text-align: center;
}

#imagePreview {
    max-width: 200px;
    max-height: 200px;
    border-radius: 50%;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Estilos para el backdrop del modal */
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

.modal-backdrop.show {
    pointer-events: none !important;
    background-color: transparent !important;
}

.modal, .modal-dialog, .modal-content {
    pointer-events: auto !important;
}

.modal.fade .modal-dialog {
    transform: scale(0.8);
    transition: transform 0.3s ease-in-out;
}

.modal.show .modal-dialog {
    transform: scale(1);
}

.modal.show {
    z-index: 1050 !important;
}

.modal.show .modal-dialog {
    z-index: 1051 !important;
}

.modal.show .modal-content {
    z-index: 1052 !important;
}

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

.cover-camera-icon {
    position: absolute;
    bottom: 20px;
    right: 20px;
    background: rgba(74, 144, 226, 0.9);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    z-index: 9999;
}

.cover-camera-icon:hover {
    transform: scale(1.1);
    background: rgba(53, 122, 189, 0.9);
}

.cover-camera-icon i {
    font-size: 1.2rem;
}

.cover-dropdown-menu {
    display: none;
    position: absolute;
    bottom: 100%;
    right: 0;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 0.5rem 0;
    min-width: 200px;
    z-index: 10000;
    margin-bottom: 10px;
}

.cover-dropdown-menu.show {
    display: block;
}

.cover-dropdown-menu a {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    color: #333;
    text-decoration: none;
    transition: all 0.2s ease;
}

.cover-dropdown-menu a:hover {
    background: #f8f9fa;
    color: #4a90e2;
}

.cover-dropdown-menu a i {
    margin-right: 0.5rem;
    color: #4a90e2;
    font-size: 1rem;
}

.profile-info-container {
    background: #fff;
    border-radius: 0 0 15px 15px;
    padding: 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    position: relative;
    z-index: 1;
    gap: 1rem;
}

/* Estilos para el cropper */
.cropper-container {
    height: 400px;
    width: 100%;
    background: #f8f9fa;
    border-radius: 10px;
    overflow: hidden;
}

.phase {
    margin-bottom: 1rem;
}

.phase-navigation {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
}

.preview-container {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
}

/* Estilos para el sistema de fases */
.phase-container {
    position: relative;
    min-height: 400px;
}

.phase {
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
    position: absolute;
    width: 100%;
    top: 0;
    left: 0;
}

.phase.active {
    display: block;
    opacity: 1;
}

.progress {
    height: 8px;
    border-radius: 4px;
    background-color: #e9ecef;
    overflow: hidden;
}

.progress-bar {
    background-color: #4a90e2;
    transition: width 0.3s ease-in-out;
}

.phase-navigation {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 1rem;
}

/* Ajustes para el cropper */
.cropper-container {
    height: 400px;
    width: 100%;
    background: #f8f9fa;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
}

.preview-container {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
}

.btn-outline-primary {
    border-color: #4a90e2;
    color: #4a90e2;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background-color: #4a90e2;
    color: white;
    transform: translateY(-1px);
}

.btn-outline-primary i {
    margin-right: 0.5rem;
}
</style>

<?php
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js');

// Registrar el archivo JavaScript de perfil
$this->registerJsFile(
    Yii::getAlias('@web/js/perfil.js'),
    ['depends' => [\yii\web\JqueryAsset::class]]
);

// Agregar meta tag para CSRF token
$this->registerMetaTag([
    'name' => 'csrf-token',
    'content' => Yii::$app->request->csrfToken
]);

// Agregar meta tag para URL base
$this->registerMetaTag([
    'name' => 'base-url',
    'content' => Yii::$app->request->hostInfo . Yii::$app->request->baseUrl
]);

// Agregar meta tag para la URL de actualización
$this->registerMetaTag([
    'name' => 'update-profile-info-url',
    'content' => Yii::$app->urlManager->createUrl(['site/update-profile-info'])
]);
?> 