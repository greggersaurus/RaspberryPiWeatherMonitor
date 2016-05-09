#!/usr/bin/python

# This script pulls Raspberry Pi Sense Hat weather related statistics and
#  adds them to a mysql table titled "weather" in a wordpress database.
# This is based off of getInfo.py from the Raspberry Weather project. See 
#  http://www.raspberryweather.com/ and 
#  https://github.com/peterkodermac/Raspberry-Weather-DS18B20 for additional
#  details.

import logging
import sys
import mysql.connector
import datetime
from sense_hat import SenseHat

# Location where execution log is stored
log_filename='/var/log/getWeather.log'

## Error Handling Function. 
#  
#  Record error in log and print to standard out.
#  @err_str String describing the error.
def handle_error( err_str ):
	# Add timestamp to error message
	err_str = datetime.datetime.now().isoformat() + ": " + err_str
	# Add error to log
	logging.error(err_str)
	# Print to stanard out
	print(err_str)

## Entry point.
try:
	# Setup log
	logging.basicConfig(filename=log_filename, 
		level=logging.ERROR)

	# Access to sense hat library
	sense = SenseHat()

	# Pull all sensor readings
	temperature = (sense.get_temperature() * 9/5) + 32
	temperature_pressure = (sense.get_temperature_from_pressure() * 9/5)+32
	humidity = sense.get_humidity()
	pressure = sense.get_pressure()

	# Get the current date and time
	current_datetime = datetime.datetime.now()

	# Attempt to connect to the mysql database
	cnx = mysql.connector.connect(user='root', password='ENTER_PASSWORD', 
		host='127.0.0.1', database='wordpress')

	cursor = cnx.cursor()

	# Add measurements to the table
	cursor.execute("INSERT INTO weather (temperature, temperature_pressure,"
		" humidity, pressure, year, month, day, hour, minute, second) "
		"VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
		(temperature,temperature_pressure, humidity,pressure,
		current_datetime.year, current_datetime.month, 
		current_datetime.day, current_datetime.hour,
		current_datetime.minute, current_datetime.second))

	cnx.commit()

	cursor.close()
	cnx.close()

except mysql.connector.Error as err:
	handle_error("Something went wrong: {}".format(err))
	
except:
	err_str = "Unexpected error:", sys.exc_info()[0]
	handle_error(err_str)
 
