<?php
require 'connect/db.php';

function login_db() {
    $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", $user, $pass);
    $mailuid = $_POST['mailuid'];
    $password = $_POST['pwd'];

    if (empty($mailuid) || empty($password)){
        die('You forgot put something in labels');
        exit();
    }
    else {
        $data = $pdo->prepare("SELECT * FROM users WHERE uidUsers=? OR emailUsers=?");
        $data->bindParam(1, $mailuid, PDO::PARAM_STR);
        $data->bindParam(2, $mailuid, PDO::PARAM_STR);
        $data->execute();
        
        $arr_data = array();
        while ($row = $data->fetch(PDO::FETCH_ASSOC)){
            $arr_data[count($arr_data)] = $row;
        }
        
        
        if (count($arr_data) < 0) {
            die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Нет пользователей с таким никнеймом или почтой.</br></br><a href="https://thedailypine.ru">Вернуться назад.</a></p></div>');
            exit();
        }
        else {
            $pwdCheck = password_verify($password, $arr_data[0]['pwdUsers']);
            if ($pwdCheck == false) {
                die('<div style="background-color:white;  display: flex; justify-content: center; align-items: center; height: 100%;"><p align="center" style="font-size: 16px; color: gray;">Неправильный пароль. </br></br><a href="https://thedailypine.ru">Вернуться на предыдущую страницу.</a></p></div>');
                exit();
            }
            else if($pwdCheck == true) {
                session_start();
                $_SESSION['userId'] = $arr_data[0]['idUsers'];
                $_SESSION['userUid'] = $arr_data[0]['uidUsers'];

                header("Location: ..?login=success"); 
                exit();
            }
            else {
                die('<div style="background-color:white;  display: flex; justify-content: center; align-items: center; height: 100%;"><p align="center" style="font-size: 16px; color: gray;">Неправильный пароль. </br></br><a href="https://thedailypine.ru">Вернуться на предыдущую страницу.</a></p></div>');
                exit();
            }
        }

        $pdo = null;
    }
        
}

function logout_db() {
        //session_start();
        session_unset();
        session_destroy();
        $content = "four";
        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/REGISTER_LOG.txt","a+");
        fwrite($fp,$content);
        fclose($fp);
        //header("Location: ../Photos.php");
    }

function render_posts() {
    
}

if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['login-submit']))){
    login_db();   
}

if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['logout-button']))){
    logout_db();   
}

function sql_load($table){
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", $user, $pass);
        $data = $pdo->prepare("SELECT * FROM ".$table);
        $data->execute();
        
        $arr_data = array();
        while ($row = $data->fetch(PDO::FETCH_ASSOC))
            $arr_data[count($arr_data)] = $row;
        return $arr_data;
        $pdo = null;
    }
    catch (PDOException $e) {
        // Оставлять логи опасно
        // print "Error!: " . $e->getMessage() . "<br/>";
        // $file = fopen("logfile.log", "a+"); 
        // fwrite($file, "Error!: ".$e->getMessage()."\n"); 
        // fwrite($file, "In file: ".$e->getFile().", line: ".$e->getLine()."\n"); 
        // fclose($file); 
        // return null;
        // die();
    }
}

function renderPosts($page,$n){

    $path = "post_images/";
    $photo_data = sql_load("source_photo_data");

    if (($page*$n) > count($photo_data)) {
        $target = count($photo_data);
    }
    else {
        $target = $page*$n;
    }
    for ($i = (($page-1)*$n); $i < $target; $i++){
        echo '
        <form method="post" enctype="multipart/form-data">
            <div class="document">
                <div>&nbsp;</div>
                <div class="dochead">
                    <span class="dhead">'.$photo_data[$i]['name'].'</span>
                </div>
                <div class="dcontent">
                <img src="/images/app_icon.png">
                    <p>'.$photo_data[$i]['description'].'</p>
                </div>
                <div class="bubble" align="right">
                    <span>#'.$photo_data[$i]['hashtag'].'</span>
                </div>';
                if (isset($_SESSION['userUid'])) {
                    if ($_SESSION['userUid'] == "crithcraft") {
                        echo '<div class="button-label">
                            <input type="text" class="id" name="id" value='.$photo_data[$i]['id'].'>
                            <button type="submit" class="link" onClick="edit_post(this,'.$photo_data[$i]['id'].')">Редактировать</button>
                            <input type="submit" class="link" name="delete-post-button" value="Удалить">
                        </div>';
                    }
                }
        echo '</div>
            <div class="rtboxbg">&nbsp;</div>
        </form>
        ';
    }
}

