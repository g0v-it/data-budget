# -*- mode: ruby -*-
# vi: set ft=ruby :

$script = <<-SCRIPT
	apt-get update
	
	# Install php and composer
	apt-get -y install git curl php7.0-cli php7.0-common php7.0-xml php7.0-zip 
	curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

	echo "======================================================"
	echo "Connect to the running box instance with 'vagrant ssh'"
	echo "======================================================"
SCRIPT


VAGRANTFILE_API_VERSION = '2'
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
	config.vm.box = "bento/ubuntu-16.04"
	config.vm.provision "shell", inline: $script
end