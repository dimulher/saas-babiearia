<?php

/**
 * Vercel Entry Point for Laravel — optimized for cold start performance
 */

$basePath = dirname(__DIR__);
$_ENV['APP_BASE_PATH'] = $basePath;

// Banco SQLite em /tmp (fallback)
$dbConnection = getenv('DB_CONNECTION') ?: 'sqlite';
if ($dbConnection === 'sqlite' && !getenv('DB_DATABASE')) {
    $dbPath = '/tmp/database.sqlite';
    if (!file_exists($dbPath)) touch($dbPath);
    putenv('DB_DATABASE=' . $dbPath);
}

// Variáveis essenciais
if (!getenv('APP_ENV'))          putenv('APP_ENV=production');
if (!getenv('APP_DEBUG'))        putenv('APP_DEBUG=false');
if (!getenv('LOG_CHANNEL'))      putenv('LOG_CHANNEL=stderr');
if (!getenv('CACHE_STORE'))      putenv('CACHE_STORE=array');
if (!getenv('CACHE_DRIVER'))     putenv('CACHE_DRIVER=array');
if (!getenv('SESSION_DRIVER'))   putenv('SESSION_DRIVER=cookie');
if (!getenv('QUEUE_CONNECTION')) putenv('QUEUE_CONNECTION=sync');

// Diretórios de cache no /tmp (único diretório gravável no Vercel)
$tmpDirs = ['/tmp/views', '/tmp/cache', '/tmp/framework'];
foreach ($tmpDirs as $dir) {
    if (!file_exists($dir)) mkdir($dir, 0755, true);
}

putenv('VIEW_COMPILED_PATH=/tmp/views');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');

// Força HTTPS — Vercel termina SSL no proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}

// OPcache: revalida arquivos apenas a cada 60s em produção
if (function_exists('opcache_reset') && getenv('APP_ENV') === 'production') {
    ini_set('opcache.revalidate_freq', 60);
    ini_set('opcache.validate_timestamps', 0);
}

require $basePath . '/vendor/autoload.php';

$app = require_once $basePath . '/bootstrap/app.php';

// Gera cache de rotas e config na primeira requisição do container
// (fica em /tmp e persiste enquanto o container estiver quente)
if (!file_exists('/tmp/routes.php') || !file_exists('/tmp/config.php')) {
    try {
        $console = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $console->call('config:cache');
        $console->call('route:cache');
        $console->call('view:cache');
    } catch (\Throwable $e) {
        // Falha silenciosa — continua sem cache se der erro
    }
}

$kernel  = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
