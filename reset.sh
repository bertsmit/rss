#!/bin/bash
sudo service apache2 stop
sudo service mysql stop
sudo service mysql start
sudo service apache2 start
