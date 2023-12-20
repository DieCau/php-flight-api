<?php

// Cargamos el módulo 'Flight PHP'
require 'flight/Flight.php';

// Conectar a la BD (host={nombre del host:puerto}, dbname={nombre de BD}, 'nombreUsuario', 'password')
Flight::register('db', 'PDO', array('mysql:host=localhost:3307;dbname=api','root','123456'));

// Hacer una solicitud con la ruta '/saludar' y cargar una función
// *** GET ***
Flight::route('GET /alumnos', function () {
    $sentencia= Flight::db()->prepare("SELECT * FROM `tbl_alumnos`");
    $sentencia->execute();
    $datos=$sentencia->fetchAll();
    Flight::json($datos);
});

// Consulta de UN registro
// *** GET ***
Flight::route('GET /alumnos/@id', function ($id) {
    
    $sentencia= Flight::db()->prepare("SELECT * FROM `tbl_alumnos` WHERE id=?");
    $sentencia->bindParam(1,$id);    
    
    $sentencia->execute();
    $datos=$sentencia->fetchAll();
    
    Flight::json($datos);
});

// Crear un alumno
// *** POST ***
Flight::route('POST /alumnos', function () {
    $nombres=(Flight::request()->data->nombres);
    $apellidos=(Flight::request()->data->apellidos);
    
    $sql="INSERT INTO tbl_alumnos (nombres, apellidos) VALUES (?,?)";
    $sentencia= Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$nombres);
    $sentencia->bindParam(2,$apellidos);
    $sentencia->execute();
    
    Flight::jsonp(["Alumno agregado"]);
});

// Actualizar un alumno
// *** PUT ***
Flight::route('PUT /alumnos', function () {
    
    $id=(Flight::request()->data->id);    
    $nombres=(Flight::request()->data->nombres);
    $apellidos=(Flight::request()->data->apellidos);
    
    $sql="UPDATE tbl_alumnos SET nombres=?, apellidos=? WHERE id=?";
    $sentencia= Flight::db()->prepare($sql);

    $sentencia->bindParam(1,$nombres);
    $sentencia->bindParam(2,$apellidos);
    $sentencia->bindParam(3,$id);
    
    $sentencia->execute();    
    Flight::jsonp(["Alumno actualizado"]);    
});

// Eliminar un alumno
// *** DELETE ***
Flight::route('DELETE /alumnos', function () {
    $id=(Flight::request()->data->id);
    
    $sql="DELETE FROM tbl_alumnos WHERE id=?";
    $sentencia= Flight::db()->prepare($sql);
    $sentencia->bindParam(1,$id);
    $sentencia->execute();
    
    Flight::jsonp(["Alumno borrado"]);
});


Flight::start();
