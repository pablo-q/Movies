<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <?php echo file_get_contents('../genericHtml.html'); ?>
    <link rel="stylesheet" href="../../css/style.css" type="text/css">
    <script src="../../js/update.js"></script>
</head>
 
<body>
    <div class="container">
      <nav class="navbar navbar-default navbar-fixed-top">
            <ul class="nav navbar-nav">
              <li><a href="../../index.php">Home</a></li>
              <li><a href="../directoresViews/showDirectores.php">Directores</a></li>
              <li><a href="../generosViews/showGeneros.php">Generos</a></li>
              <li><a href="../paisesViews/showPaises.php">Paises</a></li>
              <li><a href="../actoresViews/showActores.php">Actores</a></li>
              <li class="active"><a href="../peliculasViews/showPeliculas.php">Peliculas</a></li>
            </ul>
      </nav>
      <br>
      <br>
      <br>
            <div class="row">
                <h3>Peliculas</h3>
                <p>
                    <a href="newPelicula.php" class="btn btn-success">Nueva Pelicula</a>
                </p>
            </div>

            <?php
                   include '../../database.php';
                   $pdo = Database::connect();
                   $sql = 'select p.id as id, g.nombre as genero, pa.nombre as pais, actores, d.nombre as director, titulo, resena, img_portada as portada, codigo_trailer as trailer from peliculas p left join directores d on p.director = d.id left join paises pa on p.pais = pa.id left join generos g on p.genero = g.id;
                            ORDER BY id DESC';
                    $actoresNombres = array();
                    $actoresIds = array();
            /*        $data = $pdo->prepare($sql);
                    $data->execute(array($id));
                    $data->fetch( PDO::FETCH_ASSOC ); 
                    echo 'data '.$data;       */
                  echo '<div class="row">';
                  $rowCounter = 1;
                  foreach ($pdo->query($sql) as $row) {
                      $id = $row['id'];
                      $portada = $row['portada'];
                      $trailer = str_replace("watch?v=", "embed/", $row['trailer']);
                      $resena = $row['resena'];
                      $quote = "'";
                      $onClick = '"thevid'.$id.'=document.getElementById('.$quote.'thevideo'.$id.$quote.'); thevid'.$id.'.style.display='.$quote.'block'.$quote.'; this.style.display='.$quote.'none'.$quote.';playVideo('.$quote.$id.$quote.');"';
                      $actIds = explode(",", $row['actores']);
                      $sqlActores = 'select * from actores where id in (';
                      foreach ($actIds as $actId) {
                        $sqlActores .= '?,';
                      }
                      $sqlActores = substr($sqlActores,0, -1).')';
                      $q = $pdo->prepare($sqlActores);
                      $q->execute($actIds);
                      $actString = '';
                      while($a = $q->fetch( PDO::FETCH_ASSOC )){ 
                        array_push($actoresNombres, $a['nombre']);
                        array_push($actoresIds, $a['id']); 
                        $actString = $actString.$a['nombre'].', ';
                      }
                      $actString = substr($actString,0, -2);
                      /*sizeof*/
                      array_push($actoresNombres, $row['actores']);
                      array_push($actoresIds, $row['nombre']);

                      echo '<div class="col-lg-4 col-sm-4 col-md-4 peliFrame">';
                      echo   '<div class="row" id="videoFrame">';
                      echo     '<div onClick='.$onClick.'><img src="'.$portada.'" style="cursor:pointer" />';
                      echo     '</div>';
                      echo     '<div class="thevideo" id="thevideo'.$id.'" style="display:none">';
                      echo       '<iframe id="clickMe'.$id.'" width="560" height="315" src="'.$trailer.'" frameborder="0" allowfullscreen></iframe>';
                      echo     '</div>';
                      echo   '</div>';
                      echo   '<div class="row" id="peliContent'.$id.'">';
                      echo     '<div class="col-lg-6 col-md-6 col-sm-6 leftColumn">';
                      echo       '<div class="col-lg-12 col-md-12 col-sm-12 leftElement">';
                      echo         '<a href="updatePelicula.php?id='.$id.'">Editar</a>';
                      echo         '<a class="deleteLink" href="deletePelicula.php?id='.$id.'">Eliminar</a>';
                      echo       '</div>';
                      echo       '<div class="col-lg-12 col-md-12 col-sm-12 leftElement">';
                      echo         '<label>Titulo:</label>';
                      echo          ' '.$row['titulo'];
                      echo       '</div>';
                      echo       '<div class="col-lg-12 col-md-12 col-sm-12 leftElement">';
                      echo         '<label>Genero:</label>';
                      echo          ' '.$row['genero'];
                      echo       '</div>';
                      echo       '<div class="col-lg-12 col-md-12 col-sm-12 leftElement">';
                      echo         '<label>Director:</label>';                      
                      echo          ' '.$row['director'];  
                      echo       '</div>';
                                
                      echo       '<div class="col-lg-12 col-md-12 col-sm-12 leftElement">';
                      echo         '<label>Pais:</label>';
                      echo          ' '.$row['pais'];  
                      echo       '</div>';
                            
                      echo       '<div class="col-lg-12 col-md-12 col-sm-12 leftElement">';
                      echo         '<label>Actores:</label>';  
                      echo          ' '.$actString;                                
                      echo       '</div>';

                      echo     '</div>';

                      echo     '<div class="col-lg-6 col-md-6 col-sm-6 rightColumn">';
                      echo       '<div class="col-lg-4 col-md-4 col-sm-4">';
                      echo         '<label>Reseña:</label>';  
                      echo       '</div>';
                      echo       '<div class="col-lg-8 col-md-8 col-sm-8 rightElement">';
                      echo          ' '.$resena;             
                      echo        '</div>';
                      echo     '</div>';
                      echo   '</div>';

                      echo '</div>  ';
                      $rowCounter = $rowCounter+1;
                      if($rowCounter == 4){
                        $rowCounter = 1;
                        echo '</div>';
                        echo '<div class="row">';
                      }
                      
                  }

                  Database::disconnect();
            ?>

    </div>
  </body>
</html>