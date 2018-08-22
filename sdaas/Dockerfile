FROM jetty:9.4.2-jre8-alpine

LABEL authors="enrico@linkeddata.center"

ENV JETTY_WEBAPPS /var/lib/jetty/webapps
ENV BLAZEGRAPH_NAME bigdata
ENV BLAZEGRAPH_RW_PATH /RWStore.properties
ENV BLAZEGRAPH_VERSION 2_1_4
ENV BLAZEGRAPH_VERSION_URL https://github.com/blazegraph/database/releases/download/BLAZEGRAPH_RELEASE_${BLAZEGRAPH_VERSION}/blazegraph.war

ADD RWStore.properties $BLAZEGRAPH_RW_PATH

RUN apk --no-cache add openssl
RUN wget -O ${JETTY_WEBAPPS}/${BLAZEGRAPH_NAME}.war $BLAZEGRAPH_VERSION_URL
RUN chown jetty:jetty $BLAZEGRAPH_RW_PATH

# These are based on recommendations from the OpenShift docs in
# https://docs.openshift.org/latest/creating_images/guidelines.html#use-uid
# because of its use of random uids.
RUN chgrp -R 0 /var/lib/jetty && \
    chmod -R g=u /var/lib/jetty

#RUN apt-get update && \
#	apt-get -y install unzip curl gawk raptor2-utils csvtool install python3-pip &&\
#	pip3 install csvtomd &&\
#	apt-get -y install git curl php7.0-cli php7.0-common php7.0-mbstring php7.0-bz2 php7.0-zip php7.0-xml php7.0-curl 
