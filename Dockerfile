FROM centos:7

ENV TERM=xterm-256color

RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm \
      http://rpms.remirepo.net/enterprise/remi-release-7.rpm \
    && yum-config-manager --enable epel \
    && yum-config-manager --enable remi-php80 \
    && curl -sL https://rpm.nodesource.com/setup_16.x | bash -

RUN yum install -y --setopt=tsflags=nodocs \
    php-cli \
    php-common \
    php-gd \
    php-exif \
    php-json \
    php-mbstring \
    php-opcache \
    php-process \
    php-fpm \
    php-xml \
    php-zip \
    unzip \
    zip \
    nodejs \
    mesa-dri-drivers \
    libexif \
    libcanberra-gtk2 \
    libcanberra \
    && yum clean all \
    && rm -rf /var/cache/yum

WORKDIR /app

COPY bin/ /app/bin
COPY src/ /app/src

COPY composer.json /app
COPY composer.lock /app
RUN bin/composer install --no-progress

RUN rpm -Va --nofiles --nodigest bin/google-chrome-stable_current_x86_64.rpm

COPY package.json /app
COPY package-lock.json /app
RUN npm ci puppeteer

ENTRYPOINT ["bin/broken-links-tool"]
