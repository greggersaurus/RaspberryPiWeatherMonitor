WordpressWeatherMonitor
=========================

Introduction
--------------
This is a project to turn a Raspberry Pi equipped with a Sense HAT into a 
 weather monitoring system which tracks temperature, humidity and pressure 
 over time. This data is stored in a MySQL database that is then accessed 
 via a WordPress or the Sense HAT 8x8 LED matrix for display. 
This project is heavily influenced by the Raspberry-Weather-DS18B20 project
 (see http://www.raspberryweather.com/, 
 https://github.com/peterkodermac/Raspberry-Weather-DS18B20 and
 https://github.com/peterkodermac/Raspberry-Weather-WordPress for details). 
 The changes are significant enough that I did not feel it necessary to fork 
 that repo, but still wanted to give credit to what was essentially the guide I 
 followed for this project.

Statistics Gathering
----------------------
All code in the 'gather' directory relates to capturing the weather related
 statistics. The idea is that the python script getWeather.py will be run as a 
 cron job and add entries to a MySQL table at a regular interval. The MySQL 
 table should be added to a WordPress database to make it easier for wordpress
 to access and display the data (See creatTable.sql for table creation source). 

The script should be installed in /usr/bin/getWeather.py

The crontab entry for adding gathering new statistics every minute is:
> TZ=EST 

> \* * * * * /usr/bin/getWeather.py

Data Display
--------------
All code in the display directory relates displaying the data stored in the
 MySQL database. 

### WordPress
A plugin has been created that uses the WordPress Charts plugin 
 (https://wordpress.org/plugins/wp-charts/) to allow for displaying the 
 weather data in the SQL database with fancy charts. Not sure if sharing
 plugins functions like this is the right way of doing things in the WordPress 
 world, but it makes for a simply and clean plugin.

### Sense HAT
The Sense HAT Python library 
 (https://www.raspberrypi.org/documentation/hardware/sense-hat/) is used to 
 display scrolling text that tells the weather stastics stored in the SQL
 database. The joystick is used cycle through different display options (i.e.
 turn off display, print everything, print temperature only, etc.).
