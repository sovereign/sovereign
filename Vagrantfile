# If you're having issues, upgrade to Vagrant 1.3.x. It generates an inventory automatically:
# https://github.com/mitchellh/vagrant/blob/master/CHANGELOG.md#130-september-5-2013

Vagrant.configure('2') do |config|

  config.vm.provider :virtualbox do |vbox, override|
    override.vm.box = 'wheezy64'
    override.vm.box_url = 'https://sovereign.lukecyca.com/vagrant/wheezy64.box'
    vbox.customize ["modifyvm", :id, "--memory", 512]
  end

  config.vm.provider :vmware_fusion do |vbox, override|
    override.vm.box = 'wheezy64'
    # source: https://github.com/misheska/basebox-packer
    override.vm.box_url = 'https://dl.dropboxusercontent.com/s/g8djjlz1x5tz30j/debian72.box?token_hash=AAH1_-tgqx5PabhxLXD-X7hFEZ9x_-a899fMAYI_Kgd4Bg&dl=1'
    vbox.customize ["modifyvm", :id, "--memory", 512]
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
