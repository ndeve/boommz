#Makefile
#include env Variables
include .env
export

# Scope variables
CDC=docker-compose
BZ=${WEBSITE_FOLDER}/boommz
DATA_PATH=${CURRENT_DIR}/data
LOG_APACHE_DIR=/var/log/apache2

#INIT UNI MEDIAS PROJECTS
init: clone-projects install-dev-program create-log launch
	@docker ps
	@echo "\033[0;32mProjects were successfully instanciate. You can now visit the site.\033[0m"
	@echo "\033[0;32mWaiting for MySQL container to start...\033[0m"
	@sleep 20
	@echo "\033[0;32mInitializing ${MYSQL_DATABASE} database...\033[0m"
	@make init-sql
	@echo "\033[0;32mProjects were initialized.\033[0m"

launch: install-db build start composer yarn-install yarn

#Recreate containers even if their configuration and image haven't changed.
recreate: export EXTRA_PARAMS=--force-recreate
recreate: stop build start

#Services are built once and then tagged.
#If you change a service’s Dockerfile or the contents of its build directory,
#run docker-compose build to rebuild it.
build:
	@${CDC} build

#Starts existing containers for a service.
start: check-env permission #create-log
	@${CDC} up -d ${EXTRA_PARAMS}

#Stops running containers without removing them.
stop:
	@${CDC} down -v

restart: stop start

#Stops containers and removes containers, networks, volumes, and images created by up.
purge:
	@${CDC} down --rmi all -v || echo "\033[0;31mNo containers were found\033[0m"

#DESTROY EVERYTHING : DOCKER, DOCKER-COMPOSE, PV AND PROJECTS (EXCEPTS UNED-DEV-ENV)
terminator: purge
	@echo "\033[0;31m[WARNING] THIS COMMAND WILL REMOVE ALL UNI-MEDIAS PROJECTS, DATABASES AND DOCKER EXECUTABLES IN 15 SEC [WARNING]\033[0m"
	@echo "\033[0;34mPress [CTRL + C] to abort...\033[0m"
	@sleep 15
	@sudo rm -rf ${BZ}
	@sudo rm -rf ${DATA_PATH}
	@sudo rm -rf ${LOG_APACHE_DIR}
	@sudo rm /usr/local/bin/docker-compose || echo "\033[0;31m/usr/local/bin/docker-compose not found\033[0m"
	@sudo apt-get remove -y docker docker-engine docker-ce docker.io
	@sudo apt-get --purge remove -y pv
	@sudo apt-get autoremove -y


#DESTROY EVERYTHING AND REBORN A NEW PROJECT ENVIRONMENT
phoenix: terminator init

# Shell command
connect:
	@echo "${CDC} exec ${C} ${S}"; \
	${CDC} exec ${C} ${S}

bash:
	@${CDC} exec ${C} bash -c ${O}

#connect to the apache container
apache:
	@C=apache S=bash make connect

#restart the apache container
apache-restart:
	@${CDC} restart apache

#restart the apache container
apache-stop:
	@${CDC} stop apache

#restart the apache container
apache-start:
	@${CDC} start apache

#connect to the php container
php:
	@C=php S=bash make connect

#restart the php container
php-restart:
	@${CDC} restart php

#restart the php container
php-stop:
	@${CDC} stop php

#restart the php container
php-start:
	@${CDC} start php

#connect to the mysql container
mysql:
	@C=mysql S=bash make connect

#restart the mysql container
mysql-restart:
	@${CDC} restart mysql

#restart the mysql container
mysql-stop:
	@${CDC} stop mysql

#restart the mysql container
mysql-start:
	@${CDC} start mysql

composer: composer-bz

#install vendor of boommz directory
composer-bz:
	@${CDC} exec php bash -c 'cd boommz && composer install'

#install vendor of boommz directory
comic-screen:
	@${CDC} exec php bash -c 'cd boommz && php bin/console app:comic_screen'


cache: cache-bz

#clear cache of boommz directory
cache-bz:
	@${CDC} exec bz_php bash -c "cd boommz && php bin/console c:c"


yarn: yarn-bz

#install assets of boommz directory
yarn-install:
	@${CDC} exec php bash -c 'cd boommz && yarn install'

#install assets of boommz directory
yarn-bz:
	@${CDC} exec php bash -c 'cd boommz && yarn encore dev'

yarn-bz-prod:
	@${CDC} exec php bash -c 'cd boommz && yarn encore production'

#watch assets of boommz directory
yarn-watch-bz:
	@${CDC} exec php bash -c 'cd boommz && yarn encore dev --watch'


create-log: check-log create-log-bz

check-log:
	@if [ ! -d "${LOG_APACHE_DIR}" ]; then \
		echo "\033[0;31mLog directory ${LOG_APACHE_DIR} not created yet. Creating...\033[0m"; \
