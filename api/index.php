<?php

/**
 * Vercel Entry Point for Laravel
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

// Variáveis de ambiente padrão para produção serverless
if (!getenv('APP_ENV'))          putenv('APP_ENV=production');
if (!getenv('APP_DEBUG'))        putenv('APP_DEBUG=false');
if (!getenv('LOG_CHANNEL'))      putenv('LOG_CHANNEL=stderr');
if (!getenv('CACHE_STORE'))      putenv('CACHE_STORE=array');
if (!getenv('CACHE_DRIVER'))     putenv('CACHE_DRIVER=array');
if (!getenv('SESSION_DRIVER'))   putenv('SESSION_DRIVER=cookie');
if (!getenv('SESSION_LIFETIME')) putenv('SESSION_LIFETIME=1440');
if (!getenv('QUEUE_CONNECTION')) putenv('QUEUE_CONNECTION=sync');

// /tmp é o único diretório gravável no Vercel serverless
foreach (['/tmp/views', '/tmp/sessions'] as $dir) {
    if (!file_exists($dir)) mkdir($dir, 0755, true);
}

// Views compiladas em /tmp (precisa ser gravável)
putenv('VIEW_COMPILED_PATH=/tmp/views');

// Config e routes apontam para /tmp mas NÃO são pré-gerados aqui.
// Laravel parseia os arquivos PHP diretamente — com OPcache ativo em instâncias
// warm isso é rápido (~5ms), e evita os ~500ms de cold start dos artisan calls.
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');

// NÃO sobrescreve APP_PACKAGES_CACHE / APP_SERVICES_CACHE:
// Laravel usa bootstrap/cache/packages.php e services.php (pré-commitados),
// que são lidos diretamente sem precisar regenerar.

// Força HTTPS — Vercel termina SSL no proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}

// OPcache: desativa revalidação de timestamps em produção
if (function_exists('opcache_get_status') && getenv('APP_ENV') === 'production') {
    ini_set('opcache.revalidate_freq', 60);
    ini_set('opcache.validate_timestamps', 0);
}

require $basePath . '/vendor/autoload.php';

$app = require_once $basePath . '/bootstrap/app.php';

// Migração automática: executa apenas uma vez por deployment (flag em /tmp por container)
$deployId    = getenv('VERCEL_DEPLOYMENT_ID') ?: md5(filemtime(__FILE__));
$migrateFlag = '/tmp/migrated_' . $deployId;
if (!file_exists($migrateFlag)) {
    try {
        $console = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $console->call('migrate', ['--force' => true]);
        touch($migrateFlag);
    } catch (\Throwable $e) {
        // Migração falhou silenciosamente — evita quebrar a plataforma por erro de schema
        file_put_contents('/tmp/migrate_error.log', date('Y-m-d H:i:s') . ' ' . $e->getMessage() . "\n", FILE_APPEND);
    }
}

$kernel   = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request  = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
