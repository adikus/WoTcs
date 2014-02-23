WoTcs
=====

PHP site for representing World of Tanks clan and player stats

Installation/Configuration
--------------------------
1. Rename `config/database` to `config/database.php` and fill in your database login details into the `$config` variable on line 9
2. Import the databse from `db/wotstats.sql`

CRON settings
-------------
These scripts should be set to run using CRON:

1. `/player_stats_cron.php` at least once a day - sets/updates bounds for colored labels
2. `/api_update_cron.php` at least once a day - gets api statistics (if you don't need this just run this script at least once)
3. `/top_cron.php?r=x` at least once a day - creates list of top 100 clans (replace x with with region id, if you want to create top 100 list for multiple clans, call this script multiple times with different parameter each time)

### Regions
0 - RU
1 - EU
2 - NA
3 - SEA
5 - KR
