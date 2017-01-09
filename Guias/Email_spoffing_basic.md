# Basic Email spoofing

 Herramientas:
 - Linux (en mi caso kali)
 - Postfix
 - Mailutils
 - git
 - ingenieria social

Mail spoofing es la creación de mensajes de correo electrónico con una dirección de remitente falso. Es fácil de hacer porque los protocolos básicos no tienen ningún mecanismo de autenticación. Se puede llevar a cabo desde dentro de una LAN o desde un entorno externo utilizando troyanos.

## Paso 1

instalar postfix, mailutils, git

## Paso 2

Abrir una terminal y poner:

    $ git clone https://github.com/galkan/sees

este repositorio contiene lo necesario para la creacion de un mail falso, ya sea, data, subjects metadata etc.

## Paso 3

Una vez clonado el repositorio , procedemos formar nuestro correo. Estando en la carpeta del repositorio clonado, vamos a la carpeta *config* y editamos el archivo mail.user (los campos estan separados por ':'. El primero corresponde al correo origen, el segundo y el tercero son metadatos y elcuarto campo es el correo destino)

ej: gmail@admin.com:Administracion de GMAIL:Inicio de sesion detectado: nicolas.valenzum@gmail.com

guardamos y cerramos. Luego abrimos el archivo sees.cfg del mismo directorio y agregamos nuestro dominio, en nuestro caso como puse mas arriba **admin.com**

## Paso 4

Estando en la carpeta de nuestro repo, nos dirigimos al directorio DATA, en el se guardara la informacion o cuerpo de correo que enviaremos. Tenemos varias opciones, podemos escribir el correo en HTML, texto plano, adjuntar archivos etc. por lo que sera de vuestro gusto ver como armar su correo.

## Paso 5

Habiendo armado ya nuestro correo, debemos levantar el servicio de postfix, para ello:

    $ /etc/init.d/postfix start     # equivalete es un systemctl start postfix

Ademas debemos asegurarnos que el puerto 25 (SMTP) de nuestra maquina este abierto y que no exista algun firewall externo que pueda estar bloqueando la conexion por SMTP (en mi caos tuve que abrir el puerto 25 del router).

Luego procedemos a enviarlo utilizando el siguiente comando (debemos estar en la raiz de nuestro repositorio)
    $ ./sees.py --text --config_file config/sees.cfg --mail_user config/mail.user --html_file data/html.text -v

Con esto nos aparecera que el correo se esta enviando. En caso de que haya algun error con SMTP, el script nos avisara.

Con esto ya hemos enviado nuestro correo falso, aunque generalmente llegan a la carpeta de SPAM de nuestra bandeja.
