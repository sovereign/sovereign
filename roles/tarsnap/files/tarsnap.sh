#!/bin/sh

# Tarsnap backup script
# Written by Tim Bishop, 2009.

# Directories to backup (relative to /)
DIRS="home root decrypted var/www etc/letsencrypt"

# Number of daily backups to keep
DAILY=7

# Number of weekly backups to keep
WEEKLY=3
# Which day to do weekly backups on
# 1-7, Monday = 1
WEEKLY_DAY=5

# Number of monthly backups to keep
MONTHLY=1
# Which day to do monthly backups on
# 01-31 (leading 0 is important)
MONTHLY_DAY=01

# Path to tarsnap
TARSNAP="/usr/local/bin/tarsnap"

# Extra flags to pass to tarsnap
EXTRA_FLAGS="-L -C /"

# end of config

set -e

# day of week: 1-7, monday = 1
DOW=`date +%u`
# day of month: 01-31
DOM=`date +%d`
# month of year: 01-12
MOY=`date +%m`
# year
YEAR=`date +%Y`
# time
TIME=`date +%H%M%S`

# Backup name
if [ X"$DOM" = X"$MONTHLY_DAY" ]; then
	# monthly backup
	BACKUP="$YEAR$MOY$DOM-$TIME-monthly"
elif [ X"$DOW" = X"$WEEKLY_DAY" ]; then
	# weekly backup
	BACKUP="$YEAR$MOY$DOM-$TIME-weekly"
else
	# daily backup
	BACKUP="$YEAR$MOY$DOM-$TIME-daily"
fi

# Below command complains to stderr if postgres user cannot write to CWD
cd /home/

# Dump PostgreSQL to file
umask 077
sudo -u postgres pg_dumpall -c | gzip > /decrypted/postgresql-backup.sql.gz

# Do backups
for dir in $DIRS; do
	echo "==> create $BACKUP-$dir"
	$TARSNAP $EXTRA_FLAGS -c -f $BACKUP-$dir $dir
done

# Backups done, time for cleaning up old archives

# using tail to find archives to delete, but its
# +n syntax is out by one from what we want to do
# (also +0 == +1, so we're safe :-)
DAILY=`expr $DAILY + 1`
WEEKLY=`expr $WEEKLY + 1`
MONTHLY=`expr $MONTHLY + 1`

# Do deletes
TMPFILE=/tmp/tarsnap.archives.$$
$TARSNAP --list-archives > $TMPFILE
for dir in $DIRS; do
	for i in `grep -E "^[[:digit:]]{8}-[[:digit:]]{6}-daily-$dir" $TMPFILE | sort -rn | tail -n +$DAILY`; do
		echo "==> delete $i"
		$TARSNAP -d -f $i
	done
	for i in `grep -E "^[[:digit:]]{8}-[[:digit:]]{6}-weekly-$dir" $TMPFILE | sort -rn | tail -n +$WEEKLY`; do
		echo "==> delete $i"
		$TARSNAP -d -f $i
	done
	for i in `grep -E "^[[:digit:]]{8}-[[:digit:]]{6}-monthly-$dir" $TMPFILE | sort -rn | tail -n +$MONTHLY`; do
		echo "==> delete $i"
		$TARSNAP -d -f $i
	done
done
rm $TMPFILE
