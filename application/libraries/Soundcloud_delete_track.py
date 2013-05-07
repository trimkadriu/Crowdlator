#!/Python27/python.exe
#print "Content-type:text/html\r\n\r\n"

import sys
import soundcloud

argList = sys.argv

id = argList[1]
secret = argList[2]
user = argList[3]
passw = argList[4]
track_id = argList[5]

try:
	client = soundcloud.Client(
		client_id = id,
		client_secret = secret,
		username = user,
		password = passw
	)
	client.delete('/tracks/' + track_id)
	print 'ok'
except:
	print 'bad'