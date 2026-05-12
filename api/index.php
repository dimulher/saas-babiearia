<?php

/**
 * Vercel Entry Point for Laravel
 * Este arquivo é o ponto de entrada para o runtime PHP do Vercel.
 * Suporta SQLite (/tmp) e PostgreSQL (Supabase) via variáveis de ambiente.
 */

// Define o APP_BASE_PATH para que o Laravel encontre os arquivos corretos
$_ENV['APP_BASE_PATH'] = dirname(__DIR__);

// Banco SQLite em /tmp (fallback se não houver configuração de Postgres)
$dbConnection = getenv('DB_CONNECTION') ?: 'sqlite';

if ($dbConnection === 'sqlite' && !getenv('DB_DATABASE')) {
    $dbPath = '/tmp/database.sqlite';
    if (!file_exists($dbPath)) {
        touch($dbPath);
    }
    putenv('DB_DATABASE=' . $dbPath);
}

// Força variáveis de ambiente essenciais caso não estejam definidas no painel Vercel
if (!getenv('APP_ENV'))        putenv('APP_ENV=production');
if (!getenv('APP_DEBUG')) putenv('APP_DEBUG=false');
if (!getenv('LOG_CHANNEL'))    putenv('LOG_CHANNEL=stderr');
if (!getenv('CACHE_DRIVER'))   putenv('CACHE_DRIVER=array');
if (!getenv('SESSION_DRIVER')) putenv('SESSION_DRIVER=cookie');
if (!getenv('QUEUE_CONNECTION')) putenv('QUEUE_CONNECTION=sync');

// Configura pasta de views compiladas para /tmp (único diretório gravável no Vercel)
putenv('VIEW_COMPILED_PATH=/tmp/views');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');
if (!file_exists('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}
// Força HTTPS no Vercel (proxy SSL termination)
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

// Carrega o autoload do Composer
require dirname(__DIR__) . '/vendor/autoload.php';

    // Inicializa a aplicação Laravel
    $app = require_once dirname(__DIR__) . '/bootstrap/app.php';

    // Removed migration block to prevent Console Kernel from polluting HTTP state

    // Carrega e executa o kernel HTTP do Laravel
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
