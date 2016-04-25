WordpressWeatherMonitor
=========================

Introduction
--------------
This is a project to turn a Raspberry Pi equipped with a Sense HAT into a 
 weather monitoring system which tracks temperature, humidity and pressure 
 over time. This data is stored in a MySQL database that is then accessed 
 via a WordPress for display. 
This project is heavily influenced by the Raspberry-Weather-DS18B20 project
 (see https://github.com/peterkodermac/Raspberry-Weather-DS18B20 and/or 
 http://www.raspberryweather.com/ for details). The changes are significant
 enough that I did not feel it necessary to fork that repo, but still wanted to
 pay mention to essentially the guide I followed for this project.

Statistics Gathering
----------------------
All code in the 'gather' directory relates to capturing the weather related
 statistics. The idea is that the python script getWeather.py will be run as a 
 cron job and add entries to a MySQL table at a regular interval. The MySQL 
 table should be added to a WordPress database (See creatTable.sql for table 
 creation source). 

The script should be installed in /usr/bin/getWeather.py

The crontab entry for adding gathering new statistics every minute is:
> TZ=EST 

> \* * * * * /usr/bin/getWeather.py

Data Display
--------------
All code in the 'display' directory relates displaying the data stored in the
 MySQL database. This is primarily versioning for the WordPress plugin used
 to read the database and graph the resulting data.

TODO
------
- See getWeather.py TODOs
- Look into database size and managing for long term (i.e. circular table so we never run out of ids? or at least just cleaning old entries periodically?)
- setup redmine for this project and move TODOs to issues?
- order extension cable to move Sense HAT farther from CPU for better temp readings
