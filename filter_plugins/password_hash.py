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
    return passlib.hash.sha512_crypt.encrypt(password, rounds=5000)


def znc_pw_salt(password):
    return doveadm_pw_hash(password).split("$")[0]


def znc_pw_hash(password):
    return doveadm_pw_hash(password).split("$")[1]


class FilterModule(object):

    def filters(self):
        return {
            'doveadm_pw_hash': doveadm_pw_hash,
            'znc_pw_salt': znc_pw_salt,
            'znc_pw_hash': znc_pw_hash,
        }
