<?php
     
    require '../../database.php';
 
    if ( !empty($_POST)) {
        // keep track validation errors
        $generoError = null;
        $paisError = null;
        $actoresError = null;
        $directorError = null;
        $tituloError = null;
        $resenaError = null;
        $img_portadaError = null;
        $codigo_trailerError = null;
         
        // keep track post values
        $genero = $_POST['genero'];
        $pais = $_POST['pais'];
        $actores = $_POST['actores'];
        $director = $_POST['director'];
        $titulo = $_POST['titulo'];
        $resena = $_POST['resena'];
        $img_portada = $_POST['img_portada'];
        $codigo_trailer = $_POST['codigo_trailer'];
         
        // validate input
        $valid = true;
        if (empty($genero)) {
            $generoError = 'Ingrese el genero';
            $valid = false;
            echo $generoError;
        }
         
        if (empty($pais)) {
            $paisError = 'Ingrese el pais';
            $valid = false;
            echo $paisError;
        }
         
        if (empty($actores)) {
            $actoresError = 'Ingrese los actores';
            $valid = false;
            echo $actoresError;
        }

        if (empty($director)) {
            $directorError = 'Ingrese el director';
            $valid = false;
            echo $directorError;
        }

        if (empty($titulo)) {
            $tituloError = 'Ingrese el titulo';
            $valid = false;
            echo $tituloError;
        }

        if (empty($resena)) {
            $resenaError = 'Ingrese la resena';
            $valid = false;
            echo $resenaError;
        }

        if (empty($img_portada)) {
            $img_portadaError = 'Ingrese la imagen de portada';
            $valid = false;
            echo $img_portadaError;
        }
         
        if (empty($codigo_trailer)) {
            $codigo_trailerError = 'Ingrese el codigo trailer';
            $valid = false;
            echo $codigo_trailerError;
        }
         
         echo 'valid: '.$valid;
        // insert data
        if ($valid) {

        echo "genero: ".$genero;
        echo " pais: ".$pais;
        echo " actores: ".$actores;
        echo " director: ".$director;
        echo " titulo: ".$titulo;
        echo " resena: ".$resena;
        echo " img_portada: ".$img_portada;
        echo " codigo_trailer: ".$codigo_trailer;
            $pdo = Database::connect();
            echo 'actores '.$actores;
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO peliculas (genero,actores,pais,director,titulo,resena,img_portada,codigo_trailer) values(?, ?, ?, ?, ?, ?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($genero,$actores,$pais,$director,$titulo,$resena,$img_portada,$codigo_trailer));
            Database::disconnect();
            header("Location: showPeliculas.php");
        }else{
          echo "not valid";
        }

    }else{
      $pdo = Database::connect();
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sqld = 'select * from directores';
      $sqla = 'select * from actores';
      $sqlp = 'select * from paises';
      $sqlg = 'select * from generos';
        /*$sql = 'select * from paises';*/
      $qd = $pdo->prepare($sqld);
      $qd->execute(array($id));
      $directoresNombres = array();
      $directoresIds = array();

      $qa = $pdo->prepare($sqla);
      $qa->execute(array($id));
      $actoresNombres = array();
      $actoresIds = array();

      $qp = $pdo->prepare($sqlp);
      $qp->execute(array($id));
      $paisesNombres = array();
      $paisesIds = array();

      $qg = $pdo->prepare($sqlg);
      $qg->execute(array($id));
      $generosNombres = array();
      $generosIds = array();
      
      while($d = $qd->fetch( PDO::FETCH_ASSOC )){ 
        array_push($directoresNombres, $d['nombre']);
        array_push($directoresIds, $d['id']);
      }

      while($a = $qa->fetch( PDO::FETCH_ASSOC )){ 
        array_push($actoresNombres, $a['nombre']);
        array_push($actoresIds, $a['id']);
      }

      while($p = $qp->fetch( PDO::FETCH_ASSOC )){ 
        array_push($paisesNombres, $p['nombre']);
        array_push($paisesIds, $p['id']);
      }

      while($g = $qg->fetch( PDO::FETCH_ASSOC )){ 
        array_push($generosNombres, $g['nombre']);
        array_push($generosIds, $g['id']);
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
    <script src="../../js/peliculas.js"></script>
    <script src="../../js/bootstrap-multiselect-master/bootstrap-multiselect.js"></script>
    <script src="../../js/bootstrap-multiselect-master/bootstrap-multiselect-collapsible-groups.js"></script>
</head>
 
<body>
    <div class="container">
     
                <div class="span10 offset1">
                    <div class="row">
                        <h3>Crear Peliculas</h3>
                    </div>
             
                    <form class="form-horizontal" action="newPelicula.php" method="post">
                      <div class="control-group <?php echo !empty($tituloError)?'error':'';?>">
                        <label class="control-label">*Titulo:</label>
                        <div class="controls">
                            <input name="titulo" type="text"  placeholder="Titulo" value="<?php echo !empty($titulo)?$titulo:'';?>">
                            <?php if (!empty($tituloError)): ?>
                                <span class="help-inline"><?php echo $tituloError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>                      
                      <div class="control-group <?php echo !empty($generoError)?'error':'';?>">
                        <label class="control-label">*Genero:</label>
                        <div class="controls">
                             <input name="genero" id="genero" hidden type="text" value="0">
                            <select id="generoSelect">
                                <option selected value="0"><?php echo 'Seleccione el genero';?></option>
                                <?php
                                 for ($x = 0; $x < sizeof($generosNombres); $x++) {
                                   echo '<option value="'.$generosIds[$x].'">'.$generosNombres[$x].'</option>';
                                 }
                                ?>
                            </select>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($directorError)?'error':'';?>">
                        <label class="control-label">*Director:</label>
                        <div class="controls">
                             <input name="director" id="director" hidden type="text" value="0">
                            <select id="directorSelect">
                                <option selected value="0"><?php echo 'Seleccione el director';?></option>
                                <?php
                                 for ($x = 0; $x < sizeof($directoresNombres); $x++) {
                                   echo '<option value="'.$directoresIds[$x].'">'.$directoresNombres[$x].'</option>';
                                 }
                                ?>
                            </select>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($actoresError)?'error':'';?>">
                        <label class="control-label">*Actores:</label>
                        <div class="controls">
                             <input name="actores" id="actores" hidden type="text" value="0">
                            <select id="actorSelect" multiple="multiple">
                                <?php
                                 for ($x = 0; $x < sizeof($actoresNombres); $x++) {
                                   echo '<option value="'.$actoresIds[$x].'">'.$actoresNombres[$x].'</option>';
                                 }
                                ?>
                            </select>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($resenaError)?'error':'';?>">
                        <label class="control-label">*Resena:</label>
                        <div class="controls">
                            <input name="resena" type="text" placeholder="Reseña" value="<?php echo !empty($resena)?$resena:'';?>">
                            <?php if (!empty($resenaError)): ?>
                                <span class="help-inline"><?php echo $resenaError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($img_portadaError)?'error':'';?>">
                        <label class="control-label">*Portada:</label>
                        <div class="controls">
                            <input name="img_portada" type="text" placeholder="Portada" value="<?php echo !empty($img_portada)?$img_portada:'';?>">
                            <?php if (!empty($img_portadaError)): ?>
                                <span class="help-inline"><?php echo $img_portadaError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($codigo_trialerError)?'error':'';?>">
                        <label class="control-label">*Trailer:</label>
                        <div class="controls">
                            <input name="codigo_trailer" type="text" placeholder="Trailer" value="<?php echo !empty($codigo_trailer)?$codigo_trailer:'';?>">
                            <?php if (!empty($codigo_trailerError)): ?>
                                <span class="help-inline"><?php echo $codigo_trailerError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($paisError)?'error':'';?>">
                        <label class="control-label">*Pais:</label>
                        <div class="controls">
                             <input name="pais" id="pais" hidden type="text" value="0">
                            <select id="paisSelect">
                                <option selected value="0"><?php echo 'Seleccione el pais';?></option>
                                <?php
                                 for ($x = 0; $x < sizeof($paisesNombres); $x++) {
                                   echo '<option value="'.$paisesIds[$x].'">'.$paisesNombres[$x].'</option>';
                                 }
                                ?>
                            </select>
                        </div>
                      </div>
                      <br>
                      <div class="form-actions">
                          <button onClick="saveSelectId();" type="submit" class="btn btn-success">Guardar</button>
                          <a class="btn btn-default" href="showPeliculas.php">Volver</a>
                        </div>
                    </form>
                </div>
                 
    </div>
  </body>
</html>