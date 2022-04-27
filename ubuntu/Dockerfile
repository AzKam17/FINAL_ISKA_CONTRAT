FROM ubuntu:20.04

RUN apt-get update
RUN apt-get install -y software-properties-common
RUN add-apt-repository ppa:ondrej/php -y
RUN apt-get update

RUN apt -y install php7.4 \
    && apt-get install -y php7.4-cli php7.4-json php7.4-common php7.4-mysql php7.4-zip php7.4-gd php7.4-mbstring php7.4-curl php7.4-xml php7.4-bcmath php7.4-dev

RUN  pecl install redis
RUN echo "extension=redis.so" >> /etc/php/7.4/cli/php.ini

RUN apt-get -y install curl

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list \
    && apt update \
    && apt -y install symfony-cli

RUN mkdir /iska
WORKDIR /iska/
CMD ["symfony", "serve"]