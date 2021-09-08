# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "ubuntu/bionic64"
  config.vm.network "private_network", ip: "192.168.15.15"

  # memory usage
  config.vm.provider "virtualbox" do |vb|
    vb.memory = "2048"
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
  config.vm.provision "shell", path: "provisioning/03-configure-mysql.sh",
    name: "03 - Configure MySQL"
  config.vm.provision "shell", path: "provisioning/04-import-database.sh",
    name: "04 - Import database"
  config.vm.provision "shell", path: "provisioning/05-configure-application.sh",
    name: "05 - Configure application"

end
