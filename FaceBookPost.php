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
            <!-- Brand -->
            <div style="text-align: center"
                <!-- Toggler/collapsibe Button -->
                <!-- Navbar links -->
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
    session_start();

    if(isset($_GET['state'])){
        $_SESSION['FBRLM_state'] = $_GET['state'];
    }

    //step 1: Enter credentials
    //utilizzo la libreria dell'SDK
    $fb = new \Facebook\Facebook([
        'app_id' => '2184553708460493',
        'app_secret' => '5d379d25642c74400ca573ee37173477',
        'default_graph_version' => 'v3.2'
        //'default_access_token => {access-token}, //optional'
    ]);

    /*Step 2 Create the url*/
    if(empty($access_token)) {
        //riferimento al login facebook
        echo "<a href='{$fb->getRedirectLoginHelper()->getLoginUrl("http://localhost/FaceBookLoginProva/facebook_login.php ")}'><img src='../FaceBookLoginProva/Immagini/login_fb_button.png' width=200 </a>";
    }

    /*Step 3 : Get Access Token*/
    $access_token = $fb->getRedirectLoginHelper()->getAccessToken();


    /*Step 4: Get the graph user*/
    if(isset($access_token)) {
        try {
            $response = $fb->get('/me',$access_token);

            $fb_user = $response->getGraphUser();
            
            //restituisce il nome di chi ha fatto il login
            echo  $fb_user->getName();

        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            echo  'Graph returned an error: ' . $e->getMessage();
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
    }
}
?>
