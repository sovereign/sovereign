from ansible.errors import AnsibleError, AnsibleUndefinedVariable
from jinja2 import StrictUndefined
__metaclass__ = type


try:
    import passlib.hash
    HAS_LIB = True
except ImportError:
    HAS_LIB = False


def check_lib():
    if not HAS_LIB:
        raise AnsibleError('You need to install "passlib" prior to running '
                           'password_hash-based filters')


def doveadm_pw_hash(password):
    check_lib()
    if type(password) is StrictUndefined:
        raise AnsibleUndefinedVariable('Please pass a string into this password_hash-based filter')
    # We use the implicit 5000 rounds as per spec
    return passlib.hash.sha512_crypt.encrypt(password, rounds=5000)

def sha256_hash(password):
    check_lib()
    if type(password) is StrictUndefined:
        raise AnsibleUndefinedVariable('Please pass a string into this password_hash-based filter')
    # We use the implicit 5000 rounds as per spec
    return passlib.hash.sha256_crypt.encrypt(password, rounds=5000)

def znc_pw_salt(password):
    # Hash has looks like $5$salt$hash
    return sha256_hash(password).split("$")[2]


def znc_pw_hash(password):
    # Hash has looks like $5$salt$hash
    return sha256_hash(password).split("$")[3]


class FilterModule(object):

    def filters(self):
        return {
            'doveadm_pw_hash': doveadm_pw_hash,
            'znc_pw_salt': znc_pw_salt,
            'znc_pw_hash': znc_pw_hash,
        }
