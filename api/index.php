<?php

/**
 * Vercel Entry Point for Laravel
 * Caches (config + routes) são gerados UMA VEZ por container na cold start.
 * Warm requests usam os arquivos em /tmp/ sem re-executar artisan.
 */

$basePath = dirname(__DIR__);
$_ENV['APP_BASE_PATH'] = $basePath;

// SQLite fallback (não usado em produção)
$dbConnection = getenv('DB_CONNECTION') ?: 'sqlite';
if ($dbConnection === 'sqlite' && !getenv('DB_DATABASE')) {
    $dbPath = '/tmp/database.sqlite';
    if (!file_exists($dbPath)) touch($dbPath);
    putenv('DB_DATABASE=' . $dbPath);
}

// Supabase: corrige username para o formato postgres.[project-ref]
if ($dbConnection === 'pgsql') {
    $dbUser = getenv('DB_USERNAME') ?: 'postgres';
    if (!str_contains((string) $dbUser, '.')) {
        $fixed = 'postgres.hywqwshhfwwqqpogknoi';
        putenv('DB_USERNAME=' . $fixed);
        $_ENV['DB_USERNAME']    = $fixed;
        $_SERVER['DB_USERNAME'] = $fixed;
    }
    if (!getenv('DB_SSLMODE')) {
        putenv('DB_SSLMODE=require');
        $_ENV['DB_SSLMODE']    = 'require';
        $_SERVER['DB_SSLMODE'] = 'require';
    }
}

// Variáveis de ambiente padrão
if (!getenv('APP_ENV'))          putenv('APP_ENV=production');
if (!getenv('APP_DEBUG'))        putenv('APP_DEBUG=false');
if (!getenv('LOG_CHANNEL'))      putenv('LOG_CHANNEL=stderr');
if (!getenv('CACHE_STORE'))      putenv('CACHE_STORE=array');
if (!getenv('CACHE_DRIVER'))     putenv('CACHE_DRIVER=array');
if (!getenv('SESSION_DRIVER'))   putenv('SESSION_DRIVER=cookie');
if (!getenv('SESSION_LIFETIME')) putenv('SESSION_LIFETIME=1440');
if (!getenv('QUEUE_CONNECTION')) putenv('QUEUE_CONNECTION=sync');

// /tmp é o único diretório gravável no Vercel
foreach (['/tmp/views', '/tmp/sessions'] as $dir) {
    if (!file_exists($dir)) mkdir($dir, 0755, true);
}

// Todos os caches em /tmp/ — isolados por container, nunca conflitam com o código deployado
putenv('VIEW_COMPILED_PATH=/tmp/views');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_EVENTS_CACHE=/tmp/events.php');

// Rotas: usa o cache pré-gerado no build do Vercel se disponível (cold start mais rápido);
// caso contrário, gera em /tmp/ na primeira requisição do container.
$prebuiltRoutes = $basePath . '/bootstrap/cache/routes-v7.php';
if (!file_exists($prebuiltRoutes)) {
    putenv('APP_ROUTES_CACHE=/tmp/routes.php');
}

// Força HTTPS — Vercel termina SSL no proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}

require $basePath . '/vendor/autoload.php';

$app = require_once $basePath . '/bootstrap/app.php';

// Cold start: gera caches de config, rotas e pacotes UMA VEZ por container/deployment
// Na warm request, /tmp/routes.php já existe e os artisan calls são pulados
$deployId    = getenv('VERCEL_DEPLOYMENT_ID') ?: md5(filemtime(__FILE__));
$cacheFlag   = '/tmp/booted_' . $deployId;

if (!file_exists($cacheFlag)) {
    try {
        $console = $app->make(Illuminate\Contracts\Console\Kernel::class);

        // Migrations: apenas uma vez por deployment global (flag baseada em deploy ID)
        $migrateFlag = '/tmp/migrated_' . $deployId;
        if (!file_exists($migrateFlag)) {
            $console->call('migrate', ['--force' => true]);
            touch($migrateFlag);
        }

        // config:cache já faz o package discovery internamente
        $console->call('config:cache');

        // route:cache só é necessário se não foi pré-gerado no build do Vercel
        if (!file_exists($prebuiltRoutes)) {
            $console->call('route:cache');
        }

        touch($cacheFlag);
    } catch (\Throwable $e) {
        // Falha silenciosa — a plataforma funciona sem cache, apenas mais lenta
        file_put_contents('/tmp/boot_error.log',
            date('Y-m-d H:i:s') . ' ' . $e->getMessage() . "\n", FILE_APPEND);
    }
}

$kernel   = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request  = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
