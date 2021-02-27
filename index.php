<?php

require "exceptions/DatabaseConnectionException.php";
require "lib/functions.php";
require "lib/database.php";
require "lib/auth.php";

$config = include "config.php";

try {
    $database = new Database(
        "mysql:host=".$config['DB_HOST'].";dbname={$config['DB_NAME']}",
        $config['DB_USER'],
        $config['DB_PASSWORD']
    );
} catch (DatabaseConnectionException $e) {
    die("<h1>Veritabanı Bağlantı Hatası!</h1>");
}

$auth = new Auth($database);

if (!$auth->check()) redirect("login.php");

?>
<!doctype html>
<html>
<head>
    <title>Not Defterim - Kategoriler</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">
</head>
<body>
<div class="col-md-offset-1 col-md-10 col-md-offset-1" style="margin-top: 25px;"> 
        <h3>Kategoriler  : </h3><hr>
        <table id="table" class="table table-responsive table-bordered table-striped">
            <thead>
                <tr>
                <th>Kullanıcı</th>
                <th>Kategori</th>
                <th>Oluşturma Tarihi</th>
                <th>İşlemler</th>
                </tr>       
            </thead>
            <tbody>
                <?php
                    $categories = $database->selectMany("categories",["user_id" =>(int) $_SESSION['user_id']]);
                    foreach($categories as $key=> $category){
                        $user = $database->selectOne("users", ["id" => $category->user_id]);
                ?>
                    <tr>
                        <td><?php echo $user->name; ?></td>
                        <td><?php echo  $category->name; ?></td>
                        <td><?php echo  date("d.m.Y H:i", strtotime($category->created_at)); ?></td>
                        <td>
                            <a href="" class="btn btn-sm btn-warning">Güncelle</a>
                            <a href="" class="btn btn-danger btn-sm ">Sil</a></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<script
  src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        table = $('#table').DataTable();
    } );
</script>