#		mkdir ${CURRENT_DIR}/log; \
#		mkdir ${LOG_APACHE_DIR}; \
		sudo chown -R ${USERNAME}:www-data ${LOG_APACHE_DIR}; \
	else \
		echo "\033[0;32mLog directory already exists. No need to create.\033[0m"; \
	fi

create-log-bz:
	@if [ ! -d "${LOG_APACHE_DIR}/dev.boommz.com" ]; then \
		echo "\033[0;31mLog directory for Boommz.com not created yet. Creating...\033[0m"; \
		mkdir ${LOG_APACHE_DIR}/dev.boommz.com; \
		sudo chown -R ${USERNAME}:www-data ${LOG_APACHE_DIR}/dev.boommz.com; \
	else \
		echo "\033[0;32mLog directory for Boommz.com already exists. No need to create.\033[0m"; \
	fi

clone-projects: clone-bz

clone-bz:
	@if [ ! -d "${WEBSITE_FOLDER}/boommz.com" ]; then \
		echo "\033[0;31mProject Boommz.com not cloned yet. Cloning...\033[0m"; \
#		git clone git@github.com:ndeve/boommz.git "${WEBSITE_FOLDER}/boommz"; \
#		sudo chown -R ${USERNAME}:www-data ${WEBSITE_FOLDER}/boommz; \
	else \
		echo "\033[0;32mProject Boommz.com already exists. No need to clone.\033[0m"; \
	fi


create-data-dir:
	@if [ ! -d "${DATA_PATH}" ]; then \
		mkdir ${DATA_PATH}; \
		sudo chown -R ${USERNAME}:www-data ${DATA_PATH}; \
	else \
		echo  "\033[0;32mData directory already exists.\033[0m"; \
	fi \

permission:
	@if ! [ -f .yarn ]; then \
		touch .yarn; \
	fi
	@if ! [ -f .yarnrc ]; then \
		touch .yarnrc; \
	fi
	@if ! [ -d ~/.composer ]; then \
		mkdir ~/.composer; \
	fi
	@if ! [ -f ~/.gitconfig ]; then \
		touch ~/.gitconfig; \
	fi
	@if ! [ -d .cache ]; then \
		mkdir .cache; \
	fi
	@sudo chown -R ${USERNAME}:www-data .yarn .yarnrc ~/.composer ~/.gitconfig .cache ~/.ssh
	@sudo chmod 666 /var/run/docker.sock


check-env:
	@if [ ! -f .env ]; then \
		echo "\033[0;32mEnv file doesn't existing yet. Creating...\033[0m"; \
		cp sample.env .env;  \
	else \
		echo "\033[0;32m.env file already exist.\033[0m"; \
	fi

install-docker:
	@if ! hash docker 2>/dev/null; then \
		sudo apt-get remove -y docker docker-engine docker.io containerd runc; \
		sudo apt-get update; \
		sudo apt-get install -y apt-transport-https ca-certificates curl gnupg-agent software-properties-common; \
		curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -; \
		sudo apt-key fingerprint 0EBFCD88; \
		sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu ${LINUX_RELEASE_VERSION} stable"; \
		sudo apt-get update; \
		sudo apt-get install -y docker-ce docker-ce-cli containerd.io; \
		docker --version; \
	else \
		echo "\033[0;32mdocker already exist.\033[0m"; \
	fi

install-docker-compose:
	@if ! hash docker-compose 2>/dev/null; then \
		sudo curl -L "https://github.com/docker/compose/releases/download/1.23.2/docker-compose-${LINUX_NAME}-${LINUX_NAME_M}" -o /usr/local/bin/docker-compose; \
		sudo chmod +x /usr/local/bin/docker-compose; \
		docker-compose --version; \
	else \
		echo "\033[0;32mdocker-compose already exist.\033[0m"; \
	fi


install-pv:
	@if ! hash pv 2>/dev/null; then \
		sudo apt-get install -y pv; \
	else \
		echo "\033[0;32mpv already exist.\033[0m"; \
	fi

install-git:
	@if ! hash docker 2>/dev/null; then \
		sudo apt-get install -y git; \
	else \
		echo "\033[0;32mgit already exist.\033[0m"; \
	fi

install-php:
	@if ! hash php 2>/dev/null; then \
		sudo apt install -y php7.1; \
	else \
		echo "\033[0;32mphp already exist.\033[0m"; \
	fi

install-composer:
	@if ! hash composer 2>/dev/null; then \
        php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
        php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
        php composer-setup.php \
        php -r "unlink('composer-setup.php');" \
		sudo mv composer.phar /usr/local/bin/composer; \
	else \
		echo "\033[0;32mcomposer already exist.\033[0m"; \
	fi

test: test-bz

test-bz:
	@${CDC} exec php bash -c 'cd boommz && vendor/bin/phpunit -c phpunit.xml'