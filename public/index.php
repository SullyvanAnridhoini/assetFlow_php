<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);

    if (str_starts_with($relativeClass, 'Config\\')) {
        $file = dirname(__DIR__) . '/config/' . strtolower(substr($relativeClass, 7)) . '.php';
        if (!file_exists($file)) {
            $file = dirname(__DIR__) . '/config/' . substr($relativeClass, 7) . '.php';
        }
    } else {
        $baseDir = dirname(__DIR__) . '/app/';
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    }

    if (file_exists($file)) {
        require_once $file;
    }
});

$route = $_GET['route'] ?? $_GET['page'] ?? 'home';
$route = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', (string) $route);

try {
    switch ($route) {
        case '':
        case 'home':
        case 'index':
            $controller = new \App\Controllers\HomeController();
            $controller->index();
            break;

        case 'assets':
        case 'inventory':
            $controller = new \App\Controllers\HomeController();
            $controller->assets();
            break;

        case 'form':
        case 'questions':
            $controller = new \App\Controllers\HomeController();
            $controller->form();
            break;

        default:
            http_response_code(404);
            $pageTitle = 'Page non trouvée (404)';
            require_once __DIR__ . '/../app/Views/layouts/header.php';
            ?>
            <div class="max-w-2xl mx-auto px-4 py-16 text-center space-y-4">
                <div class="text-5xl font-extrabold text-brand-600">404</div>
                <h1 class="text-2xl font-bold text-slate-800">Page introuvable</h1>
                <p class="text-sm text-slate-500">
                    La route <code class="font-mono bg-slate-100 px-2 py-1 rounded text-rose-600"><?= e($route) ?></code> n'est pas
                    encore enregistrée dans le Front Controller (<code class="font-mono">public/index.php</code>).
                </p>
                <div class="pt-4">
                    <a href="index.php?route=home"
                        class="inline-flex items-center px-4 py-2 bg-brand-600 hover:bg-brand-500 text-white rounded-lg text-sm font-medium transition shadow-sm">
                        &larr; Retour au tableau de bord
                    </a>
                </div>
            </div>
            <?php
            require_once __DIR__ . '/../app/Views/layouts/footer.php';
            break;
    }
} catch (\Throwable $e) {
    error_log(sprintf('[AssetFlow 500] %s in %s:%d', $e->getMessage(), $e->getFile(), $e->getLine()));

    http_response_code(500);
    $pageTitle = 'Erreur interne (500)';
    require_once __DIR__ . '/../app/Views/layouts/header.php';
    ?>
    <div class="max-w-3xl mx-auto px-4 py-16 space-y-4">
        <div class="bg-rose-50 border border-rose-200 rounded-xl p-6 space-y-3">
            <div class="text-rose-600 font-bold text-xs uppercase tracking-wider">Erreur d'exécution 500</div>
            <h1 class="text-lg font-bold text-rose-900">Une erreur inattendue est survenue</h1>
            <p class="text-xs text-slate-600 leading-relaxed">
                Le serveur a rencontré une anomalie lors du traitement de la requête. L'incident a été consigné dans les journaux d'erreurs du serveur PHP.
            </p>

            <div class="pt-2">
                <a href="index.php?route=home"
                    class="inline-flex items-center px-3 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-medium hover:bg-rose-500 transition">
                    &larr; Revenir à l'accueil
                </a>
            </div>
        </div>
    </div>
    <?php
    require_once __DIR__ . '/../app/Views/layouts/footer.php';
}