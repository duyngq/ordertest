<?php
error_reporting(E_ALL ^ E_DEPRECATED);
session_start(); /// initialize session
if (!isset($_SESSION['loggedIn']) || (isset($_SESSION['loggedIn']) && !$_SESSION['loggedIn'])) {
    header("location:login.php");
}
$data = array();
if(isset($_GET['files'])) {
    $error = false;
    $errorFiles = array();
    $files = array();
    $uploaddir = './uploads/'.$_SESSION['user_id'].'/';
    if (!file_exists($uploaddir)) {
        mkdir($uploaddir, 0755, true);
    }
    if (is_dir($uploaddir) && is_writable($uploaddir)) {
        foreach($_FILES as $file) {
            if(move_uploaded_file($file['tmp_name'], $uploaddir.basename($file['name']))) {
                $files[] = $uploaddir .$file['name'];
            } else {
                array_push($errorFiles, $file);
                $error = true;
            }
        }
    } else {
        $error = true;
    }
    if ($error) {
        array_push($errorFiles, 'There was an error uploading your files');
    }
    $data = ($error) ? array('error' => $errorFiles) : array('files' => $files);
} else if(isset($_POST['file']) && isset($_POST['remove']) && $_POST['remove'] == 'true' ){
    $deletingFile = $_POST['file'];
    if (unlink('./uploads/'.$_SESSION['user_id'].'/'.$deletingFile)) {
        $data = array('deleted' => $deletingFile);
    } else {
        throw new Exception('Unable to delete file');
    }
} else {
    $data = array('success' => 'Form was submitted', 'formData' => $_POST);
}
echo json_encode($data);
?>