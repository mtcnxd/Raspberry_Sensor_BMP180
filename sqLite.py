import time
import sqlite3
from bmp180 import bmp180

sensor = bmp180(0x77)
con = sqlite3.connect("temperature.db")

while True:
	temp = sensor.get_temp()
	cur = con.cursor()
	query = "INSERT INTO Temperature (value) values ('" + str(temp) + "')"
	cur.execute(query)
	con.commit()
	print("value: " +  str(temp))
	time.sleep(10)
