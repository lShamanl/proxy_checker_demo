#!/bin/bash

FROM --platform=linux/amd64 nginx:1.17-alpine

RUN apk add --no-cache curl

COPY ./docker/common/nginx/conf.d /etc/nginx/conf.d

WORKDIR /app

COPY ./public ./public
