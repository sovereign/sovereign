import unittest
from time import sleep
import uuid
import socket
import requests
import os

TEST_SERVER = 'sovereign.local'
TEST_ADDRESS = 'sovereign@sovereign.local'
TEST_PASSWORD = 'foo'
CA_BUNDLE = 'roles/common/files/wildcard_ca.pem'


socket.setdefaulttimeout(5)
os.environ['REQUESTS_CA_BUNDLE'] = CA_BUNDLE


class SSHTests(unittest.TestCase):
    def test_ssh_banner(self):
        """SSH is responding with its banner"""
        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        s.connect((TEST_SERVER, 22))
        data = s.recv(1024)
        s.close()

        self.assertRegexpMatches(data, '^SSH-2.0-OpenSSH')


class WebTests(unittest.TestCase):
    def test_blog_http(self):
        """Blog is redirecting to https"""
        # FIXME: requests won't verify sovereign.local with *.sovereign.local cert
        r = requests.get('http://' + TEST_SERVER, verify=False)

        # We should be redirected to https
        self.assertEquals(r.history[0].status_code, 301)
        self.assertEquals(r.url, 'https://' + TEST_SERVER + '/')

        # 403 - Since there is no documents in the blog directory
        self.assertEquals(r.status_code, 403)

    def test_mail_autoconfig_http_and_https(self):
        """Email autoconfiguration XML file is accessible over both HTTP and HTTPS"""

        # Test getting the file over HTTP and HTTPS
        for proto in ['http', 'https']:
            r = requests.get(proto + '://autoconfig.' + TEST_SERVER + '/mail/config-v1.1.xml')

            # 200 - We should see the XML file
            self.assertEquals(r.status_code, 200)
            self.assertIn('application/xml', r.headers['Content-Type'])
            self.assertIn('clientConfig version="1.1"', r.content)

    def test_webmail_http(self):
        """Webmail is redirecting to https and displaying login page"""
        r = requests.get('http://mail.' + TEST_SERVER)

        # We should be redirected to https
        self.assertEquals(r.history[0].status_code, 301)
        self.assertEquals(r.url, 'https://mail.' + TEST_SERVER + '/')

        # 200 - We should be at the login page
        self.assertEquals(r.status_code, 200)
        self.assertIn(
            'Welcome to Roundcube Webmail',
            r.content
        )

    def test_zpush_http_unauthorized(self):
        r = requests.get('http://mail.' + TEST_SERVER + '/Microsoft-Server-ActiveSync')

        # We should be redirected to https
        self.assertEquals(r.history[0].status_code, 301)
        self.assertEquals(r.url, 'https://mail.' + TEST_SERVER + '/Microsoft-Server-ActiveSync')

        # Unauthorized
        self.assertEquals(r.status_code, 401)

    def test_zpush_https(self):
        r = requests.post('https://mail.' + TEST_SERVER + '/Microsoft-Server-ActiveSync',
                          auth=('sovereign@sovereign.local', 'foo'),
                          params={
                              'DeviceId': '1234',
                              'DeviceType': 'testbot',
                              'Cmd': 'Ping',
                          })

        self.assertEquals(r.headers['content-type'],
                          'application/vnd.ms-sync.wbxml')
        self.assertEquals(r.headers['ms-server-activesync'],
                          '14.0')

    def test_owncloud_http(self):
        """ownCloud is redirecting to https and displaying login page"""
        r = requests.get('http://cloud.' + TEST_SERVER)

        # We should be redirected to https
        self.assertEquals(r.history[0].status_code, 301)
        self.assertEquals(r.url, 'https://cloud.' + TEST_SERVER + '/')

        # 200 - We should be at the login page
        self.assertEquals(r.status_code, 200)
        self.assertIn(
            'ownCloud',
            r.content
        )

    def test_selfoss_http(self):
        """selfoss is redirecting to https and displaying login page"""
        r = requests.get('http://news.' + TEST_SERVER)

        # We should be redirected to https
        self.assertEquals(r.history[0].status_code, 301)
        self.assertEquals(r.url, 'https://news.' + TEST_SERVER + '/')

        # 200 - We should be at the login page
        self.assertEquals(r.status_code, 200)
        self.assertIn(
            'selfoss',
            r.content
        )
        self.assertIn(
            'login',
            r.content
        )

    def test_cgit_http(self):
        """CGit web interface is displaying home page"""
        r = requests.get('http://git.' + TEST_SERVER, verify=False)

        # We should be redirected to https
        self.assertEquals(r.history[0].status_code, 301)
        self.assertEquals(r.url, 'https://git.' + TEST_SERVER + '/')

        # 200 - We should be at the repository page
        self.assertEquals(r.status_code, 200)
        self.assertIn(
            'git repository',
            r.content
        )

    def test_newebe_http(self):
        """Newebe is displaying home page"""
        r = requests.get('http://newebe.' + TEST_SERVER, verify=False)

        # We should be redirected to https
        self.assertEquals(r.history[0].status_code, 301)
        self.assertEquals(r.url, 'https://newebe.' + TEST_SERVER + '/')

        # 200 - We should be at the repository page
        self.assertEquals(r.status_code, 200)
        self.assertIn(
            'Newebe, Freedom to Share',
            r.content
        )


