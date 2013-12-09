#!/bin/bash
gpgResult=`gpg --decrypt tarsnap-sigs-{{ tarsnap_version }}.asc`
sha=${gpgResult#*=}
echo $sha > /root/tarsnapSha
echo $sha
