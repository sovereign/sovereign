# Variables

By default your variables are stored in `user.yml` and `private.yml`.
Reference defaults are in `defaults.yml`, but you don't want to change 
that file. 

The original repo held all of the variables in `user.yml`, but Ansible
provides a secure vault that encrypts information that you want to
keep private. Passwords fall into this category.

You can read about Ansible Vault [here](http://docs.ansible.com/ansible/playbooks_vault.html).

## How to use

The default password for the Vault is 'sovereign' - please change it:

```
$ ansible-vault rekey private.yml
Vault password: 
New Vault password: 
Confirm New Vault password: 
Rekey successful
```

To access the vault:

```
$ ansible-vault edit private.yml
```

It will ask you for the password, after which it will open the file in your
configured editor. Make your changes, save and exit.

When running playbooks, it will, by default, ask you for your vault password. If you
get tired of this, you can store the password in a file and reference that at runtime.
For example, if you put the password in `~/.vault_pass.txt`, you can fire off Ansible with:

```
$ ansible-playbook --vault-password-file ~/.vault_pass.txt site.yml
```

You can also set the location in the environment variable 
`ANSIBLE_VAULT_PASSWORD_FILE`, and Ansible will look there. 

## How to remove this feature

If you prefer _not_ to have your passwords secured, you can follow these steps:

1. Copy the contents of `private.yml` to the corresponding sections (or make new sections)
in `user.yml`
2. In `site.yml`, remove the line under `vars_files` that references `vars/private.yml`
3. In `ansible.cfg` remove or comment out `ask_vault_pass`

