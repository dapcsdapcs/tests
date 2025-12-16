<?php
error_reporting(0);
ini_set('display_errors', 0);
// Database configuration using a DATABASE_URL or a fallback DSN.
// For safety prefer exporting DATABASE_URL in your environment.

// Example provided by user (will be used only if env var not set):
$fallback = 'mysql://admin:nurlan2005@demo-db.cm94s2gqizfp.us-east-1.rds.amazonaws.com:3306/database-my?ssl-mode=REQUIRED';
$databaseUrl = getenv('DATABASE_URL') ?: $fallback;

// parse URL and query params
$parts = parse_url($databaseUrl);
parse_str($parts['query'] ?? '', $queryParams);
if ($parts === false || !isset($parts['host'])) {
    throw new Exception('DATABASE_URL is invalid.');
}

$dbHost = $parts['host'];
$dbPort = $parts['port'] ?? 3306;
$dbUser = $parts['user'] ?? '';
$dbPass = $parts['pass'] ?? '';
$dbName = isset($parts['path']) ? ltrim($parts['path'], '/') : '';

// Build DSN
$charset = 'utf8mb4';
$dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Handle ssl-mode if present in the URL query (e.g. ssl-mode=REQUIRED)
if (isset($queryParams['ssl-mode'])) {
    $sslMode = strtoupper($queryParams['ssl-mode']);
    // If PDO MySQL supports SSL CA option, try to set a reasonable default CA bundle
    if (defined('PDO::MYSQL_ATTR_SSL_CA')) {
        // Prefer a project-local CA bundle if present (certs/ca.pem), else try common system locations
        $localCa = __DIR__ . '/certs/ca.pem';
        if (is_readable($localCa)) {
            $options[PDO::MYSQL_ATTR_SSL_CA] = $localCa;
        } else {
            $candidates = [
                '/etc/ssl/certs/ca-certificates.crt',
                '/etc/ssl/cert.pem',
                '/usr/local/share/ca-certificates/ca-certificates.crt'
            ];
            foreach ($candidates as $c) {
                if (is_readable($c)) {
                    $options[PDO::MYSQL_ATTR_SSL_CA] = $c;
                    break;
                }
            }
        }
    }
    // If SSL mode is REQUIRED but no CA is available, still attempt connection;
    // the server may accept TLS without client CA verification.
}

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    // Log success to console (CLI) or error log (web)
    $successMsg = sprintf('Database connection successful: host=%s port=%s db=%s user=%s', $dbHost, $dbPort, $dbName, $dbUser);
    if (php_sapi_name() === 'cli') {
        echo $successMsg . PHP_EOL;
    } else {
        error_log($successMsg);
    }
} catch (PDOException $e) {
    $msg = $e->getMessage();
    $hint = "";
    if (stripos($msg, 'SSL') !== false || stripos($msg, 'certificate') !== false) {
        $hint = " Ensure PHP has OpenSSL and pdo_mysql enabled and provide a CA bundle via PDO::MYSQL_ATTR_SSL_CA or set a proper system CA (see README).";
    }
    // Log failure before throwing
    $errMsg = 'Veritabanına bağlanılamadı: ' . $msg . $hint;
    if (php_sapi_name() === 'cli') {
        fwrite(STDERR, $errMsg . PHP_EOL);
    } else {
        error_log($errMsg);
    }
    throw new Exception($errMsg);
}

return $pdo;
