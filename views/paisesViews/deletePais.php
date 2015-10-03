<?php
    require '../../database.php';
    $id = 0;
     
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( !empty($_POST)) {
        // keep track post values
        $id = $_POST['id'];
         
        // delete data
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT ( SELECT COUNT(*) FROM directores WHERE pais = ? ) AS dir, ( SELECT COUNT(*) FROM actores WHERE pais = ? ) AS act";
        $q = $pdo->prepare($sql);
        $q->execute(array($id, $id)); 
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $dir = $data['dir'];
        $act = $data['act'];
        if( $dir > 0 || $act > 0 ){
            echo '<p class="alert alert-danger"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> No se puede eliminar el pais de un director o actor</p>';
        }else{
            echo 'no trajo datos';
            $sql = "DELETE FROM paises WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($id));
            Database::disconnect();
            header("Location: showPaises.php");
        }
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <?php echo file_get_contents('../genericHtml.html'); ?>
</head>
 
<body>
    <div class="container">
     
                <div class="span10 offset1">
                    <div class="row">
                        <h3>Eliminar Pais</h3>
                    </div>
                     
                    <form class="form-horizontal" action="deletePais.php" method="post">
                      <input type="hidden" name="id" value="<?php echo $id;?>"/>
                      <p class="alert alert-danger"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Esta seguro de eliminar ?</p>

                      <div class="form-actions">
                          <button type="submit" class="btn btn-danger">Si</button>
                          <a class="btn btn-default" href="showPaises.php">No</a>
                        </div>
                    </form>
                </div>
    </div>
  </body>
</html>