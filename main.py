from bmp180 import bmp180
import pymysql.cursors # type: ignore
import requests # type: ignore

class mySensor:
    def __init__(self):
        self.sensor = bmp180(0x77)
        self.connection = pymysql.connect(
            host="127.0.0.1",
            user="marcos",
            password="Tucm+1985",
            db="mySensors"
        )

    def sensorInformation(self):
        data = (
            self.sensor.get_temp(), 
            self.sensor.get_altitude()
        )
        cursor = self.connection.cursor(pymysql.cursors.DictCursor)
        cursor.execute("INSERT INTO temperature (value1, value2) VALUES (%s, %s)", data)
        self.connection.commit()
        return data


# main code start here!

setpoint = 35
sensor = mySensor()


url = "https://io.adafruit.com/api/v2/webhooks/feed/wCTd1eGUXzsVyVUT99TCRhh3qBpZ/raw"

headers = {
    'Content-Type': 'application/octet-stream'
}

temperature = sensor.sensorInformation()[0]
print("Current Temperature: {0:.2f}".format(temperature))

if temperature > setpoint:
    requests.request('POST', url, headers=headers, data=str(temperature))