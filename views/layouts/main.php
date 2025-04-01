<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use app\models\Notificaciones;
use yii\web\JqueryAsset;
JqueryAsset::register($this);
AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/assets/img/chismo-ico.png')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?= Html::cssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => '<i class="fas fa-comments"></i> El Chismoso',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-expand-lg navbar-light bg-white fixed-top shadow-sm',
            'style' => 'padding: 0.8rem 0;'
        ],
        'innerContainerOptions' => [
            'class' => 'container-fluid px-4'
        ]
    ]);
    
    // Calcular el número de notificaciones
    $notificationCount = 0;
    if (!Yii::$app->user->isGuest) {
        $notificationCount = Notificaciones::find()
            ->where(['receptor_id' => Yii::$app->user->id])
            ->count();
    }

    $menuItems = [
        [
            'label' => '<i class="fas fa-home"></i> Inicio',
            'url' => ['/site/index'],
            'encode' => false,
            'options' => ['class' => 'nav-item mx-2']
        ],
        [
            'label' => '<i class="fas fa-bell"></i> Notificaciones' . 
                ($notificationCount > 0 ? 
                ' <span class="badge bg-danger rounded-pill">' . $notificationCount . '</span>' : ''),
            'url' => ['/site/notificaciones'],
            'encode' => false,
            'options' => ['class' => 'nav-item mx-2']
        ],
        [
            'label' => '<i class="fas fa-plus-circle"></i> Nuevo Chisme',
            'url' => ['/site/create-post'],
            'encode' => false,
            'options' => ['class' => 'nav-item mx-2']
        ],
    ];

    // Agregar el ítem de administración solo si el usuario tiene rol_id = 1313, 1314 o 1315
    if (!Yii::$app->user->isGuest) {
        $rol_id = Yii::$app->user->identity->rol_id;
        
        // Solo mostrar el menú de administración si el usuario tiene rol de administrador
        if (in_array($rol_id, [1313, 1314, 1315])) {
            $menuItems[] = [
                'label' => '<i class="fas fa-cog"></i> Administración',
                'encode' => false,
                'items' => []
            ];
            
            // SUPERSU ve todas las opciones
            if ($rol_id == 1313) {
                $menuItems[count($menuItems)-1]['items'][] = [
                    'label' => '<i class="fas fa-chart-line"></i> Dashboard',
                    'url' => ['/site/logs'],
                    'encode' => false,
                ];
                $menuItems[count($menuItems)-1]['items'][] = [
                    'label' => '<i class="fas fa-ban"></i> Gestión de Contenido',
                    'url' => ['/site/gestion-contenido'],
                    'encode' => false,
                ];
                $menuItems[count($menuItems)-1]['items'][] = [
                    'label' => '<i class="fas fa-users-cog"></i> Administración de Usuarios',
                    'url' => ['/site/admin-usuarios'],
                    'encode' => false,
                ];
            }
            // ADMIN ve Gestión de Contenido y Administración de Usuarios
            elseif ($rol_id == 1314) {
                $menuItems[count($menuItems)-1]['items'][] = [
                    'label' => '<i class="fas fa-ban"></i> Gestión de Contenido',
                    'url' => ['/site/gestion-contenido'],
                    'encode' => false,
                ];
                $menuItems[count($menuItems)-1]['items'][] = [
                    'label' => '<i class="fas fa-users-cog"></i> Administración de Usuarios',
                    'url' => ['/site/admin-usuarios'],
                    'encode' => false,
                ];
            }
            // MOD solo ve Gestión de Contenido
            elseif ($rol_id == 1315) {
                $menuItems[count($menuItems)-1]['items'][] = [
                    'label' => '<i class="fas fa-ban"></i> Gestión de Contenido',
                    'url' => ['/site/gestion-contenido'],
                    'encode' => false,
                ];
            }
        }
    }

    // Agregar elementos para usuarios registrados
    if (!Yii::$app->user->isGuest) {
        $menuItems[] = [
            'label' => '<i class="fas fa-user-circle"></i> Mi Perfil',
            'url' => ['/site/perfil'],
            'encode' => false,
            'options' => ['class' => 'nav-item mx-2']
        ];
        
        $menuItems[] = [
            'label' => '<i class="fas fa-sign-out-alt"></i> Cerrar Sesión (' . Yii::$app->user->identity->user . ')',
            'url' => ['/site/logout'],
            'encode' => false,
            'options' => ['class' => 'nav-item mx-2']
        ];
    } else {
        $menuItems[] = [
            'label' => '<i class="fas fa-sign-in-alt"></i> Iniciar Sesión',
            'url' => ['/site/login'],
            'encode' => false,
            'options' => ['class' => 'nav-item mx-2']
        ];
    }

    echo Nav::widget([
        'options' => [
            'class' => 'navbar-nav ms-auto',
            'style' => 'font-size: 1rem;'
        ],
        'items' => $menuItems,
    ]);
    
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container py-4 mt-5">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-4 bg-white border-top">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">
                <i class="fab fa-github"></i> Creado por DiegoPtit <?= date('Y') ?>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <i class="fas fa-heart text-danger"></i> Que vivan los secretos, QPD TuSecreto
            </div>
        </div>
    </div>
</footer>

<style>
:root {
    --primary-color: #6c5ce7;
    --secondary-color: #a8a4e6;
    --hover-color: #5b4cc4;
    --text-color: #2d3436;
    --light-bg: #f8f9fa;
}

body {
    background-color: var(--light-bg);
    color: var(--text-color);
    font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
}

.navbar {
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    background-color: rgba(255, 255, 255, 0.95) !important;
    height: 80px;
}

.navbar-brand {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--primary-color) !important;
}

.nav-link {
    position: relative;
    padding: 0.5rem 1rem !important;
    transition: all 0.3s ease;
    color: var(--text-color) !important;
    font-weight: 500;
}

.nav-link:hover {
    color: var(--primary-color) !important;
    transform: translateY(-2px);
}

.nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 50%;
    background-color: var(--primary-color);
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.nav-link:hover::after {
    width: 80%;
}

.nav-link.active {
    color: var(--primary-color) !important;
}

.nav-link.active::after {
    width: 80%;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
    font-weight: 500;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    padding: 0.5rem;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.dropdown-item:hover {
    background-color: var(--light-bg);
    color: var(--primary-color);
    transform: translateX(5px);
}

.btn-link {
    color: var(--text-color);
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-link:hover {
    color: var(--primary-color);
}

.breadcrumb {
    background: transparent;
    padding: 0.5rem 0;
    margin-bottom: 1rem;
}

.breadcrumb-item a {
    color: var(--primary-color);
    text-decoration: none;
    transition: all 0.3s ease;
}

.breadcrumb-item a:hover {
    color: var(--hover-color);
}

.breadcrumb-item.active {
    color: var(--text-color);
}

.alert {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

footer {
    position: relative;
    z-index: 1;
    background-color: white !important;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
}

footer i {
    margin-right: 0.5rem;
}

@media (max-width: 991.98px) {
    .navbar-nav {
        padding: 1rem 0;
    }
    
    .nav-item {
        margin: 0.5rem 0;
    }

    .navbar-collapse {
        background: white;
        padding: 1rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 1rem;
    }
}

/* Animaciones */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

main {
    animation: fadeIn 0.5s ease-out;
    padding-top: 80px;
}

.container {
    position: relative;
    z-index: 1;
}
</style>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
