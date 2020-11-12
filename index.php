<?php
    session_start();
?>

<!DOCTYPE html>
<head>
<meta charset="utf-8">
<title>Блог</title>
<link rel="stylesheet" href="css/style.css" />

</head>
<body>
<?php require $_SERVER["DOCUMENT_ROOT"]."/include/code.php"; ?>
<!--wrapper starts-->
<div id="wrapper">
	<!--container starts-->
    	<div id="container">
        	<!--ltcontent starts-->
        	<div id="ltcontent">
            	<!--hdlogo starts-->
                <div id="hdlogo" align="center">
                	<img src="images/topbanner.jpg" alt=""/>
                </div>
                <!--hdlogo ends-->

                <!--login starts-->
                <div id="login">
                	<div class="lthead" align="center"><span class="tphead">Авторизация</span></div>
                    <?php
                        if (isset($_SESSION['userId'])) {
                            echo '<div class="link-text">'.$_SESSION['userUid'].'</div>';
                        }
                        else {
                            echo '
                                <div class="ltdetail">Логин и пароль</div>
                                <form action="include/code.php" method="post" style="display: flex; flex-wrap: wrap;">
                                    <input type="text" name="mailuid" placeholder="E-mail / Никнейм" class="link-field" required>
                                    <input type="password" name="pwd" placeholder="Пароль" class="link-field" required>
                                    <button type="submit" name="login-submit" class="link">Войти</button>
                                </form>';
                        };
                        if (isset($_SESSION['userId'])) echo '
                            <form method="post" style="margin: auto 0px;">
                                <button type="submit" class="link" name="logout-button">Выход</button>
                            </form>
                        ';
                    ?>

                </div>
                 <div class="ltgap"></div>
                 <!--login ends-->

                 <!-- search starts-->
                 <div id="search">
                 	<div class="input">
                 	<input type="text" name="search" class="textbox"/> <input type="submit" value="Поиск" class="searchBut" />
                    </div>
                 </div>

                 <!--search ends-->
            </div>
            <!--ltcontent ends-->

            <!--rtcontent starts-->
            <div id="rtcontent">
                <!--head starts-->
                <div class="head">Блог</div>
                <!--head ends-->

                <!--menulinks starts -->
                <div class="clear"></div>
            <!--menulinks ends -->
            <?php
                loadAddForm();
                if(isset($_POST['page'])){
                    $data=strip_tags($_POST['page']);
                    renderPosts($data,5);
                }
                else {
                    renderPosts(1,5);
                }
            ?>
            <?php
                if(isset($_POST['page'])){
                    $data=strip_tags($_POST['page']);
                    renderPageList($data, 10);
                }
                else {
                    renderPageList(1, 10);
                }
            ?>
            <!--post ends-->
            </div>
            <!--rtcontent ends-->
            <div class="clear"></div>
            <br />

            <!--footer starts-->
            	<div id="footer">
                	<div>
                    &copy; Задание: Блог
                    </div>
              </div>
            <!--footer ends-->
        </div>
    <!--container starts-->
</div>
<!--wrapper ends-->
<script src="/include/code.js"></script>
</body>
</html>
