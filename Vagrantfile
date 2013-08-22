# The following patch needs to be applied for ansible to run cleanly on vagrant up:
# https://github.com/mitchellh/vagrant/pull/1723.diff on /opt/vagrant/embedded/gems/gems/vagrant-1.2.x

curdir=File.dirname(__FILE__)

$boxes = [
    {
        :name => "ansible.local",
        :forwards => { 22 => 22222, 80 => 80, 25 => 25, 143 => 143, 465 => 465, 993 => 993 }
    }
]

Vagrant.configure("2") do |config|

    config.cache.auto_detect = true

    # define some standard configuration for memory 
    #config.vm.provider :lxc do |lxc|
    #    lxc.customize 'cgroup.memory.limit_in_bytes', '400M'
    #    lxc.customize 'cgroup.memory.memsw.limit_in_bytes', '500M'
    #end

    config.vm.provider :lxc do |lxc, override|
        override.vm.box = "precise64"
        override.vm.box_url = "http://bit.ly/vagrant-lxc-precise64-2013-05-08"
    end
    $boxes.each do | opts | 
        config.vm.define(opts[:name]) do |config|
            config.vm.hostname =   "%s" % [ opts[:name].to_s ]
            opts[:forwards].each do |guest_port,host_port|
                config.vm.network :forwarded_port, guest: guest_port, host: host_port
            end if opts[:forwards]

            config.vm.provision :shell, :inline => 'if [ ! -e /root/apt.updated ]; then apt-get update && touch /root/apt.updated ; fi; apt-get install -y python-apt'
            config.vm.provision :ansible do |ansible|
                ansible.playbook = "site.yml"
                #ansible.inventory_file = "hosts"
            end
            config.vm.provision :shell, :inline => "echo [test] > /vagrant/hosts.autogen && ifconfig eth0 | grep 'inet addr'|awk '{print $2}' |cut -d: -f2 >> /vagrant/hosts.autogen"
            config.vm.provision :shell, :inline => "echo 'VM is setup'"
        end if ! opts[:disabled]
    end
end
