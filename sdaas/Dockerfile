FROM jetty:9.4.2-jre8-alpine

LABEL authors="enrico@linkeddata.center"

ENV JETTY_WEBAPPS /var/lib/jetty/webapps
ENV BLAZEGRAPH_NAME sdaas
ENV BLAZEGRAPH_RW_PATH /RWStore.properties
ENV BLAZEGRAPH_VERSION 2_1_4
ENV BLAZEGRAPH_VERSION_URL https://github.com/blazegraph/database/releases/download/BLAZEGRAPH_RELEASE_${BLAZEGRAPH_VERSION}/blazegraph.war
ENV KEES_DIR /kees
ENV SD_LOCAL_BG_ENDPOINT http://localhost:8080/${BLAZEGRAPH_NAME}
ENV PATH="/usr/local/bin/sdaas:${PATH}"

COPY RWStore.properties $BLAZEGRAPH_RW_PATH
COPY sdaas-bin /usr/local/bin/sdaas
COPY boilerplate $KEES_DIR

# load regular linux tools required by sdaas:
# see https://wiki.alpinelinux.org/wiki/How_to_get_regular_stuff_working
RUN apk --no-cache add \
	bash \
	binutils \
	coreutils \
	curl \
	findutils \
	gawk \
	grep \
	openssl \
	php7 \
	raptor2 \
	sed \
	sudo \
	unzip \
	util-linux
RUN chown jetty:jetty $BLAZEGRAPH_RW_PATH

# load and install blazegraph.war
RUN wget -O ${JETTY_WEBAPPS}/${BLAZEGRAPH_NAME}.war $BLAZEGRAPH_VERSION_URL

# These are based on recommendations from the OpenShift docs in
# https://docs.openshift.org/latest/creating_images/guidelines.html#use-uid
# because of its use of random uids.
RUN chgrp -R 0 /var/lib/jetty && \
    chmod -R g=u /var/lib/jetty

RUN chmod -R 0755 /usr/local/bin/sdaas

EXPOSE 8080

WORKDIR  ${KEES_DIR}

# We must not run as root, because of OpenShift policy
# We have to make sure we run in the correct folder, since that might be messed
# up because of OpenShift's random uids (see the link above for more info).
# The blazegraph docs also hint about wrong startup folder being a possible
# problem if rules.log is not found:
# https://wiki.blazegraph.com/wiki/index.php/NanoSparqlServer#Common_Startup_Problems
CMD cd /var/lib/jetty && sudo -u jetty java -jar /usr/local/jetty/start.jar -Dcom.bigdata.rdf.sail.webapp.ConfigParams.propertyFile=/RWStore.properties
