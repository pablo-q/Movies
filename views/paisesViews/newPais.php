<?php
     
    require '../../database.php';
 
    if ( !empty($_POST)) {
        // keep track validation errors
      echo 'save';
        $nombreError = null;
         
        // keep track post values
        $nombre = $_POST['nombre'];
         
        // validate input
        $valid = true;
        if (empty($nombre)) {
            $nombreError = 'Ingrese el nombre del Pais';
            $valid = false;
        }
         
        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO paises (nombre) values(?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($nombre));
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
    <script src="../../js/update.js"></script>
</head>
 
<body>
    <div class="container">
     
                <div class="span10 offset1">
                    <div class="row">
                        <h3>Crear Pais</h3>
                    </div>
             
                    <form class="form-horizontal" action="newPais.php" method="post">
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
                          <button type="submit" class="btn btn-success">Guardar</button>
                          <a class="btn btn-default" href="showPaises.php">Volver</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>