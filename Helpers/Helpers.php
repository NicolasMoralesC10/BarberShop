<?php

function base_url()
{
    return BASE_URL;
}

function media()
{
    return BASE_URL . "/Assets";
}

function header_admin($data = "")
{
    $view_header = "Views/Template/header_admin.php";
    require_once($view_header);
}

function aside_admin($data = "")
{
    $view_header = "Views/Template/aside_admin.php";
    require_once($view_header);
}

function nav_admin($data = "")
{
    $view_header = "Views/Template/nav_admin.php";
    require_once($view_header);
}

function footer_admin($data = "")
{
    $view_footer = "Views/Template/footer_admin.php";
    require_once($view_footer);
}

function dep($data)
{
    $format = print_r('<pre>');
    $format .= print_r($data);
    $format .= print_r('<pre>');
    return $format;
}

function getModal(string $nameModal, $data)
{
    $view_modal = "Views/Template/Modals/{$nameModal}.php";
    require_once $view_modal;
}

function strClean($strCadena)
{
    $string = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strCadena);
    $string = trim($string); //Elimina espacios en blanco al inicio y al final
    $string = stripslashes($string); // Elimina las \ invertidas
    $string = str_ireplace("<script>", "", $string);
    $string = str_ireplace("</script>", "", $string);
    $string = str_ireplace("<script src>", "", $string);
    $string = str_ireplace("<script type=>", "", $string);
    $string = str_ireplace("SELECT * FROM", "", $string);
    $string = str_ireplace("DELETE FROM", "", $string);
    $string = str_ireplace("INSERT INTO", "", $string);
    $string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
    $string = str_ireplace("DROP TABLE", "", $string);
    $string = str_ireplace("OR '1'='1", "", $string);
    $string = str_ireplace('OR "1"="1"', "", $string);
    $string = str_ireplace('OR `1`=`1`', "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("in NULL; --", "", $string);
    $string = str_ireplace("LIKE '", "", $string);
    $string = str_ireplace('LIKE "', "", $string);
    $string = str_ireplace('LIKE `', "", $string);
    $string = str_ireplace("OR 'a'='a", "", $string);
    $string = str_ireplace('OR "a"="a', "", $string);
    $string = str_ireplace("OR `a`=`a", "", $string);
    $string = str_ireplace("OR `a`=`a", "", $string);
    $string = str_ireplace("--", "", $string);
    $string = str_ireplace("^", "", $string);
    $string = str_ireplace("[", "", $string);
    $string = str_ireplace("]", "", $string);
    $string = str_ireplace("==", "", $string);
    $string = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
    return $string;
}

function check_post(array $postNames)
{

    $validState = true;
    foreach ($postNames as $value) {
        if (!isset($_POST[$value]) || empty(strClean($_POST[$value]))) {
            $validState = false;
        }
    }

    return $validState;
}
function check_post_var($post)
{

    $validState = true;
    if (!isset($post) || empty(strClean($post))) {
        $validState = false;
    }
    return $validState;
}

function check_file(array $fileName)
{
    $validState = true;
    foreach ($fileName as $value) {
        if ($_FILES[$value]['error'] == 4 || ($_FILES[$value]['size'] == 0 && $_FILES[$value]['error'] == 0)) {
            $validState = false;
        }
    }
    return $validState;
}

function save_image($fileName)
{
    $imagen = $_FILES[$fileName];
    $response = "";
    $directorio = 'Assets/img/uploded/';
    if (!is_dir($directorio)) {
        mkdir($directorio, 0777, true);
    }
    $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
    $nuevoNombreImagen = pathinfo($imagen['name'], PATHINFO_FILENAME) . '_' . date("Ymd_His") . '.' . $extension;
    $rutaArchivo = $directorio . $nuevoNombreImagen;
    if (move_uploaded_file($imagen['tmp_name'], $rutaArchivo)) {
        $response = $rutaArchivo;
    }

    return $response;
}

function uploadFile($file)
{
    $arrResponse = array('status' => false, 'msg' => '');
    // Definir el directorio de destino
    $targetDir = "pdf/";

    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0777, true)) {
            $arrResponse['msg'] = "No se pudo crear el directorio para subir el archivo.";
            return json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }

    $fileName = basename($file["name"]);
    $fileName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $fileName);  // Reemplaza caracteres no permitidos por _

    // Generar un nombre único para evitar sobreescribir archivos existentes
    $targetFile = $targetDir . uniqid() . "_" . $fileName;

    // Obtener la extensión del archivo
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if ($fileType != "pdf") {
        $arrResponse['msg'] = "Solo se permiten archivos PDF.";
        return json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    // Verificar el tamaño del archivo (no permitir archivos mayores a 5MB)
    $maxFileSize = 5 * 1024 * 1024;
    if ($file["size"] > $maxFileSize) {
        $arrResponse['msg'] = "El archivo es demasiado grande. El tamaño máximo permitido es 5MB.";
        return json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }

    // Intentar mover el archivo al directorio de destino
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        $arrResponse['status'] = true;
        $arrResponse['msg'] = "El archivo ha sido cargado correctamente.";
        $arrResponse['filePath'] = $targetFile;
    } else {
        $arrResponse['msg'] = "Hubo un error al subir el archivo.";
    }
    return json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
}