class IRCTests(unittest.TestCase):
    def test_irc_auth(self):
        """ZNC is accepting encrypted logins"""
        import ssl
        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        ssl_sock = ssl.wrap_socket(s, ca_certs=CA_BUNDLE, cert_reqs=ssl.CERT_REQUIRED)
        ssl_sock.connect((TEST_SERVER, 6697))

        # Check the encryption parameters
        cipher, version, bits = ssl_sock.cipher()
        self.assertEquals(cipher, 'AES256-GCM-SHA384')
        self.assertEquals(version, 'TLSv1/SSLv3')
        self.assertEquals(bits, 256)

        # Login
        ssl_sock.send('CAP REQ sasl multi-prefix\r\n')
        ssl_sock.send('PASS foo\r\n')
        ssl_sock.send('NICK sovereign\r\n')
        ssl_sock.send('USER sovereign 0 * Sov\r\n')

        # Read until we see the ZNC banner (or timeout)
        while 1:
            r = ssl_sock.recv(1024)
            if 'Connected to ZNC' in r:
                break


def new_message(from_email, to_email):
    """Creates an email (headers & body) with a random subject"""
    from email.mime.text import MIMEText
    msg = MIMEText('Testing')
    msg['Subject'] = uuid.uuid4().hex[:8]
    msg['From'] = from_email
    msg['To'] = to_email
    return msg.as_string(), msg['subject']


