<?
    // Pon la ruta del directorio de donde listar los archivos desde el root
    function listarArchivos(){
        $pathFile = "./files/";
        $pathIco = "img/";
        $arrayExcepciones = array(".","..","index.php" );

        // Abrir la carpeta
        $dir_handle = @opendir($pathFile) or die("No existe la ruta indicada");
        
        // Leer los archivos
        while ($file=readdir($dir_handle)) { 
            if(in_array($file, $arrayExcepciones))continue;
            $arrayArchivos[] = $file;}

        // Cerrar la carpeta
        closedir($dir_handle);
        
        //Ordenar y mostrar el directorio
        sort($arrayArchivos);

        foreach ($arrayArchivos as $archivo) {construirEnlace($archivo);}      
    }

    function construirEnlace($filename){
            $pathFile = "./files/".$filename;
            $pathIco = "img/".identificarExtension($filename);;
            echo "<li><img src='".$pathIco.".jpg' /><a href=\"".$pathFile."\">".$filename."</a></li>";
            echo "<li>".sizeOfArchivo($pathFile)."</li>"; 
    }

    function identificarExtension($filename){
        return pathinfo($filename, PATHINFO_EXTENSION);;
    }

    function sizeOfArchivo($filename){
        return filesize($filename);
    }


    function extension($filename){
        return substr(strrchr($filename, '.'), 1);
    }    

    function identificarExtension2($archivo){
        $separacion=explode(".", $archivo);
        $nombreArchivo=$separacion[0];
        $extensionArchivo=$separacion[1];
        return $extensionArchivo;
    }
   
    function filedata($path) {
        // Vaciamos la caché de lectura de disco
        clearstatcache();
        // Comprobamos si el fichero existe
        $data["exists"] = is_file($path);
        // Comprobamos si el fichero es escribible
        $data["writable"] = is_writable($path);
        // Leemos los permisos del fichero
        $data["chmod"] = ($data["exists"] ? substr(sprintf("%o", fileperms($path)), -4) : FALSE);
        // Extraemos la extensión, un sólo paso
        $data["ext"] = substr(strrchr($path, "."),1);
        // Primer paso de lectura de ruta
        $data["path"] = array_shift(explode(".".$data["ext"],$path));
        // Primer paso de lectura de nombre
        $data["name"] = array_pop(explode("/",$data["path"]));
        // Ajustamos nombre a FALSE si está vacio
        $data["name"] = ($data["name"] ? $data["name"] : FALSE);
        // Ajustamos la ruta a FALSE si está vacia
        $data["path"] = ($data["exists"] ? ($data["name"] ? realpath(array_shift(explode($data["name"],$data["path"]))) : realpath(array_shift(explode($data["ext"],$data["path"])))) : ($data["name"] ? array_shift(explode($data["name"],$data["path"])) : ($data["ext"] ? array_shift(explode($data["ext"],$data["path"])) : rtrim($data["path"],"/")))) ;
        // Ajustamos el nombre a FALSE si está vacio o a su valor en caso contrario
        $data["filename"] = (($data["name"] OR $data["ext"]) ? $data["name"].($data["ext"] ? "." : "").$data["ext"] : FALSE);
        // Devolvemos los resultados
        return $data;
    }


?>

<!–Maquetacion html–>

<!DOCTYPE html><!–Es una pagina html5–>

<html lang=”es”><!–Es una página en español–>

    <head>
        <meta charset=”utf-8″/><!–La pagina soporta la codificacion UTF-8–>
        <title>Zona de Descarga</title><!–Nombre del sitio web–>


    
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
    <LINK href="style.css" rel="stylesheet" type="text/css">
    </head>

    <body class="degradado" onLoad="ini()">
        <div name="general" id="general">
        <header>
            <hgroup>
                <h1>Representaciones Tecnimotors</h1>
                <h3>Zona de Descarga</h3><!–Slogan de la pagina–>
            </hgroup>

            <nav><!–Barra de navegacion–>
            
            </nav>
        </header>

        <section>
                <ol>
                <? listarArchivos(); ?>
                <a onClick="window.open('enlace.php?dato="$variable"', 'main')"; onmouseover="this.style.color='red';this.style.textDecoration='underline';this.style.cursor='Hand';" onmouseout="this.style.color='black';this.style.textDecoration='none';">Enlace</a>
                </ol>    
        </section>

        <footer>
            <h6>Estos archivos estan de alojados de manera temporal</h6>
        </footer>
        </div>
    </body>

</html>