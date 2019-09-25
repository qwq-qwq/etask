#!/bin/sh
ORACLE_HOME=/usr/local/oracle8-client; export ORACLE_HOME
NLS_LANG=AMERICAN_AMERICA.CL8MSWIN1251; export NLS_LANG
/usr/local/bin/php /opt/www/webshop/etask.bukva.ua/cron/paritet_exchange_step2.php
