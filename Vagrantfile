VAGRANTFILE_API_VERSION = "2"

HOST_ALIASES = %w(adam.dev adam.prod)

Vagrant.require_version ">= 1.8.0"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.hostname = "adam.box"
  config.vm.network "private_network", type: "dhcp"

  config.vm.provider "virtualbox" do |v|
    v.name = "adam_dev"
    v.memory = 1024
  end

  config.hostmanager.enabled = true
  config.hostmanager.manage_host = true
  config.hostmanager.aliases = HOST_ALIASES
  config.hostmanager.ip_resolver = proc do |vm, resolving_vm|
    if vm.id
      `VBoxManage guestproperty get #{vm.id} "/VirtualBox/GuestInfo/Net/1/V4/IP"`.split()[1]
    end
  end

  $initial_apt = <<SCRIPT
if [ ! -e "/var/.vagrant_provision_initial_apt" ]; then
  echo Updating vm and cleaning up afterwards ..
  touch /var/.vagrant_provision_initial_apt
  apt-get -y update
  apt-get -y dist-upgrade
  apt-get -y autoremove
fi
SCRIPT
  $install_ansible = <<SCRIPT
if [ ! -e "/etc/apt/sources.list.d/ansible-ansible-trusty.list" ]; then
  echo Adding ansible repo ..
  apt-get install -y software-properties-common language-pack-en
  apt-add-repository -y ppa:ansible/ansible
fi
if [ ! -x "$(command -v ansible)" ]; then
  echo Installing ansible ..
  apt-get update -y
  apt-get install -y ansible
fi
SCRIPT

  config.vm.provision :shell, :inline => <<-EOT
    echo 'LC_ALL="en_US.UTF-8"'  >  /etc/default/locale
  EOT
  config.vm.provision :shell, inline: $initial_apt
  config.vm.provision :shell, inline: $install_ansible
  # Add fix for https://github.com/mitchellh/vagrant/issues/6793#issuecomment-172408346
  if Vagrant::VERSION =~ /^1.8.[0-1]/
    config.vm.provision "shell" do |s|
      s.inline = '[[ ! -f $1 ]] || grep -F -q "$2" $1 || sed -i "/__main__/a \\    $2" $1'
      s.args = ['/usr/bin/ansible-galaxy', "if sys.argv == ['/usr/bin/ansible-galaxy', '--help']: sys.argv.insert(1, 'info')"]
    end
  end
  config.vm.provision "ansible_local" do |ansible|
    ansible.playbook = "playbook.yml"
    ansible.galaxy_role_file = "requirements.yml"
    ansible.provisioning_path = "/vagrant/.ansible"
  end

  config.vm.synced_folder ".", "/vagrant", type: :nfs
  config.vm.synced_folder ".", "/vagrant-nfs", type: :nfs
  config.bindfs.bind_folder "/vagrant-nfs", "/var/www/adam", "force-user": "www-data"

end
