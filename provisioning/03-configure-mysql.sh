#!/bin/bash

set -e

{
    mysql -uroot -proot -e "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));"
}

# > /dev/null 2>&1
