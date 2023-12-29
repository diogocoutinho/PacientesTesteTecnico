FROM node:14

WORKDIR /app

RUN npm install -g laravel-echo-server

COPY ./laravel-echo-server.json ./

CMD ["laravel-echo-server", "start"]
