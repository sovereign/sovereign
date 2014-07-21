# -*- mode: ruby -*-
# If you're having issues, upgrade to Vagrant 1.3.x. It generates an inventory automatically:
# https://github.com/mitchellh/vagrant/blob/master/CHANGELOG.md#130-september-5-2013

Vagrant.configure('2') do |config|
  # Debian 7 is the officially supported Linux distribution
  config.vm.box = 'box-cutter/debian75'

  # Comment the entry above and uncomment one of these two entries
  # below if you want to develop/test against Ubuntu 12.04/14.04.
  # config.vm.box = 'box-cutter/ubuntu1204'
  # config.vm.box = 'box-cutter/ubuntu1404'

  config.vm.provider :virtualbox do |v|
    v.memory = 512
  end

  config.vm.provider :vmware_fusion do |v|
    v.vmx["memsize"] = "512"
  end

  config.vm.hostname = 'sovereign.local'

  config.vm.network "private_network", ip: "172.16.100.2"

  config.vm.provision :ansible do |ansible|
    ansible.playbook = 'site.yml'
    ansible.host_key_checking = false
    ansible.extra_vars = { ansible_ssh_user: 'vagrant', testing: true }

    # ansible.tags = ['blog']
    # ansible.skip_tags = ['openvpn']
    # ansible.verbose = 'vvvv'
  end

end
