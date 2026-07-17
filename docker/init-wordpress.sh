#!/bin/sh
set -eu

cd /var/www/html

until [ -f wp-config.php ]; do
  sleep 2
done

if ! wp core is-installed --allow-root 2>/dev/null; then
  wp core install \
    --url="$WP_URL" \
    --title="$WP_TITLE" \
    --admin_user="$WP_ADMIN_USER" \
    --admin_password="$WP_ADMIN_PASSWORD" \
    --admin_email="$WP_ADMIN_EMAIL" \
    --skip-email \
    --allow-root
fi

wp theme activate olga-maksimenko --allow-root
wp rewrite structure '/%postname%/' --hard --allow-root
wp option update blogdescription 'Дизайн интерьеров, в которых хочется жить' --allow-root
wp option update timezone_string 'Europe/Kyiv' --allow-root
wp eval-file wp-content/themes/olga-maksimenko/demo/import.php --allow-root
wp rewrite flush --hard --allow-root

echo "WordPress is ready at $WP_URL"

