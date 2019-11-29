FROM composer as build-stage
WORKDIR /app

COPY gateways/composer.* /app/
RUN composer install --no-dev


FROM linkeddatacenter/sdaas-ce:2.4.0

RUN apk --no-cache add \
	php7 \
	php7-json \
	php7-mbstring

COPY ./ /tmp/g0v
COPY --from=build-stage /app/vendor /tmp/g0v/gateways/vendor

ENV JAVA_OPTS="-Xmx2g"

# start a temporary sdaas instance to rebuild the knowledge base (tith text index) 
RUN /sdaas-start && \
	cd /tmp/g0v; sdaas -f build.sdaas --reboot && \
	curl -X POST http://localhost:8080/sdaas/namespace/kb/textIndex?force-index-create=true && \
 	/sdaas-stop ; \
	rm -rf /tmp/g0v	

# start platform in readonly	
CMD /sdaas-start --foreground --readonly