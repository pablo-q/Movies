<?php
    require '../../database.php';
 
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( null==$id ) {
        header("Location: showActores.php");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $nombreError = null;
        $apellidoError = null;
        $paisError = null;
         
        // keep track post values
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $pais = $_POST['pais'];
         
        // validate input
        $valid = true;
        if (empty($nombre)) {
            $nombreError = 'Ingrese el nombre';
            $valid = false;
        }
         
        if (empty($apellido)) {
            $apellidoError = 'Ingrese el apellido';
            $valid = false;
        }
         
        if (empty($pais)) {
            $paisError = 'Ingrese el pais';
            $valid = false;
        }
        // update data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE actores set nombre = ?, apellido = ?, pais = ? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($nombre,$apellido,$pais,$id));
            Database::disconnect();
            header("Location: showActores.php");
        }
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'select actores.id AS id, actores.nombre AS nombre, apellido, paises.nombre AS pais, paises.id as paisId FROM actores INNER JOIN paises ON actores.pais = paises.id WHERE actores.id = ?';
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
        $pais = $data['pais'];
        $paisId = $data['paisId'];

        $sql = 'select * from paises where nombre != '."'".$pais."'"; 
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $paisesNombres = array();
        $paisesIds = array();
        while($d = $q->fetch( PDO::FETCH_ASSOC )){ 
          array_push($paisesNombres, $d['nombre']);
          array_push($paisesIds, $d['id']);
        }
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
                        <h3>Actualizar Actor</h3>
                    </div>
             
                    <form class="form-horizontal" action="updateActor.php?id=<?php echo $id?>" method="post">
                      <div class="control-group <?php echo !empty($nombreError)?'error':'';?>">
                        <label class="control-label">Nombre</label>
                        <div class="controls">
                            <input name="nombre" type="text"  placeholder="Nombre" value="<?php echo !empty($nombre)?$nombre:'';?>">
                            <?php if (!empty($nombreError)): ?>
                                <span class="help-inline"><?php echo $nombreError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($apellidoError)?'error':'';?>">
                        <label class="control-label">Apellido</label>
                        <div class="controls">
                            <input name="apellido" type="text" placeholder="Apellido" value="<?php echo !empty($apellido)?$apellido:'';?>">
                            <?php if (!empty($apellidoError)): ?>
                                <span class="help-inline"><?php echo $apellidoError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($paisError)?'error':'';?>">
                        <label class="control-label">Pais</label>
                        <div class="controls">
                         <input name="pais" id="pais" hidden type="text" value="<?php echo !empty($paisId)?$paisId:'';?>">
                            <select id="paisSelect">
                                <option value="<?php echo !empty($paisId)?$paisId:'';?>"><?php echo !empty($pais)?$pais:'';?></option>
                                <?php
                                 for ($x = 0; $x < sizeof($paisesNombres); $x++) {
                                   echo '<option value="'.$paisesIds[$x].'">'.$paisesNombres[$x].'</option>';
                                 }
                                ?>
                            </select>

                            <?php if (!empty($paisError)): ?>
                                <span class="help-inline"><?php echo $paisError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <br>
                      <div class="form-actions">
                          <button onClick="savePaisId();" type="submit" class="btn btn-success">Actualizar</button>
                          <a class="btn btn-default" href="showActores.php">Volver</a>
                        </div>
                    </form>
                </div>
                 
    </div>
  </body>
</html>