#!/usr/bin/make -f

check: update
	find src -name '*.php' -exec php -l {} \;
	find test -name '*.php' -exec php -l {} \;
	find src -name '*.php' -exec vendor/instaclick/php-code-sniffer/scripts/phpcs --standard=PSR2 {} \;
	find test -name '*.php' -exec vendor/instaclick/php-code-sniffer/scripts/phpcs --standard=PSR2 {} \;

composer.phar:
	curl -sS https://getcomposer.org/installer | php

update: composer.phar
	./composer.phar install

update-dep: composer.phar
	./composer.phar selfupdate
	./composer.phar update

pux:
	vendor/corneltek/pux/pux compile -o route/compiled.php route/mux.php

test: check
	rm -fr test/test_group
	mkdir test/test_group
	cp test/files/test.jpg test/test_group/
	vendor/phpunit/phpunit/phpunit.php -c phpunit.xml

.PHONY: test update-dep check update