function renderPageList ($page, $page_size) {
    $photo_data = sql_load("source_photo_data");
    echo '<div id="menulinks">';
    echo '<form action="#" method="post">';
    if ($page >= 3) {
        $start = $page - 2;
        $target = $page + 2;
    }
    else {
        $start = 1;
        $target = 5;
    }
    if ($target*$page_size > count($photo_data)) {
        $target = ((count($photo_data)-1)/$page_size)+1;
    }
    for ($i = $start; $i <= $target; $i++){
        if ($i == $page) {
            echo '<input name="page" type="submit" value="'.$i.'"/>';
        }
        else {
            echo '<input name="page" style = "background-color: #fff; color: gray;" type="submit" value="'.$i.'"/>';
        }
    }
    echo '</form>';
    echo '</div>';
}


function loadAddForm() {
    if (isset($_SESSION['userId'])) {
        if ($_SESSION['userId'] == "crithcraft") {
        echo '<form method="post" enctype="multipart/form-data">
                <div class="document">
                    <div>&nbsp</div>
                    <div class="dochead">
                        <input type="text" name="name" placeholder="Введите имя" required>
                    </div>
                    <div class="dcontent">
                        <img src="/images/app_icon.png">
                        <textarea name="description" placeholder="Введите описание" required></textarea>
                    </div>
                    <div class="bubble" align="right">
                        <input type="text" name="hashtag" placeholder="Введите хэштег" required>
                    </div>
                    <div class="button-label">
                        <input type="submit" class="link" name="add-post-button" value="ОТПРАВИТЬ">
                    </div>
                </div>
            </form>';
        }
    }
}

if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['edit-post-button']))){
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", $user, $pass);
        $php = "UPDATE source_photo_data set name = ?, description = ?, hashtag = ? WHERE id = ?";
        $STH = $pdo->prepare($php);
        $STH->bindParam(1, strip_tags($_POST["name"]), PDO::PARAM_STR);
        $STH->bindParam(2, strip_tags($_POST["description"]), PDO::PARAM_STR);
        $STH->bindParam(3, strip_tags($_POST["hashtag"]), PDO::PARAM_STR);
        $STH->bindParam(4, strip_tags($_POST["id"]), PDO::PARAM_STR);
        $STH->execute();
        $pdo = null;
        die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Загрузка удачна.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
    }
    catch(Exception $e){
        die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Что-то пошло не так.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
    }
}

if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['delete-post-button']))){
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", $user, $pass);
        $php = "DELETE FROM source_photo_data WHERE id = ?";
        $STH = $pdo->prepare($php);
        $STH->bindParam(1, strip_tags($_POST["id"]), PDO::PARAM_STR);
        $STH->execute();
        $pdo = null;
        die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Пост удален.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
    }
    catch(Exception $e){
        die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Что-то пошло не так.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
    }
}

if (($_SERVER['REQUEST_METHOD'] == 'POST')&&(isset($_POST['add-post-button']))){
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=pineforest_db;charset=utf8", $user, $pass);
        $php = "INSERT INTO `source_photo_data` (`id`, `name`, `description`, `hashtag`) VALUES (NULL,?,?,?);";
        $STH = $pdo->prepare($php);
        $STH->bindParam(1, strip_tags($_POST["name"]), PDO::PARAM_STR);
        $STH->bindParam(2, strip_tags($_POST["description"]), PDO::PARAM_STR);
        $STH->bindParam(3, strip_tags($_POST["hashtag"]), PDO::PARAM_STR);
        $STH->execute();
        $pdo = null;
        die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Пост добавлен.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
    }
    catch(Exception $e){
        die('<div style="background-color:white; grid-area:1/1/5/6; margin: auto 0;"><p align="center" style="font-size: 16px; color: gray;">Что-то пошло не так.</br></br><a href="?">Вернуться на страницу загрузки.</a></p></div>');
    }
}

?>