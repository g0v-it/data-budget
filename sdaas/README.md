# g0v-data smart data management platform


## Credits and license

- the rdf datastore implementation is based on [Docker Blazegraph](https://github.com/lyrasis/docker-blazegraph)
- the sdaas platform is derived from [LinkedData.Center SDaas Product](https://it.linkeddata.center/p/sdaas) and licensed with CC-by-nd-nc by LinkedData.Center to g0v community

#!/usr/bin/env bash
# Run this script on a host with a fresh ubuntu 16.04 linux distribution.
# see https://bitbucket.org/linkeddatacenter/sdaas/wiki/Installation

echo "LinkedData.Center Smart Data as a Service Provisioning script"

set -e

if [ $(id -u) -ne 0 ];then
        echo "Please launch this script as root"
        exit 2
fi

if [ "$(gawk -F= '/^VERSION_ID/{print $2}' /etc/os-release)" != '"16.04"' ]; then
        echo "Sorry this script is for ubuntu 16.04 only"
        exit 3
fi

# calculate optimal sdaas memory configuration
maxMemory=$(grep MemTotal /proc/meminfo | awk '{print $2}')
if [ $maxMemory -gt 8192000 ]; then
	JVM_OPTS="-Djava.awt.headless=true -server -Xmx8g -XX:MaxDirectMemorySize=3000m -XX:+UseG1GC"
elif [ $maxMemory -gt 6144000 ] && [ $maxMemory -lt 8192000 ]; then
	JVM_OPTS="-Djava.awt.headless=true -server -Xmx6g -XX:MaxDirectMemorySize=3000m -XX:+UseG1GC"
elif [ $maxMemory -gt 4096000 ] && [ $maxMemory -lt 6144000 ]; then
	JVM_OPTS="-Djava.awt.headless=true -server -Xmx4g -XX:MaxDirectMemorySize=2000m -XX:+UseG1GC"
elif [ $maxMemory -gt 2048000 ] && [ $maxMemory -lt 4096000 ]; then
	JVM_OPTS="-Djava.awt.headless=true -server -Xmx2g -XX:MaxDirectMemorySize=1500m -XX:+UseG1GC"
elif [ $maxMemory -gt 1024000 ] && [ $maxMemory -lt 2048000 ]; then
	JVM_OPTS="-Djava.awt.headless=true -server -Xmx1g -XX:MaxDirectMemorySize=500m -XX:+UseG1GC"
else
	echo "Too little memory to run sdaas (min 1024000, found $maxMemory)"
	exit 1
fi


apt-get update

###### install raptor
apt-get -y install raptor2-utils curl 

##### install csv tool
apt-get -y install csvtool

##### Install local instance of blazegraph 

# optimize kernel for blazegraph
echo 1 > /proc/sys/net/ipv4/tcp_tw_reuse
echo 'vm.swappiness = 0' >> /etc/sysctl.d/10-vm.swappiness.conf

# install java
apt-get -y install default-jre

# install blazegraph
curl -o /tmp/blazegraph.deb https://netix.dl.sourceforge.net/project/bigdata/bigdata/2.1.4/blazegraph.deb
dpkg -i /tmp/blazegraph.deb
sysctl -p || true >& /dev/null
service blazegraph stop
sed -i "s/^JVM_OPTS=.*/JVM_OPTS=\"$JVM_OPTS\"/" /etc/default/blazegraph
update-rc.d blazegraph defaults
service blazegraph start

##### install python and csvtool
apt-get -y install python3-pip
pip3 install --upgrade pip
pip3 install csvtomd --upgrade


##### install php and composer
apt-get -y install git curl php7.0-cli php7.0-common php7.0-mbstring php7.0-bz2 php7.0-zip php7.0-xml php7.0-curl unzip
if [ ! -x /usr/local/bin/composer ]; then
	curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
	rm -rf .composer
fi

############## Optional packages

### to to use an S2 Amazon Datalake ############
#pip3 install awscli --upgrade
#
#if  [ ! -d "$HOME/.aws" ]; then
#	echo "RUN aws configure"
#fi

### to to use xls2csv ############
#cd /tmp curl; http://ftp.wagner.pp.ru/pub/catdoc/catdoc-0.95.tar.gz | tar xz
#cd /tmp/catdoc-0.95; .configure; make; make install

### to to use xlsx2csv ############
#pip install xlsx2csv --upgrade