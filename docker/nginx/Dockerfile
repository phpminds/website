FROM centos:7

MAINTAINER Shaun Hare <shaun.hare@nottingham.ac.uk>

#
# Import the Centos-7 RPM GPG key to prevent warnings and Add EPEL Repository
#
RUN rpm --import http://mirror.centos.org/centos/RPM-GPG-KEY-CentOS-7 \
    && rpm --import http://dl.fedoraproject.org/pub/epel/RPM-GPG-KEY-EPEL-7 \
    && rpm -Uvh http://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm


RUN yum -y install \
    nginx openssl\
    && yum -y update bash \
    && rm -rf /var/cache/yum/* \
    && yum clean all



#
# UTC Timezone & Networking
#
RUN ln -sf /usr/share/zoneinfo/UTC /etc/localtime \
    && echo "NETWORKING=yes" > /etc/sysconfig/network


VOLUME /var/log/nginx


COPY default.conf /etc/nginx/conf.d/default.conf

RUN echo "daemon off;" >> /etc/nginx/nginx.conf


EXPOSE 80 443


CMD ["nginx"]



