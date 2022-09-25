<?php
namespace MF\Model;
use App\Connection;
// retorna o modelo solicitado já instaciado, inclusive com a conexão estabelecida
class Container{
    public static function getModel($model){
        $class ="\\App\\Models\\".ucfirst($model);
        $conn = Connection::getDb();
        return new $class($conn);
    }
}
?>