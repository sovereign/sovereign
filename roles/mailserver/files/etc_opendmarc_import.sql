# Source: http://www.trusteddomain.org/pipermail/opendmarc-users/2015-February/000447.html

START TRANSACTION;

SET standard_conforming_strings=off;
SET escape_string_warning=off;
SET CONSTRAINTS ALL DEFERRED;

CREATE TABLE "domains" (
  "id" integer NOT NULL,
  "name" varchar(510) NOT NULL,
  "firstseen" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ("id"),
  UNIQUE ("name")
);

CREATE TABLE "ipaddr" (
  "id" integer NOT NULL,
  "addr" varchar(128) NOT NULL,
  "firstseen" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ("id"),
  UNIQUE ("addr")
);

CREATE TABLE "messages" (
  "id" integer NOT NULL,
  "date" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "jobid" varchar(256) NOT NULL,
  "reporter" integer  NOT NULL,
  "policy" tinyint(3)  NOT NULL,
  "disp" tinyint(3)  NOT NULL,
  "ip" integer  NOT NULL,
  "env_domain" integer  NOT NULL,
  "from_domain" integer  NOT NULL,
  "policy_domain" integer  NOT NULL,
  "spf" tinyint(3)  NOT NULL,
  "align_dkim" tinyint(3)  NOT NULL,
  "align_spf" tinyint(3)  NOT NULL,
  "sigcount" tinyint(3)  NOT NULL,
  PRIMARY KEY ("id"),
  UNIQUE ("reporter", "date", "jobid")
);

CREATE TABLE "reporters" (
  "id" integer NOT NULL,
  "name" varchar(510) NOT NULL,
  "firstseen" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ("id"),
  UNIQUE ("name")
);

CREATE TABLE "requests" (
  "id" integer NOT NULL,
  "domain" integer NOT NULL,
  "repuri" varchar(510) NOT NULL,
  "adkim" tinyint(4) NOT NULL,
  "aspf" tinyint(4) NOT NULL,
  "policy" tinyint(4) NOT NULL,
  "spolicy" tinyint(4) NOT NULL,
  "pct" tinyint(4) NOT NULL,
  "locked" tinyint(4) NOT NULL,
  "firstseen" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "lastsent" timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY ("id"),
  UNIQUE ("domain")
);

CREATE TABLE "signatures" (
  "id" integer NOT NULL,
  "message" integer NOT NULL,
  "domain" integer NOT NULL,
  "pass" tinyint(4) NOT NULL,
  "error" tinyint(4) NOT NULL,
  PRIMARY KEY ("id")
);

COMMIT;

-- Sequences --
START TRANSACTION;

CREATE SEQUENCE domains_id_seq;
SELECT setval('domains_id_seq', max(id)) FROM domains;
ALTER TABLE "domains" ALTER COLUMN "id" SET DEFAULT nextval('domains_id_seq');

CREATE SEQUENCE ipaddr_id_seq;
SELECT setval('ipaddr_id_seq', max(id)) FROM ipaddr;
ALTER TABLE "ipaddr" ALTER COLUMN "id" SET DEFAULT nextval('ipaddr_id_seq');

CREATE SEQUENCE messages_id_seq;
SELECT setval('messages_id_seq', max(id)) FROM messages;
ALTER TABLE "messages" ALTER COLUMN "id" SET DEFAULT nextval('messages_id_seq');

CREATE SEQUENCE reporters_id_seq;
SELECT setval('reporters_id_seq', max(id)) FROM reporters;
ALTER TABLE "reporters" ALTER COLUMN "id" SET DEFAULT nextval('reporters_id_seq');

CREATE SEQUENCE requests_id_seq;
SELECT setval('requests_id_seq', max(id)) FROM requests;
ALTER TABLE "requests" ALTER COLUMN "id" SET DEFAULT nextval('requests_id_seq');

CREATE SEQUENCE signatures_id_seq;
SELECT setval('signatures_id_seq', max(id)) FROM signatures;
ALTER TABLE "signatures" ALTER COLUMN "id" SET DEFAULT nextval('signatures_id_seq');

COMMIT;
