NOW=$(date +"%d-%m-%Y %r")
echo "STARTED! Crontab Themeisle Wraith $NOW"
php themeisle-wraith-service.php -m=sitemaps
php themeisle-wraith-service.php -m=all_history_spyder
NOW=$(date +"%d-%m-%Y %r")
echo "FINISHED! Crontab Themeisle Wraith $NOW"