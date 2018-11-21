#!/bin/bash

set -e

{
    # disable strict mode (introduced in 5.7)
    cp /vagrant/provisioning/etc/mysql/conf.d/disable-strict-mode.cnf /etc/mysql/conf.d
}

# > /dev/null 2>&1
