FROM node:19-alpine3.16
WORKDIR /phpadmin
COPY package*.json ./
RUN npm install
COPY . .
EXPOSE 8080
CMD ["node", "app.js"]