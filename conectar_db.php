    <?php 
<<<<<<< HEAD
    // establish conection to the database
=======
    //As with all web applications, we start with the database; first we create it and then we connect it with PHP.
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
    define ("HOSTNAME", "localhost");  
    define ("USER_DB","root"); 
    define ("PASSWORD",""); 
    define ("DATABASE", "reservas_hotel");

    function conectar(){
<<<<<<< HEAD
        $dsn= "mysql:host=".HOSTNAME.";dbname=".DATABASE.";charset=utf8mb4";
// use try-catch for error handling
        try{
            $pdo = new PDO($dsn, USER_DB, PASSWORD);
            //configuration of atributtes for greater security
=======
        $dsn= "mysql:host=".HOSTNAME.";dbname=".DATABASE;
//Use of try-catch for error handling
        try{
            $pdo = new PDO($dsn, USER_DB, PASSWORD);
            //We configured the attributes for greater security.
>>>>>>> 2b73148 (Proyecto actualizado: archivos PHP incluidos, carpeta vendor, datos sensibles y base de datos ignorada)
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
}
}