class MailTests(unittest.TestCase):
    def assertIMAPReceived(self, subject):
        """Connects with IMAP and asserts the existence of an email, then deletes it"""
        import imaplib

        sleep(1)

        # Login to IMAP
        m = imaplib.IMAP4_SSL(TEST_SERVER, 993)
        m.login(TEST_ADDRESS, TEST_PASSWORD)
        m.select()

        # Assert the message exists
        typ, data = m.search(None, '(SUBJECT \"{}\")'.format(subject))
        self.assertTrue(len(data[0].split()), 1)

        # Delete it & logout
        m.store(data[0].strip(), '+FLAGS', '\\Deleted')
        m.expunge()
        m.close()
        m.logout()

    def assertPOP3Received(self, subject):
        """Connects with POP3S and asserts the existence of an email, then deletes it"""
        import poplib

        sleep(1)

        # Login to POP3
        mail = poplib.POP3_SSL(TEST_SERVER, 995)
        mail.user(TEST_ADDRESS)
        mail.pass_(TEST_PASSWORD)

        # Assert the message exists
        num = len(mail.list()[1])
        resp, text, octets = mail.retr(num)
        self.assertTrue("Subject: " + subject in text)

        # Delete it and log out
        mail.dele(num)
        mail.quit()

    def test_imap_requires_ssl(self):
        """IMAP without SSL is NOT available"""
        import imaplib

        with self.assertRaisesRegexp(socket.timeout, 'timed out'):
            imaplib.IMAP4(TEST_SERVER, 143)

    def test_pop3_requires_ssl(self):
        """POP3 without SSL is NOT available"""
        import poplib

        with self.assertRaisesRegexp(socket.timeout, 'timed out'):
            poplib.POP3(TEST_SERVER, 110)

    def test_smtps(self):
        """Email sent from an MUA via SMTPS is delivered"""
        import smtplib
        msg, subject = new_message(TEST_ADDRESS, 'root@sovereign.local')
        s = smtplib.SMTP_SSL(TEST_SERVER, 465)
        s.login(TEST_ADDRESS, TEST_PASSWORD)
        s.sendmail(TEST_ADDRESS, ['root@sovereign.local'], msg)
        s.quit()
        self.assertIMAPReceived(subject)

    def test_smtps_delimiter_to(self):
        """Email sent to address with delimiter is delivered"""
        import smtplib
        msg, subject = new_message(TEST_ADDRESS, 'root+foo@sovereign.local')
        s = smtplib.SMTP_SSL(TEST_SERVER, 465)
        s.login(TEST_ADDRESS, TEST_PASSWORD)
        s.sendmail(TEST_ADDRESS, ['root+foo@sovereign.local'], msg)
        s.quit()
        self.assertIMAPReceived(subject)

    def test_smtps_requires_auth(self):
        """SMTPS with no authentication is rejected"""
        import smtplib
        s = smtplib.SMTP_SSL(TEST_SERVER, 465)

        with self.assertRaisesRegexp(smtplib.SMTPRecipientsRefused, 'Access denied'):
            s.sendmail(TEST_ADDRESS, ['root@sovereign.local'], 'Test')

        s.quit()

    def test_smtp(self):
        """Email sent from an MTA is delivered"""
        import smtplib
        msg, subject = new_message('someone@example.com', TEST_ADDRESS)
        s = smtplib.SMTP(TEST_SERVER, 25)
        s.sendmail('someone@example.com', [TEST_ADDRESS], msg)
        s.quit()
        self.assertIMAPReceived(subject)

    def test_smtp_tls(self):
        """Email sent from an MTA via SMTP+TLS is delivered"""
        import smtplib
        msg, subject = new_message('someone@example.com', TEST_ADDRESS)
        s = smtplib.SMTP(TEST_SERVER, 25)
        s.starttls()
        s.sendmail('someone@example.com', [TEST_ADDRESS], msg)
        s.quit()
        self.assertIMAPReceived(subject)

    def test_smtps_headers(self):
        """Email sent from an MUA has DKIM and TLS headers"""
        import smtplib
        import imaplib

        # Send a message to root
        msg, subject = new_message(TEST_ADDRESS, 'root@sovereign.local')
        s = smtplib.SMTP_SSL(TEST_SERVER, 465)
        s.login(TEST_ADDRESS, TEST_PASSWORD)
        s.sendmail(TEST_ADDRESS, ['root@sovereign.local'], msg)
        s.quit()

        sleep(1)

        # Get the message
        m = imaplib.IMAP4_SSL(TEST_SERVER, 993)
        m.login(TEST_ADDRESS, TEST_PASSWORD)
        m.select()
        _, res = m.search(None, '(SUBJECT \"{}\")'.format(subject))
        _, data = m.fetch(res[0], '(RFC822)')

        self.assertIn(
            'DKIM-Signature: v=1; a=rsa-sha256; c=relaxed/relaxed; d=sovereign.local;',
            data[0][1]
        )

        self.assertIn(
            'ECDHE-RSA-AES256-GCM-SHA384 (256/256 bits)',
            data[0][1]
        )

        # Clean up
        m.store(res[0].strip(), '+FLAGS', '\\Deleted')
        m.expunge()
        m.close()
        m.logout()

    def test_smtp_headers(self):
        """Email sent from an MTA via SMTP+TLS has X-DSPAM and TLS headers"""
        import smtplib
        import imaplib

        # Send a message to root
        msg, subject = new_message('someone@example.com', TEST_ADDRESS)
        s = smtplib.SMTP(TEST_SERVER, 25)
        s.starttls()
        s.sendmail('someone@example.com', [TEST_ADDRESS], msg)
        s.quit()

        sleep(1)

        # Get the message
        m = imaplib.IMAP4_SSL(TEST_SERVER, 993)
        m.login(TEST_ADDRESS, TEST_PASSWORD)
        m.select()
        _, res = m.search(None, '(SUBJECT \"{}\")'.format(subject))
        _, data = m.fetch(res[0], '(RFC822)')

        self.assertIn(
            'X-DSPAM-Result: ',
            data[0][1]
        )

        self.assertIn(
            'ECDHE-RSA-AES256-GCM-SHA384 (256/256 bits)',
            data[0][1]
        )

        # Clean up
        m.store(res[0].strip(), '+FLAGS', '\\Deleted')
        m.expunge()
        m.close()
        m.logout()

    def test_pop3s(self):
        """Connects with POP3S and asserts the existance of an email, then deletes it"""
        import smtplib
        msg, subject = new_message(TEST_ADDRESS, 'root@sovereign.local')
        s = smtplib.SMTP_SSL(TEST_SERVER, 465)
        s.login(TEST_ADDRESS, TEST_PASSWORD)
        s.sendmail(TEST_ADDRESS, ['root@sovereign.local'], msg)
        s.quit()
        self.assertPOP3Received(subject)


class XMPPTests(unittest.TestCase):
    def test_xmpp_c2s(self):
        """Prosody is listening on 5222 for clients and requiring TLS"""

        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        s.connect((TEST_SERVER, 5222))

        # Based off http://wiki.xmpp.org/web/Programming_Jabber_Clients
        s.send("<stream:stream xmlns:stream='http://etherx.jabber.org/streams' "
               "xmlns='jabber:client' to='sovereign.local' version='1.0'>")

        data = s.recv(1024)
        s.close()

        self.assertRegexpMatches(
            data,
            "<starttls xmlns='urn:ietf:params:xml:ns:xmpp-tls'><required/></starttls>"
        )

    def test_xmpp_s2s(self):
        """Prosody is listening on 5269 for servers"""

        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        s.connect((TEST_SERVER, 5269))

        # Base off http://xmpp.org/extensions/xep-0114.html
        s.send("<stream:stream xmlns:stream='http://etherx.jabber.org/streams' "
               "xmlns='jabber:component:accept' to='sovereign.local'>")

        data = s.recv(1024)
        s.close()

        self.assertRegexpMatches(
            data,
            "from='sovereign.local'"
        )
