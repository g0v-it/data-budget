FROM maven:3.5.0-jdk-8 as lodview
RUN apt-get update \
    && apt-get install -y git

RUN git clone https://github.com/dvcama/LodView.git

COPY conf.ttl /LodView/src/main/webapp/WEB-INF/

RUN cd LodView \
  && mvn compile war:war

FROM tomcat:8
COPY --from=lodview /LodView/target/lodview.war /usr/local/tomcat/webapps/
ADD http://downloads.visualdataweb.de/webvowl_1.0.6.war /usr/local/tomcat/webapps/webvowl.war