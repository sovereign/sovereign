# Design Description for Radicale Role

The Radicale Project is a complete CalDAV (calendar) and CardDAV (contact) server solution.

Calendars and address books are available for both local and remote access,
possibly limited through authentication policies. They can be viewed and edited
by calendar and contact clients on mobile phones or computers.

## Radicale Role implementation

Radicale and its dependencies are installed from the standard apt repositories.
Postgres user and database are created at install-time by the radicale task.

The server is running on dav.DOMAIN:5232.

### /etc/radicale/config

All other configuration is done via the /etc/radicale/config file in the config.j2 template.
Relevant configuration options:

* Domain and port via hosts = {{ radicale_domain }}:{{ radicale_port }} (line 17)
* SSL certificate path (line 24, might have to be changed for letsencrypt)
* Authentication method htpasswd (line 46)
* htpasswd file location is /etc/radicale/users (line 54)
* htpasswd encryption is sha1 (line 57), bcrypt was tested and did not work
* database configuration (line 118)

### default vars in defaults.yml

radicale_port: "5232"
radicale_db_username: radicale
radicale_db_database: radicale

### user vars in user.yml

radicale_db_password: TODO
radicale_accounts:
  - name: "{{ main_user_name }}"
    password: TODO

## Device Setup
According to the radicale project website, it has been tested with these clients:

* Mozilla Lightning
* GNOME Evolution
* KDE KOrganizer
* aCal, ContactSync, CalendarSync, CalDAV-Sync CardDAV-Sync and DAVdroid for Google Android
* InfCloud, CalDavZAP, CardDavMATE
* Apple iPhone
* Apple Calendar
* Apple Contacts
* syncEvolution

The radicale user documentation provides [detailed instructions](http://radicale.org/user_documentation/#idstarting-the-client)
on client setup.


## Links
http://radicale.org/
