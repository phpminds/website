#! /bin/bash
HOST_IP=`ifconfig | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1'`
echo "setting xedbug remote ip to ${HOST_IP}"
export HOST_IP="${HOST_IP}"
docker-compose up -d
