<?php
    require '../../database.php';
 
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( null==$id ) {
        header("Location: showPeliculas.php");
    }
     
    if ( !empty($_POST)) {
        // keep track validation errors
        $tituloError = null;
        $generoError = null;
        $actoresError = null;
        $directorError = null;
        $resenaError = null;
        $img_portadaError = null;
        $codigo_trailerError = null;
        $paisError = null;
         
        // keep track post values
        $titulo = $_POST['titulo'];;
        $genero = $_POST['genero'];;
        $actores = $_POST['actores'];;
        $director = $_POST['director'];;
        $resena = $_POST['resena'];;
        $img_portada = $_POST['portada'];;
        $codigo_trailer = $_POST['trailer'];;
        $pais = $_POST['pais'];;
         
        // validate input
        $valid = true;
        if (empty($titulo)) {
            $tituloError = 'Ingrese el titulo';
            echo $tituloError;
            $valid = false;
        }
         
        if (empty($genero)) {
            $generoError = 'Ingrese el genero';
            $valid = false;
            echo $generoError;
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

        if (empty($resena)) {
            $resenaError = 'Ingrese la resena';
            $valid = false;
            echo $resenaError;
        }

        if (empty($img_portada)) {
            $img_portadaError = 'Ingrese la url de la imagen de portada';
            $valid = false;
            echo $img_portadaError;
        }

        if (empty($codigo_trailer)) {
            $codigo_trailerError = 'Ingrese la url del trailer';
            $valid = false;
            echo $codigo_trailerError;
        }

        if (empty($pais)) {
            $paisError = 'Ingrese el pais';
            $valid = false;
            echo $paisError;
        }
        
        // update data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE peliculas set titulo = ?, genero = ?, actores = ?, director = ?, resena = ?, img_portada = ?, codigo_trailer = ?, pais = ? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($titulo,$genero,$actores,$director,$resena,$img_portada,$codigo_trailer,$pais,$id));
            Database::disconnect();
            header("Location: showPeliculas.php");
        }
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'select *, paises.nombre as paisNombre, directores.nombre as directorNombre, generos.nombre as generoNombre from peliculas left join paises on peliculas.pais = paises.id join directores on peliculas.director = directores.id  join generos on peliculas.genero = generos.id WHERE peliculas.id = ?';
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $actores = $data['actores'];
        $titulo = $data['titulo'];
        $titulo = $data['titulo'];
        $genero = $data['genero'];
        $resena = $data['resena'];
        $portada = $data['img_portada'];
        $trailer = $data['codigo_trailer'];
        $director = $data['director'];
        $pais = $data['pais'];
        $paisNombre = $data['paisNombre'];
        $directorNombre = $data['directorNombre'];
        $generoNombre = $data['generoNombre'];
        
        $sql = 'select * from paises where id != '.$pais; 
        $q = $pdo->prepare($sql);
        $q -> execute(array($pais));
        $paisesNombres = array();
        $paisesIds = array();
        while($d = $q->fetch( PDO::FETCH_ASSOC )){ 
          array_push($paisesNombres, $d['nombre']);
          array_push($paisesIds, $d['id']);
        }

        $sql = 'select * from directores where id != '.$director; 
        $q = $pdo->prepare($sql);
        $q -> execute(array($director));
        $directoresNombres = array();
        $directoresIds = array();
        while($dr = $q->fetch( PDO::FETCH_ASSOC )){ 
          array_push($directoresNombres, $dr['nombre']);
          array_push($directoresIds, $dr['id']);
        }        

        $sql = 'select * from generos where id != '.$genero; 
        $q = $pdo->prepare($sql);
        $q -> execute(array($genero));
        $generosNombres = array();
        $generosIds = array();
        while($dr = $q->fetch( PDO::FETCH_ASSOC )){ 
          array_push($generosNombres, $dr['nombre']);
          array_push($generosIds, $dr['id']);
        }

        $actIds = explode(",", $data['actores']);        
        $selectedActores = 'select * from actores where id in (';
        $notSelectedActores = 'select * from actores where id not in ('    ;
        foreach ($actIds as $actId) {
            $selectedActores = $selectedActores.$actId.', ';
            $notSelectedActores = $notSelectedActores.$actId.', ';
        }
        $selectedActores = substr($selectedActores,0, -2).')';
        $notSelectedActores = substr($notSelectedActores,0,-2).')';
        $q  = $pdo->prepare($selectedActores);
        $q -> execute(array($selectedActores));
        $actoresNombres = array();
        $actoresIds = array();
        while($d = $q->fetch( PDO::FETCH_ASSOC )){
          array_push($actoresNombres, $d['nombre']);
          array_push($actoresIds, $d['id']);
        }

        $q  = $pdo->prepare($notSelectedActores);
        $actoresNSNombres = array();
        $actoresNSIds = array();
        $q -> execute(array($notSelectedActores));
        while($d = $q->fetch( PDO::FETCH_ASSOC )){
          array_push($actoresNSNombres, $d['nombre']);
          array_push($actoresNSIds, $d['id']);
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
                        <h3>Actualizar Peliculas</h3>
                    </div>
             
                    <form class="form-horizontal" action="updatePelicula.php?id=<?php echo $id?>" method="post">
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
                         <input name="genero" id="genero" hidden type="text" value="<?php echo !empty($genero)?$genero:'';?>">
                            <select id="generoSelect">
                                <option value="<?php echo !empty($genero)?$genero:'';?>"><?php echo !empty($generoNombre)?$generoNombre:'';?></option>
                                <?php
                                 for ($x = 0; $x < sizeof($generosNombres); $x++) {
                                   echo '<option value="'.$generosIds[$x].'">'.$generosNombres[$x].'</option>';
                                 }
                                ?>
                            </select>
                            <?php if (!empty($generoError)): ?>
                                <span class="help-inline"><?php echo $generoError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($paisError)?'error':'';?>">
                        <label class="control-label">Pais</label>
                        <div class="controls">
                         <input name="pais" id="pais" hidden type="text" value="<?php echo !empty($pais)?$pais:'';?>">
                            <select id="paisSelect">
                                <option value="<?php echo !empty($pais)?$pais:'';?>"><?php echo !empty($paisNombre)?$paisNombre:'';?></option>
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
                      <div class="control-group <?php echo !empty($directorError)?'error':'';?>">
                        <label class="control-label">*Director:</label>
                        <div class="controls">
                         <input name="director" id="director" hidden type="text" value="<?php echo !empty($director)?$director:'';?>">
                            <select id="directorSelect">
                                <option value="<?php echo !empty($director)?$director:'';?>"><?php echo !empty($directorNombre)?$directorNombre:'';?></option>
                                <?php
                                 for ($x = 0; $x < sizeof($directoresNombres); $x++) {
                                   echo '<option value="'.$directoresIds[$x].'">'.$directoresNombres[$x].'</option>';
                                 }
                                ?>
                            </select>
                            <?php if (!empty($directorError)): ?>
                                <span class="help-inline"><?php echo $directorError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($actoresError)?'error':'';?>">
                        <label class="control-label">*Actores:</label>
                        <div class="controls">
                            <input name="actores" id="actores" hidden type="text" value="0">
                            <input name="actoresSelId" id="actoresSelId" hidden type="text" value="<?php for ($x = 0; $x < sizeof($actoresIds); $x++) { $aids.=$actoresIds[$x].', '; }echo substr($aids,0, -2);?>">
                            <input name="actoresSelNom" id="actoresSelNom" hidden type="text" value="<?php for ($x = 0; $x < sizeof($actoresNombres); $x++) { $anbr.=$actoresNombres[$x].', '; }echo substr($anbr,0, -2);?>">
                            <select id="actorSelect" multiple="multiple">
                                <?php
                                 for ($x = 0; $x < sizeof($actoresIds); $x++) {
                                   echo '<option value="'.$actoresIds[$x].'">'.$actoresNombres[$x].'</option>';
                                 }
                                ?>
                                <?php
                                 for ($x = 0; $x < sizeof($actoresNSIds); $x++) {
                                   echo '<option value="'.$actoresNSIds[$x].'">'.$actoresNSNombres[$x].'</option>';
                                 }
                                ?>
                            </select>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($resenaError)?'error':'';?>">
                        <label class="control-label">*Reseña:</label>
                        <div class="controls">
                            <input name="resena" type="text" placeholder="Resena" value="<?php echo !empty($resena)?$resena:'';?>">
                            <?php if (!empty($resenaError)): ?>
                                <span class="help-inline"><?php echo $resenaError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($portadaError)?'error':'';?>">
                        <label class="control-label">*Imagen Portada:</label>
                        <div class="controls">
                            <input name="portada" type="text" placeholder="Portada" value="<?php echo !empty($portada)?$portada:'';?>">
                            <?php if (!empty($portadaError)): ?>
                                <span class="help-inline"><?php echo $portadaError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($trailerError)?'error':'';?>">
                        <label class="control-label">*Trailer:</label>
                        <div class="controls">
                            <input name="trailer" type="text" placeholder="Trailer" value="<?php echo !empty($trailer)?$trailer:'';?>">
                            <?php if (!empty($trailerError)): ?>
                                <span class="help-inline"><?php echo $trailerError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <br>
                      <div class="form-actions">
                          <button onClick="savePaisId();" type="submit" class="btn btn-success">Actualizar</button>
                          <a class="btn btn-default" href="showPeliculas.php">Volver</a>
                        </div>
                    </form>
                </div>
                 
    </div>
  </body>
</html>