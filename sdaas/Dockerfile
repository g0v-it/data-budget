FROM jetty:9.4.2-jre8-alpine

LABEL authors="enrico@linkeddata.center"

ENV JETTY_WEBAPPS /var/lib/jetty/webapps
ENV BLAZEGRAPH_NAME bigdata
ENV BLAZEGRAPH_RW_PATH /RWStore.properties
ENV BLAZEGRAPH_VERSION 2_1_4
ENV BLAZEGRAPH_VERSION_URL https://github.com/blazegraph/database/releases/download/BLAZEGRAPH_RELEASE_${BLAZEGRAPH_VERSION}/blazegraph.war
ENV PATH="/usr/local/bin/sdaas:${PATH}"

ADD RWStore.properties $BLAZEGRAPH_RW_PATH
ADD sdaas-bin /usr/local/bin/sdaas
ADD g0v-it /home/sdaas

# see https://wiki.alpinelinux.org/wiki/How_to_get_regular_stuff_working

RUN apk update && apk --no-cache add sudo util-linux coreutils binutils findutils grep openssl bash unzip curl gawk raptor2 php7
RUN wget -O ${JETTY_WEBAPPS}/${BLAZEGRAPH_NAME}.war $BLAZEGRAPH_VERSION_URL
RUN chown jetty:jetty $BLAZEGRAPH_RW_PATH

# These are based on recommendations from the OpenShift docs in
# https://docs.openshift.org/latest/creating_images/guidelines.html#use-uid
# because of its use of random uids.
RUN chgrp -R 0 /var/lib/jetty && \
    chmod -R g=u /var/lib/jetty

EXPOSE 8080
	
# We have to make sure we run in the correct folder, since that might be messed
# up because of OpenShift's random uids (see the link above for more info).
# The blazegraph docs also hint about wrong startup folder being a possible
# problem if rules.log is not found:
# https://wiki.blazegraph.com/wiki/index.php/NanoSparqlServer#Common_Startup_Problems
#CMD cd /var/lib/jetty && sudo -u jetty "java -jar /usr/local/jetty/start.jar -Dcom.bigdata.rdf.sail.webapp.ConfigParams.propertyFile=/RWStore.properties"
