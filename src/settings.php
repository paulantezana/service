<?php
date_default_timezone_set('America/Lima');

function exceptions_error_handler($severity, $message, $filename, $lineno)
{
  error_log($severity. $message. $filename. $lineno . PHP_EOL, 3,  __DIR__ . '/../files/errors.log');
  if (error_reporting() == 0) {
    return;
  }
  if (error_reporting() & $severity) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
}
set_error_handler('exceptions_error_handler');

$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$requestUri = parse_url('http://example.com' . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
$virtualPath = '/' . ltrim(substr($requestUri, strlen($scriptName)), '/');
$hostName = (stripos($_SERVER['REQUEST_SCHEME'], 'https') === 0 ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'];

define('HOST', $hostName);
define('URI', $requestUri);
define('URL_PATH', rtrim($scriptName,'/'));
define('URL',$virtualPath);

define('ROOT_DIR', $_SERVER["DOCUMENT_ROOT"] . rtrim($scriptName,'/'));
define('CONTROLLER_PATH', ROOT_DIR . '/src/Controllers');
define('MODEL_PATH', ROOT_DIR . '/src/Models');
define('VIEW_PATH', ROOT_DIR . '/src/Views');
define('CERVICE_PATH', ROOT_DIR . '/src/Services');

define('SESS_KEY','SnId_service');
define('SESS_USER','SnUser_service');

define('APP_NAME','Servicios');
define('APP_AUTHOR','paulantezana');
define('APP_DESCRIPTION','Control de pagos');
define('APP_EMAIL','paulantezana.2@gmail.com');

define('APP_DEV',true);

define('FILE_PATH', '/files');

define('GOOGLE_API_KEY','AIzaSyAG7pSRfqKAObS76qjyyIeuImkVcooIt2I');
// define('GOOGLE_RE_SECRET_KEY','6LfcgMQZAAAAANIo0O9NzC5bJyPowYVt9gQMyqyo');