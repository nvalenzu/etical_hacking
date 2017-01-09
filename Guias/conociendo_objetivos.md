# Conociendo la victima

## FootPrinting

Consiste en la practica de recoleccion de informacion de los sistemas computacionales utilizando varrias herramientas de las fuentes de internet. Esta informacion siempre es util para el hacker quien esta tratando de atacar algun sistema.

Herramientas:
- Netcraft https://www.netcraft.com  
- Whois // por consola o por web


## Fingerprinting

Consiste en la recoleccion de informacion directamente del sistema de una organizacion

herramientas:
- nmap
- modulos de mestasploit

#### Modulo de ejemplo (busqueda de correos)

    $ msfconsole
    msf > use auxiliary/gather/Search_email_collector
    msf auxiliary(Search_email_collector) > show options

aqui nos dara las opciones para ejecutar este modulo auxiliary

    msf auxiliary(Search_email_collector) > set DOMAIN <DOMAIN>
    msf auxiliary(Search_email_collector) > exploit

aqui ya comienza a buscar los correos de la compañia o dominio y nos arrojara una lista de correos con ese dominio.

## Otras utilidades

- https://redbot.org/  # para visualizar la version de php de una pagina, servicio web que usa, cookies etc.
