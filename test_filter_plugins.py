import unittest
import os.path
import sys

# Make filter_plugins available for import
sys.path.append(os.path.join(os.path.dirname(__file__), 'filter_plugins'))

from password_hash import doveadm_pw_hash, znc_pw_hash, znc_pw_salt


class PasswordHashPluginTest(unittest.TestCase):
    def test_doveadm_pw_hash(self):
        """doveadm_pw_hash returns a SHA512 hash"""
        hashed_password = doveadm_pw_hash('test-password')

        assert hashed_password != None
        self.assertEqual(type(hashed_password), str)
        # Hash expected to be SHA512 (starting with $6$)
        self.assertEqual(hashed_password[:3], '$6$')
        self.assertEqual(len(hashed_password), 106)

    def test_znc_pw_hash(self):
        """znc_pw_hash returns only the hashed password component"""
        hashed_password = znc_pw_hash('test-password')

        assert hashed_password != None
        self.assertEqual(type(hashed_password), str)
        self.assertFalse('$' in hashed_password)
        self.assertEqual(len(hashed_password), 43)

    def test_znc_pw_salt(self):
        """znc_pw_salt retruns only the salt of the hash"""
        salt = znc_pw_salt('test-password')

        assert salt != None
        self.assertEqual(type(salt), str)
        self.assertFalse('$' in salt)
        self.assertEqual(len(salt), 16)
