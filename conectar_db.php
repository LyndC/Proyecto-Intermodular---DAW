    <?php 
    // como en todas las aplicaciones web, se empieza por la DB, primero la creamos y luego conectamos con php
    define ("HOSTNAME", "localhost");  
    define ("USER_DB","root"); 
    define ("PASSWORD",""); 
    define ("DATABASE", "reservas_hotel");

    function conectar(){
        $dsn= "mysql:host=".HOSTNAME.";dbname=".DATABASE;
// uso de try-catch para manejo de errores
        try{
            $pdo = new PDO($dsn, USER_DB, PASSWORD);
            //configuramos los atributos para mayor seguridad
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
}
}