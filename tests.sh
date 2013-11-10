#!/bin/bash

# use timeout or gtimeout
export TIMEOUT="timeout"
if [ -z `which timeout` ]; then
    export TIMEOUT="gtimeout"
fi

SUITE_RET=0

runtest() {
  NAME=$1
  OUTPUT=`$TIMEOUT -k 1 5 bash -c "$2" 2>&1`
  RET="$?"
  if [ $RET -eq 0 ]; then
    printf "$NAME: \e[00;32mPASSED\e[00m\n"
  elif [ $RET -eq 124 ]; then
    printf "$NAME: \e[00;31mTIMEOUT\e[00m\n"
    SUITE_RET=1
  else
    printf "$NAME: \e[00;31mFAILED\e[00m\n"
    echo "$OUTPUT"
    SUITE_RET=1
  fi
}



# SSH
runtest test_ssh "nc -w1 172.16.100.2 22 | grep '^SSH'"

# SMTP
runtest test_smtp "echo 'quit' | nc -w1 172.16.100.2 25 | grep 'ESMTP Postfix'"
runtest test_smtps "echo '' | openssl s_client -connect 172.16.100.2:465 | grep 'TLSv1/SSLv3, Cipher is DHE-RSA-AES256-SHA'"
runtest test_smtp_tls "echo '' | openssl s_client -connect 172.16.100.2:25 -starttls smtp | grep 'TLSv1/SSLv3, Cipher is DHE-RSA-AES256-SHA'"

# IMAP
runtest test_imaps "echo '' | openssl s_client -connect 172.16.100.2:993 | grep 'TLSv1/SSLv3, Cipher is DHE-RSA-AES256-SHA'"

# HTTP/S
runtest test_http "echo 'GET /' | nc -w1 172.16.100.2 80 | grep 'It works!'"
runtest test_https "echo '' | openssl s_client -connect 172.16.100.2:443 | grep 'TLSv1/SSLv3, Cipher is DHE-RSA-AES256-SHA'"
runtest test_blog_redirect "curl 172.16.100.2:80 -H 'Host: sovereign.local' -v | grep '301 Moved Permanently'"

# The blog will give 403 because it is an empty directory
runtest test_blog "curl https://172.16.100.2:443/ -H 'Host: sovereign.local' -v --insecure | grep '403 Forbidden'"

# Other web sites
runtest test_roundcube "curl https://172.16.100.2:443/ -H 'Host: mail.sovereign.local' -v --insecure | grep 'Welcome to Roundcube Webmail'"
runtest test_owncloud "curl https://172.16.100.2:443/ -H 'Host: cloud.sovereign.local' -v --insecure | grep 'ownCloud'"

# ZNC
runtest test_znc "echo '' | openssl s_client -connect 172.16.100.2:6697 | grep 'TLSv1/SSLv3, Cipher is AES256-SHA'"

exit $SUITE_RET
