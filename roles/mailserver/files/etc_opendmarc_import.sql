-- OpenDMARC database schema
--
-- Copyright (c) 2012, The Trusted Domain Project.
--      All rights reserved.

USE opendmarc;

-- A table for mapping domain names and their DMARC policies to IDs
CREATE TABLE IF NOT EXISTS domains (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        firstseen TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

        PRIMARY KEY(id),
        UNIQUE KEY(name)
);

-- A table for logging reporting requests
CREATE TABLE IF NOT EXISTS requests (
        id INT NOT NULL AUTO_INCREMENT,
        domain INT NOT NULL,
        repuri VARCHAR(255) NOT NULL,
        adkim TINYINT NOT NULL,
        aspf TINYINT NOT NULL,
        policy TINYINT NOT NULL,
        spolicy TINYINT NOT NULL,
        pct TINYINT NOT NULL,
        locked TINYINT NOT NULL,
        firstseen TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        lastsent TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',

        PRIMARY KEY(id),
        KEY(lastsent),
        UNIQUE KEY(domain)
);

-- A table for reporting hosts
CREATE TABLE IF NOT EXISTS reporters (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        firstseen TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

        PRIMARY KEY(id),
        UNIQUE KEY(name)
);

-- A table for IP addresses
CREATE TABLE IF NOT EXISTS ipaddr (
	id INT NOT NULL AUTO_INCREMENT,
	addr VARCHAR(64) NOT NULL,
	firstseen TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

	PRIMARY KEY(id),
	UNIQUE KEY(addr)
);

-- A table for messages
CREATE TABLE IF NOT EXISTS messages (
        id INT NOT NULL AUTO_INCREMENT,
        date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        jobid VARCHAR(128) NOT NULL,
        reporter INT UNSIGNED NOT NULL,
        policy TINYINT UNSIGNED NOT NULL,
        disp TINYINT UNSIGNED NOT NULL,
        ip INT UNSIGNED NOT NULL,
        env_domain INT UNSIGNED NOT NULL,
        from_domain INT UNSIGNED NOT NULL,
        policy_domain INT UNSIGNED NOT NULL,
        spf TINYINT UNSIGNED NOT NULL,
        align_dkim TINYINT UNSIGNED NOT NULL,
        align_spf TINYINT UNSIGNED NOT NULL,
        sigcount TINYINT UNSIGNED NOT NULL,

        PRIMARY KEY(id),
        KEY(date),
        UNIQUE KEY(reporter, date, jobid)
);

-- A table for signatures
CREATE TABLE IF NOT EXISTS signatures (
        id INT NOT NULL AUTO_INCREMENT,
        message INT NOT NULL,
        domain INT NOT NULL,
        pass TINYINT NOT NULL,
        error TINYINT NOT NULL,

        PRIMARY KEY(id),
        KEY(message)
);
