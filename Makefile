run-tests: vendor/autoload.php
	bin/phpsimplertest --bootstrap vendor/autoload.php src/test --colorful-output

vendor/autoload.php: composer.json
	composer install
