import os
import socket

import testinfra.utils.ansible_runner

testinfra_hosts = testinfra.utils.ansible_runner.AnsibleRunner(
    os.environ['MOLECULE_INVENTORY_FILE']).get_hosts('all')


def test_hosts_file(host):
    f = host.file('/etc/hosts')

    assert f.exists
    assert f.user == 'root'
    assert f.group == 'root'


def test_sshd_listening(host):
    sshd = host.socket('tcp://0.0.0.0:22')

    assert sshd.is_listening


def test_sshd_banner(host):
    """SSH is responding with its banner"""
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.connect(('localhost', 22))
    data = s.recv(1024)
    s.close()

    assert 'SSH-2.0-OpenSSH' in str(data)
