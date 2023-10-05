FROM node:14

WORKDIR /app

# To Fix Permissions for Packages
RUN yarn config set unsafe-perm true

RUN mkdir -p /app/node_modules

RUN chown -R node /app/node_modules

USER node
