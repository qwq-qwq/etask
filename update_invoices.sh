#!/bin/sh
ORACLE_HOME=/opt/oracle/instantclient; export ORACLE_HOME
NLS_LANG=AMERICAN_AMERICA.CL8MSWIN1251; export NLS_LANG
/usr/bin/php /opt/etasks/cron/updateinvoices.php