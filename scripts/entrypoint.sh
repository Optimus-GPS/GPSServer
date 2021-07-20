#!/bin/bash


echo "Procesando archivos de configuracion ..."
python3 /var/www/html/scripts/parse_configs.py
echo "Iniciando Apache ..."
apache2-foreground