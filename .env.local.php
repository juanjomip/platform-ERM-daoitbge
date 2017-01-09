<?php
  
/**
 * Array con variables de configuración de laravel y variables de autenticación.
 * @author David Miranda A. <dm@beenary.cl>
 */
  
return array(
        // *** Configuración general - /app/config/app.php ***
        // Debug mode, muestra errores y su traza cuando es true.
        'debug' => true,

        'url_integracion_transvip' => 'http://wspublic.transvip.cl/Tadesa',
  
        // Zona horaria.
        'timezone' => 'America/Santiago',
  
        // Idioma.
        'locale' => 'es',
  
        // Llave de encriptación propia de la aplicación de 32 caracteres para encriptar la información.
        // Recomendación: utilizar letras (minúsculas y mayúsculas sin repetir), números (sin repetir) y símbolos (sin repetir).
        // Utilizar el patrón 'letra número símbolo' y repetir.
        'key' => '',
  
        // *** Autenticación - /app/config/auth.php ***
        // Modelo que se utiliza con Authentication.
        'model' => 'User',
  
        // *** Database - /app/config/database.php ***
        // Conexión de base de datos por defecto.
        'default' => 'mysql',
  
        'connections' => array(
            'mysql' => array(
                'read' => array(
                    'host' => 'localhost',
                ),
                'write' => array(
                    'host' => 'localhost',
                ),
                'driver'       => 'mysql',
                'host'         => 'localhost',
                'database'     => 'tesis',
                'username'     => 'root',
                'password'     => 'r',
                'charset'      => 'utf8',
                'collation'    => 'utf8_unicode_ci',
                'prefix'       => '',
            ),
  
            'mongodb' => array(
                'driver'    => 'mongodb',
                'host'      => '52.67.161.77',
                'port'      => '45195',
                'database'  => 'taxiapp',
                'username'  => 'admin',
                'password'  => '',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ),

        ),
  
);
