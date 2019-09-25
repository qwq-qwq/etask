<?php
mb_internal_encoding('UTF-8');
// error reporting
error_reporting(~E_ALL|~E_NOTICE);
define('ENABLE_INTERNAL_DEBUG', false);
// local path & url
define("PROJECT_PATH", "/opt/etasks/");
define("PROJECT_URL", "http://e-tasks.bukva.ua/");
define("SHOP_URL", "http://bukva.ua/");
define("PROJECT_INTRANET_URL", "http://intranet.bukva.ua/");
//units path
define('UNITS_PATH', PROJECT_PATH.'classes/unit/');
// templates path
define('TEMPLATES_PATH', PROJECT_PATH.'data/templates/');
define('TEMPLATES_TASKS_PATH', 'tasks/');
// cache path
define('PROJECT_CACHE', PROJECT_PATH.'cache/');
// filestorage settings
define('FILESTORAGE', PROJECT_PATH.'data/filestorage/');
define('FILESTORAGE_URL', PROJECT_URL.'data/filestorage/');
// mail settings
define('MAIL_TEMPLATES_PATH', TEMPLATES_PATH.'mail/');
define('PROJECT_FROM_MAIL', 'noreply@bukva.ua');
// framework path
define("FRAMEWORK_PATH", PROJECT_PATH."framework/");
//database names
define('DB_ETASK_NAME', 'etask');
define('DB_EMPIK_NAME', 'prod_bukva_ua');
// database connections
define('DB_URI', 'mysql://prod_bukva_web:@localhost/'.DB_ETASK_NAME);
define('DB_INTRANET_URI', 'mysql://prod_bukva_web:@localhost/empik');
define("DB_SPRUT_CONNECT_PARAMS", 'betaempik|empikdev#2|(DESCRIPTION =
	    (ADDRESS = (PROTOCOL = TCP)(HOST =172.16.5.2)(PORT = 1521))
	 (CONNECT_DATA = (SERVICE_NAME = CNT_TAF.eksmo.com.ua)) )');
define('DB_EMPIK_URI', 'mysql://prod_bukva_web:@localhost/'.DB_EMPIK_NAME);
// secret key for authorization
define('SECRET_KEY', 'xIG2h7WCiz4pJbkl');

// define departments id
define('DEPARTMENT_CALLCENTRE_ID', 47); // TODO fix to callcentre deptID
define('DEPARTMENT_CURRIER_ID', 46);

define('PARITET_SHOP_ID', 48);
define('PARITET_CODE_SHOP', 71);
define('PARITET_REPLACEMENT_SHOP_ID', 37);
define('PARITET_REPLACEMENT_CODE_SHOP', 53);
define('PARITET_FTP_HOST', 'bukva.ua');
define('PARITET_FTP_USER', 'empik_ua');
define('PARITET_FTP_PASSWD', 'ewm1mt1oa');
define('PARITET_FTP_URL', 'ftp://'.PARITET_FTP_USER.':'.PARITET_FTP_PASSWD.'@'.PARITET_FTP_HOST);