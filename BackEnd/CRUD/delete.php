<?PHP 
    require('db.php');
    $id = $_GET['id'];
    $query = 'DELETE FROM users WHERE id = :id';
    $res = $conn->prepare($query);
    $res->execute(['id'=>$id]);
    header('Location: index.php');

?>