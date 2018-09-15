FROM linkeddatacenter/sdaas-ce:2.1.0

COPY ./ /tmp/g0v

RUN apk --no-cache add \
	php7-json
	
# start a temporary sdaas instance to rebuild the knowledge base  
RUN sdaas-start && \
	cd /tmp/g0v; sdaas -f build.sdaas --reboot && \
	sdaas-stop ; \
	rm -rf /tmp/g0v	

# start platform in readonly	
CMD sdaas-start --foreground --readonly
