#!/usr/bin/python

# This script pulls Raspberry Pi Sense Hat weather related statistics and
#  adds them to a mysql table titled "weather" in a wordpress database.
# This is based off of getInfo.py from the Raspberry Weather project. See 
#  http://www.raspberryweather.com/ and 
#  https://github.com/peterkodermac/Raspberry-Weather-DS18B20 for additional
#  details.

import sys
import mysql.connector
import datetime
from sense_hat import SenseHat

try:
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
	#TODO: check connection, print error and email admin on problem

	#TODO: enabling this generates exception.NameErrors
##	cnx.raise_on_warnings = true

	cursor = cnx.cursor()

	cursor.execute("INSERT INTO weather (temperature, temperature_pressure,"
		" humidity, pressure, datetime) VALUES (%s,%s,%s,%s,%s)",
		(temperature,temperature_pressure,humidity,pressure,
		current_datetime))
	#TODO: check that this did not fail, what about when we run out of id space?
	#TODO: manage size of table with paritions???

	cnx.commit()
	#TODO: can this fail?

	cursor.close()
	cnx.close()
except:
	## TODO: Get more info out
	## TODO: Email error to admin
	print "Unexpected error:", sys.exc_info()[0]
 
