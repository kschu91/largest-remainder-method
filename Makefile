build:
	docker build -t largest-remainder-method .
test: build
	docker run -ti largest-remainder-method vendor/bin/phpunit