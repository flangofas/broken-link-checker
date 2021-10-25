FROM centos:7

<<<<<<< HEAD
ENV TERM=xterm-256color

=======
>>>>>>> @{-1}
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
<<<<<<< HEAD
=======
    php-pdo \
>>>>>>> @{-1}
    php-process \
    php-fpm \
    php-xml \
    php-zip \
    unzip \
    zip \
<<<<<<< HEAD
    nodejs \
    mesa-dri-drivers \
    libexif \
    libcanberra-gtk2 \
    libcanberra \
=======
>>>>>>> @{-1}
    && yum clean all \
    && rm -rf /var/cache/yum

WORKDIR /app

<<<<<<< HEAD
COPY bin/ /app/bin
COPY src/ /app/src

COPY composer.json /app
COPY composer.lock /app
RUN bin/composer install --no-progress

RUN rpm -Va --nofiles --nodigest bin/google-chrome-stable_current_x86_64.rpm

COPY package.json /app
COPY package-lock.json /app
RUN npm ci puppeteer
=======
COPY composer.json /app

COPY  src/ /app/src
COPY  bin/ /app/bin

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN composer install

RUN yum install -y --setopt=tsflags=nodocs \
    nodejs \
    mesa-dri-drivers \
    libexif \
    libcanberra-gtk2 \
    libcanberra \
    && yum clean all

ADD https://dl.google.com/linux/direct/google-chrome-stable_current_x86_64.rpm /root/google-chrome-stable_current_x86_64.rpm
RUN yum -y install /root/google-chrome-stable_current_x86_64.rpm; yum clean all
RUN dbus-uuidgen > /etc/machine-id

RUN npm install --global --unsafe-perm puppeteer
>>>>>>> @{-1}

ENTRYPOINT ["bin/broken-links-tool"]
