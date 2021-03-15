mysql -u root -p < sql/movieshop.sql

sudo mkdir /etc/apache2/ssl

cd my_certs
sudo cp movie_ca.crt movie_crl.pem localhost.pem /etc/apache2/ssl
cd ..

sudo a2enmod ssl
sudo a2enmod rewrite

sudo cp -f ssl /etc/apache2/sites-available/default-ssl.conf
sudo cp -f 000 /etc/apache2/sites-available/000-default.conf

sudo a2ensite default-ssl.conf

sudo service apache2 restart

exit
