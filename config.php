
<?php
    const _MODULE = 'home';
    const _ACTION= 'dashboard';

    const _CODE = 'true';

    // thiet lap host
    define('_WEB_HOST','http://' .$_SERVER['HTTP_HOST']. '/User-manager');
    define('_WEB_HOST_TEMPLATES',_WEB_HOST.'/templates');

    // thiet lap path
    define('_WEB_PATH', __DIR__);
    define('_WEB_PATH_TEMPLATES', _WEB_PATH . '/templates');

    // thong tin ket noi file connect
    const _HOST = 'localhost';
    const _DB = 'qlnd';
    const _USER = 'root';
    const _PASS = '';

?>