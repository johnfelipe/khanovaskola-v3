#!/bin/sh

if [ "$TEST_SUITE" = "cs" ]
then
	exit 0
fi

echo "Installing ElasticSearch ICU plugin"
sudo /usr/share/elasticsearch/bin/plugin -install elasticsearch/elasticsearch-analysis-icu/1.13.0
sudo service elasticsearch restart

echo "Installing beanstalkd"
sudo apt-get install beanstalkd
beanstalkd -l 127.0.0.1 -p 13000 &

echo "Setting configuration"
cat tests/travis/php-custom.ini >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini
rm tests/acceptance.suite.yml
mv tests/travis/acceptance.suite.yml tests/acceptance.suite.yml
cp tests/travis/config.local.neon app/config/config.local.neon

if [ "$TEST_SUITE" = "acceptance" ]
then
	echo "Starting local server"
	php -S localhost:8000 -t www/ &
fi

echo "Waiting for all services to load"
sleep 10 # wait for all services to load

echo "Provisioning"
psql -c 'create database khanovaskola;' -U postgres
bin/console db:migrate
bin/console es:recreate
bin/console db:fill --testdata
