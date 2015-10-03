<?php
    require '../../database.php';
 
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( null==$id ) {
        header("Location: showPaises.php");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $nombreError = null;
         
        // keep track post values
        $nombre = $_POST['nombre'];

        // validate input
        $valid = true;
        if (empty($nombre)) {
            $nombreError = 'Ingrese el nombre';
            $valid = false;
        }

        // update data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE paises set nombre = ? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($nombre, $id));
            Database::disconnect();
            header("Location: showPaises.php");
        }
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'select * from paises where id = ?';
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $nombre = $data['nombre'];
        Database::disconnect();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <?php echo file_get_contents('../genericHtml.html'); ?>
    <script src="../../js/update.js"></script>
</head>
 
<body>
    <div class="container">
     
                <div class="span10 offset1">
                    <div class="row">
                        <h3>Actualizar Genero</h3>
                    </div>
             
                    <form class="form-horizontal" action="updatePais.php?id=<?php echo $id?>" method="post">
                      <div class="control-group <?php echo !empty($nombreError)?'error':'';?>">
                        <label class="control-label">Nombre</label>
                        <div class="controls">
                            <input name="nombre" type="text"  placeholder="Nombre" value="<?php echo !empty($nombre)?$nombre:'';?>">
                            <?php if (!empty($nombreError)): ?>
                                <span class="help-inline"><?php echo $nombreError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      <br>
                      <div class="form-actions">
                          <button onClick="savePaisId();" type="submit" class="btn btn-success">Actualizar</button>
                          <a class="btn btn-default" href="showPaises.php">Volver</a>
                        </div>
                    </form>
                </div>                 
    </div>
  </body>
</html>