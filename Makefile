all: nodev phar

dev:
	composer update --with-all-dependencies -v -o --ansi --dev

nodev:
	composer update --with-all-dependencies -v -o --ansi --no-dev

completion:
	rm -f emurelator_completion
	php emurelator.php bash --bind emurelator.phar --program emurelator.phar > emurelator_completion
	chmod +x emurelator_completion

phar:
	rm -f emurelator.phar
	# compression breaks pvdisplay
	php emurelator.php archive --composer=composer.json --app-bootstrap --executable --no-compress emurelator.phar
	chmod +x emurelator.phar

install:
	cp emurelator_completion /etc/bash_completion.d/emurelator
	ln -fs /root/cpaneldirect/emurelator.phar /usr/local/bin/emurelator

internals:
	rm -rf app/Command/InternalsCommand
	php emurelator.php generate-internals

copy:
	cp -fv emurelator.phar ../vps_host_server/emurelator.phar && \
		cd ../vps_host_server && \
		git pull --all && \
		git commit -m 'Updating emurelator.phar' emurelator.phar && \
		git push --all && \
		git pull --all && \
		cd ../emurelator
