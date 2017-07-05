FROM nginx
MAINTAINER GodZilL
RUN /bin/bash -c 'rm /etc/nginx/conf.d/default.conf'
ADD seo.conf /etc/nginx/conf.d/
ADD www/  /usr/share/nginx/html
