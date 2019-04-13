<?php
    session_start();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1">
        <link rel="stylesheet" href="../Social_Media_App/css/w3.css">
        <link rel="stylesheet" href="../Social_Media_App/bootstrap/btt.css">
        <link rel="stylesheet" href="../Social_Media_App/css/mybtt.css">
        <link rel="stylesheet" href="../Social_Media_App/css/mycss.css">
        <title>FacebookPost</title>
    </head> 
    <body class="body-style">
        <nav class="navbar navbar-expand-md"style="background-color:  #10707f">
            <div style="text-align: center"
                <ul class="navbar-nav">
                    <li>
                        <div>
                            <b class="myfont w3-opacity" style="font-size: 22px; color: white">FacebookPost</b
                        </div>
                    </li>
                    <li class="nav-link">
                        <a class="nav-item alert-link w3-hover-text-lime" href="Home.php"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 25 30" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            Home
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </body>
</html>

<?php
    require ("../vendor/autoload.php");
    use Facebook\Facebook;
    
    //step 1: Enter credentials
    //utilizzo la libreria dell'SDK
    $fb = new Facebook([
        'app_id' => '646457439148163',
        'app_secret' => 'caae448a3967762864ddcf43a979d514',
        'default_graph_version' => 'v3.2'
    ]);
    
    //se è vuoto significa che non si è loggati su FB
    if(empty($_SESSION['token'])){
        //pulsante rimanda al login facebook
        echo "<a href='{$fb->getRedirectLoginHelper()->getLoginUrl("http://localhost/Social_Media_App/FaceBookPost.php ")}'><img id='btn_image' src='../Social_Media_App/content/login_fb_button.png' width=200 </a>";
    }
    
    //ottengo il token di accesso
    $access_token = $fb->getRedirectLoginHelper()->getAccessToken();

    /*Step 4: Get the graph user*/
    if(isset($access_token) || isset($_SESSION['token'])) {
        try {
            if(isset($access_token)){
                //salvo il token di accesso nella variabile di sessione
                $_SESSION['token'] = $access_token->getValue();
                //ho effettuato l'accesso
                $response = $fb->get('/me', $access_token);
            }else{
                //ho effettuato l'accesso
                $response = $fb->get('/me', $_SESSION['token']);
            }
            
            //verifico se la variabile Id utente è settata
            //in caso affermativo ho effettuato l'accesso a FB quindi
            //posso togliere il pulsante di accesso utilizzo una form
            //per l'eventuale logout
            if(isset($_SESSION['token'])){
                echo"<script>document.getElementById('btn_image').style.display='none'</script>";
                echo"<form action='FaceBookPost.php' method='post'>";
                    echo"<button type='submit' name='sub' class='btn btn-info' value=".$_SESSION['token'].">FacebookLogout</button>";
                echo"</form>";
            
                // getting all posts published by user
                if(empty($_SESSION['post']) || empty($_POST['sub'])){
                    $posts_request= $fb->get('/me?fields=id,name,posts', $_SESSION['token']);
                    //ottengo il body della richiesta. ovvero i post
                    $_SESSION['post'] = $posts_request->getBody(); 
                    print_r($_SESSION['post']);
                }          
            }
                    
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            echo  'Graph returned an error: ' . $e->getMessage();
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SD returned an error: ' . $e->getMessage();
        }
    }
    
    //Se ho premuto il pulsante di logout lo effettuo automaticamente su FB
    if(isset($_POST['sub'])){
        $url = 'https://www.facebook.com/logout.php?next=http://localhost/Social_Media_App/Home.php&access_token='.$_POST['sub'];
        session_destroy();
        header('Location: '.$url);
        echo"<script>document.getElementById('btn_image').style.visibility='visible'</script>";
    }
