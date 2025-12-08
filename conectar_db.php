    <?php 
    // establish conection to the database
    define ("HOSTNAME", "localhost");  
    define ("USER_DB","root"); 
    define ("PASSWORD",""); 
    define ("DATABASE", "reservas_hotel");

    function conectar(){
        $dsn= "mysql:host=".HOSTNAME.";dbname=".DATABASE.";charset=utf8mb4";
// use try-catch for error handling
        try{
            $pdo = new PDO($dsn, USER_DB, PASSWORD);
            //configuration of atributtes for greater security
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            die("Error en la conexión: " . $e->getMessage());
}
}