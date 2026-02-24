<?php
// config/railway_db.php
// Configuración para la base de datos externa en Railway, con soporte para variables de entorno
define('RW_HOST', getenv('MYSQLHOST') ?: 'shinkansen.proxy.rlwy.net');
define('RW_PORT', getenv('MYSQLPORT') ?: '49579');
define('RW_USER', getenv('MYSQLUSER') ?: 'root');
define('RW_PASS', getenv('MYSQLPASSWORD') ?: 'EHClsjxUYgaZfndzmSwGgekxJlAVImdv');
define('RW_NAME', getenv('MYSQLDATABASE') ?: 'railway');

function getRailwayDB()
{
    static $rw_db = null;
    if ($rw_db === null) {
        try {
            $dsn = "mysql:host=" . RW_HOST . ";port=" . RW_PORT . ";dbname=" . RW_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $rw_db = new PDO($dsn, RW_USER, RW_PASS, $options);
            $rw_db->exec("SET time_zone = '-05:00'");
        } catch (PDOException $e) {
            error_log("Error conexión Railway: " . $e->getMessage());
            return null; // Don't die, just return null so local app can still work
        }
    }
    return $rw_db;
}
?>