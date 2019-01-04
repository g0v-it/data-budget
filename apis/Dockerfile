FROM node:8

#Variables
ENV WORK_DIR /usr/src/app/

#Set working diractory
WORKDIR $WORK_DIR

#FOR develop
RUN npm install -g nodemon

# Install app dependencies
COPY package*.json ./
RUN npm install
RUN npm install rdflib --save

#FOR_DEV
RUN mv $WORK_DIR/node_modules /node_modules

COPY . .
EXPOSE 80

CMD [ "npm", "start" ]

