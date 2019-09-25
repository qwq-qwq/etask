<?php
require(dirname(__FILE__) . '/config.php');

date_default_timezone_set("Europe/Kiev");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

define("FRAMEWORK_VERSION", "1.0");
define("PROJECT_SESSION_NAME", 'auctionsession');

define('ENABLE_TEMPLATES_CACHE', false);
define('PROJECT_CHARSET', 'utf-8');
define('SESSION_SAVE_HANDLER', 'php');
define('DEFAULT_ITEMSPERPAGE', 20);
define('DEFAULT_SUB_ITEMSPERPAGE', 5);
define('COUNT_AUCTIONS_IN_INDEX_PAGE', 5);
define('DEFAULT_DATE_FORMAT', '%d.%m.%y');
define('DEFAULT_DATETIME_FORMAT', '%Y-%m-%d %H:%M');
define('SUPPORT_FROM_MAIL', 'noreply@bukva.ua');

define('DB_TABLE_PREFIX', '');
define('DB_TABLE_TASKS', DB_TABLE_PREFIX.'tasks');
define('DB_TABLE_TASK_TYPES', DB_TABLE_PREFIX.'task_types');
define('DB_TABLE_TASK_STATE', DB_TABLE_PREFIX.'task_state');
define('DB_TABLE_COMMENTS', DB_TABLE_PREFIX.'comments');
define('DB_TABLE_DOCUMENTS', DB_TABLE_PREFIX.'documents');
define('DB_TABLE_INVOICES', DB_TABLE_PREFIX.'invoices');
define('DB_TABLE_COLLECT_ARTICLES', DB_TABLE_PREFIX.'collect_articles');
define('DB_TABLE_PACK_ARTICLES', DB_TABLE_PREFIX.'pack_articles');
define('DB_TABLE_INVOICE_ARTICLES', DB_TABLE_PREFIX.'invoice_articles');
define('DB_TABLE_DELIVERY_ARTICLES', DB_TABLE_PREFIX.'delivery_articles');
define('DB_TABLE_BILL', DB_TABLE_PREFIX.'bill_number');
define('SL_TABLE_PRINT_DOC_PATTERN', DB_TABLE_PREFIX.'print_doc_pattern');

define('DB_INTRANET_TABLE_PREFIX', 'empik_');
define('DB_INTRANET_TABLE_USERS', DB_INTRANET_TABLE_PREFIX.'users');
define('DB_INTRANET_TABLE_DEAPRTMENTS', DB_INTRANET_TABLE_PREFIX.'vars');
define('DB_INTRANET_TABLE_USERSMULTIDEPTS', DB_INTRANET_TABLE_PREFIX.'usersmultidepts');

define('DB_EMPIK_TABLE_PREFIX', '');
define('DB_EMPIK_TABLE_ACCOUNTS', DB_EMPIK_TABLE_PREFIX.'accounts');
define('DB_EMPIK_TABLE_ORDERS', DB_EMPIK_TABLE_PREFIX.'orders');
define('DB_EMPIK_TABLE_SALES', DB_EMPIK_TABLE_PREFIX.'sales');
define('DB_EMPIK_TABLE_ACCOUNTS', DB_EMPIK_TABLE_PREFIX.'accounts');
define('DB_EMPIK_TABLE_DELIVERY_TYPES', DB_EMPIK_TABLE_PREFIX.'delivery_types');
define('DB_EMPIK_TABLE_WHS', DB_EMPIK_TABLE_PREFIX.'whs');
define('DB_EMPIK_TABLE_COUNTRIES', DB_EMPIK_TABLE_PREFIX.'countries');
define('DB_EMPIK_TABLE_CITIES', DB_EMPIK_TABLE_PREFIX.'cities');
define('DB_EMPIK_TABLE_BRAND', DB_EMPIK_TABLE_PREFIX.'wares_brand');
define('DB_EMPIK_TABLE_CITY_COUNTRY', DB_EMPIK_TABLE_PREFIX.'city_country');
define('DB_EMPIK_TABLE_PAYMENT_TYPES', DB_EMPIK_TABLE_PREFIX.'payment_types');
define('DB_EMPIK_TABLE_PAY_CITY', DB_EMPIK_TABLE_PREFIX.'pay_city');
define('DB_EMPIK_TABLE_PAY_COUNTRY', DB_EMPIK_TABLE_PREFIX.'pay_city');
define('DB_EMPIK_TABLE_PAY_DELIV', DB_EMPIK_TABLE_PREFIX.'pay_deliv');
define('DB_EMPIK_TABLE_DELIVERY_CITY', DB_EMPIK_TABLE_PREFIX.'delivery_city');
define('DB_EMPIK_TABLE_GMAP', DB_EMPIK_TABLE_PREFIX.'gmap'); // shops
define('DB_EMPIK_TABLE_REGIONS', DB_EMPIK_TABLE_PREFIX.'regions');
define('DB_EMPIK_TABLE_EANS', DB_EMPIK_TABLE_PREFIX.'eans');
define('DB_EMPIK_TABLE_CATALOG', DB_EMPIK_TABLE_PREFIX.'catalog');
define('DB_EMPIK_TABLE_CATALOG_TREE', DB_EMPIK_TABLE_PREFIX.'catalog_tree');
define('DB_EMPIK_TABLE_CATALOG_AGGREGATION', DB_EMPIK_TABLE_PREFIX.'catalog_aggregation');
define('DB_EMPIK_TABLE_DISCOUNTS', DB_EMPIK_TABLE_PREFIX.'discounts');
define('DB_EMPIK_TABLE_PROPERTIES', DB_EMPIK_TABLE_PREFIX.'properties');
define('DB_EMPIK_TABLE_DATA', DB_EMPIK_TABLE_PREFIX.'data');
define('DB_EMPIK_TABLE_MAILTEMPLATES', DB_EMPIK_TABLE_PREFIX.'mails_templates');
define('DB_EMPIK_TABLE_CODE', DB_EMPIK_TABLE_PREFIX.'codes');
define('DB_EMPIK_TABLE_CODE_CATS', DB_EMPIK_TABLE_PREFIX.'code_cats');
define('DB_EMPIK_TABLE_CODE_PRODS', DB_EMPIK_TABLE_PREFIX.'code_prod');


define('DB_SPRUT_TABLE_PREFIX', 'MZ.');
define('DB_SPRUT_TABLE_STOCK', DB_SPRUT_TABLE_PREFIX.'BETAEMPIK_STOCK');
define('DB_SPRUT_TABLE_WRITE_OFF_INVOICE', DB_SPRUT_TABLE_PREFIX.'WRITE_OFF_INVOICE');
define('DB_SPRUT_TABLE_WARES_WRITE_OFF_INVOICE', DB_SPRUT_TABLE_PREFIX.'WARES_WRITE_OFF_INVOICE');
define('DB_SPRUT_TABLE_ADDITION_SIGN', DB_SPRUT_TABLE_PREFIX.'ADDITION_SIGN');
define('DB_SPRUT_TABLE_ORDER_CLIENT', DB_SPRUT_TABLE_PREFIX.'ORDER_CLIENT');



include_once(FRAMEWORK_PATH.FRAMEWORK_VERSION."/system/kernel.php");