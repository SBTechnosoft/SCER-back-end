## composer installation
	
**NOTES:**
##### composer link : http://www.cyberciti.biz/faq/how-to-install-composer-on-debian-ubuntu-linux-server/
	
#####if you are using Ubuntu Linux 16.04 LTS or newer and want to use PHP 7.x, run: #####
	- $ sudo apt install curl php7.0-cli git 
	
####install composer on Debian or Ubuntu Linux in /usr/local/bin/ directory as follows####
	- $ curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
 
####Verify composer####
	- $ composer OR $ /usr/local/bin/composer
	
####install dependencies defined in composer.json file####
	- $ composer install
	
OR
**NOTES:** 
#####composer link : http://tecadmin.net/install-laravel-framework-on-ubuntu/#
	
	- $ curl -sS https://getcomposer.org/installer | php
	- $ sudo mv composer.phar /usr/local/bin/composer	
	- $ sudo chmod +x /usr/local/bin/composer

## laravel installation##
	
	- cd /var/www/html
	- sudo composer create-project laravel/laravel your-project --prefer-dist
	
**NOTES:** 
##### laravel link : https://www.howtoforge.com/tutorial/install-laravel-on-ubuntu-for-apache/

	
	
	