#!/bin/bash

echo "Esperando base de datos..."
until mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASSWORD" --skip-ssl "$DB_NAME" -e "SELECT 1;" 2>/dev/null; do
    sleep 2
done
echo "Base de datos lista."

TABLE_COUNT=$(mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASSWORD" --skip-ssl "$DB_NAME" -e "SHOW TABLES;" 2>/dev/null | wc -l)

if [ "$TABLE_COUNT" -eq "0" ]; then
    echo "Importando estructura..."
    mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASSWORD" --skip-ssl "$DB_NAME" < /var/www/html/barber_shop.sql
    echo "Poblando base de datos con datos de ejemplo..."
    mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASSWORD" --skip-ssl --default-character-set=utf8mb4 "$DB_NAME" < /var/www/html/barber_shop_seed.sql
    echo "Base de datos lista con datos de ejemplo."
else
    echo "Base de datos ya existe, omitiendo importación."
fi

echo "App lista en http://localhost:8080"
exec apache2-foreground
