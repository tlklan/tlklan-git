# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.network "private_network", ip: "192.168.15.15"

  # memory usage
  config.vm.provider "virtualbox" do |vb|
    vb.memory = "512"
  end

  # synced folder
  config.vm.synced_folder "./", "/vagrant",
    owner: "vagrant",
    group: "www-data",
    mount_options: ["dmode=775,fmode=664"]

  # provisioning
  config.vm.provision "shell", path: "provisioning/01-install-system-packages.sh",
    name: "01 - Install system packages"
  config.vm.provision "shell", path: "provisioning/02-configure-apache.sh",
    name: "02 - Configure Apache"
  config.vm.provision "shell", path: "provisioning/03-import-database.sh",
    name: "03 - Import database"
  config.vm.provision "shell", path: "provisioning/04-configure-application.sh",
    name: "04 - Configure application"

end
