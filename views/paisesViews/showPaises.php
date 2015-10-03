<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <?php echo file_get_contents('../genericHtml.html'); ?>
</head>
 
<body>
    <div class="container">
      <nav class="navbar navbar-default navbar-fixed-top">
            <ul class="nav navbar-nav">
              <li><a href="../../index.php">Home</a></li>
              <li><a href="../directoresViews/showDirectores.php">Directores</a></li>
              <li><a href="../generosViews/showGeneros.php">Generos</a></li>
              <li class="active"><a href="../paisesViews/showPaises.php">Paises</a></li>
              <li><a href="../actoresViews/showActores.php">Actores</a></li>
              <li><a href="../peliculasViews/showPeliculas.php">Peliculas</a></li>
            </ul>
      </nav>
      <br>
      <br>
      <br>
            <div class="row">
                <h3>Paises</h3>
                <p>
                    <a href="newPais.php" class="btn btn-success">Nuevo Pais</a>
                </p>
            </div>
            <div class="row">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Nombre</th>
                      <th>Accion</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                   include '../../database.php';
                   $pdo = Database::connect();
                   $sql = 'select * from paises ORDER BY id DESC';
                   foreach ($pdo->query($sql) as $row) {
                            echo '<tr>';
                            echo '<td>'. $row['nombre'] . '</td>';
                            echo '<td width=250>';
                                echo ' ';
                                echo '<a class="btn btn-primary" href="updatePais.php?id='.$row['id'].'">Actualizar</a>';
                                echo ' ';
                                echo '<a class="btn btn-danger" href="deletePais.php?id='.$row['id'].'">Eliminar</a>';
                                echo '</td>';
                            echo '</tr>';
                   }
                   Database::disconnect();
                  ?>
                  </tbody>
            </table>
        </div>
    </div>
  </body>
</html>