#!/usr/bin/python



#  Codigo desarrollado por Samuel Esteban
#  https://www.facebook.com/samutix

import requests
from termcolor import colored

cont = 0 
for n in range(1,1001):
	cont +=1
	r = requests.get("http://192.168.0.22/test/6/media/uploading/images/"+str(n)+".php")
	if r.status_code == 200:
		print colored("%d- Recurso http://192.168.0.22/test/6/media/uploading/images/%s.php Encontrado :D" %(cont,str(n)),"green")
	if cont in range(100,1001,100):
		print cont




