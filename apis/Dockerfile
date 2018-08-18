FROM node:8

#Variables
ENV WORK_DIR /usr/src/app

#Set working diractory
WORKDIR $WORK_DIR

# Install app dependencies
COPY package*.json ./
RUN npm install

#FOR PRODUCTION
RUN npm install -g nodemon

COPY . .
EXPOSE 80

CMD [ "npm", "start" ]