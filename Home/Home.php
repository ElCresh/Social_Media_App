<?php
session_start();
require_once ('../database_query/db_conn_query.php');
require_once ('../parsing_date/parsing_date.php');
require_once ("../vendor/autoload.php");
require_once ("../functions.php");
require_once ("../settings.php");

use Facebook\Facebook;

db_select("localhost", "root", "", "socialmediadata");

// Verifico il refresh di Instragram
if(isset($_REQUEST['ig_reload'])){
    $data = getInstagramPost($_REQUEST['ig_name'],$_REQUEST['ig_password']);
    if(!empty($data)){
        $id_social = 3;
        $user_id = getUserID($_SESSION['email']);
        $i=0;

        delIgPost($user_id);

        foreach($data as $ig_post){
            $post_data = ['body'=> addslashes(json_encode($ig_post)),
                        'id_post'=>$i,
                        'id_social'=>$id_social,
                        'timestamp'=>date("Y-m-d H:i:s", $ig_post['date']),
                        'userID'=>$user_id];
            insert_post_data($post_data);
            $i++;
        }
        
    }
}

//eleboro i dati che sono stati inseriti nella registrazione e li inserisco nel db
//verifico che siano stati settati almeno uno dei tre social, che non sia stato premuto il bottone accedi
//e che sia la prima volta che accedo al db per caricarlo

if((isset($_SESSION['facebook_id']) || isset($_SESSION['twitter_id']) || isset($_SESSION['instagram_id_ok'])) && !isset($_SESSION['btnAccedi']) && !isset($_SESSION['inserito'])){
    if(!$_SESSION['twitter_id_ok'] && $_SESSION['facebook_id_ok']){
        //twitter non è stato inserito come social
        //inserisco prima i valori relativi alla tabella tb_clienti
        $client_data = ['facebook_id'=>$_SESSION['facebook_id'], 
            'twitter_id'=>false, 
            'email'=>$_SESSION['email'],
            'password'=>$_SESSION['password'],
            'facebook_name'=>$_SESSION['facebook_name']];
        insert_daticliente_db($client_data);
        $_SESSION['inserito'] = true;
    //facebook non inserito come social    
    }elseif(!$_SESSION['facebook_id_ok'] && $_SESSION['twitter_id_ok']){
        //facebook non inserito come social
        $client_data = ['facebook_id'=>false, 
            'twitter_id'=>$_SESSION['twitter_id'], 
            'email'=>$_SESSION['email'],
            'password'=>$_SESSION['password'],
            'twitter_name'=>$_SESSION['twitter_name']];
        insert_daticliente_db($client_data);
        $_SESSION['inserito'] = true;
    //altrimenti sono stati settati tutti    
    }elseif($_SESSION['facebook_id_ok'] && $_SESSION['twitter_id_ok']){
        //altrimenti sono stati settati tutti
        //inserisco prima i valori relativi alla tabella tb_clienti
        $client_data = ['facebook_id'=>$_SESSION['facebook_id'], 
                        'twitter_id'=>$_SESSION['twitter_id'], 
                        'email'=>$_SESSION['email'],
                        'password'=>$_SESSION['password'],
                        'twitter_name'=>$_SESSION['twitter_name'],
                        'facebook_name'=>$_SESSION['facebook_name']];
        insert_daticliente_db($client_data);
        $_SESSION['inserito'] = true;
    }

    //se ho settato facebook scarico i dati relativi ai post nel db
    if($_SESSION['facebook_id_ok']){
        $i = 0;
        //print_r($_SESSION['facebook_data']);
        //ricerco i valori per i post di facebook e li inserisco in tb_post   
        while(isset($_SESSION['facebook_data']['data'][$i])){
            //se è presente il messaggio e la foto
                            if(isset($_SESSION['facebook_data']['data'][$i]['message'])&&isset($_SESSION['facebook_data']['data'][$i]['full_picture'])){
                                //metto nel body entrambi 
                                $body=$_SESSION['facebook_data']['data'][$i]['message']."-pe-".$_SESSION['facebook_data']['data'][$i]['permalink_url']."-fo-".$_SESSION['facebook_data']['data'][$i]['full_picture'];
                            //ho solo permalink e foto    
                            }elseif(isset($_SESSION['facebook_data']['data'][$i]['permalink_url'])&&isset($_SESSION['facebook_data']['data'][$i]['full_picture'])){
                                $body='null'.'-pe-'.$_SESSION['facebook_data']['data'][$i]['permalink_url'].'-fo-'.$_SESSION['facebook_data']['data'][$i]['full_picture'];
                            //ho solo il messaggio ma non la foto    
                            }elseif(isset($_SESSION['facebook_data']['data'][$i]['message'])){
                                $body=$_SESSION['facebook_data']['data'][$i]['message'].'-pe-'.$_SESSION['facebook_data']['data'][$i]['permalink_url'].'-fo-'.'null';
                            }else{
                                $body='null'.'-pe-'.$_SESSION['facebook_data']['data'][$i]['permalink_url'].'-fo-'.'null';
                            }
                            
            $id_post=$_SESSION['facebook_data']['data'][$i]['id'];
            $id_social = 1;
            $timestamp = parsing_facebook($_SESSION['facebook_data']['data'][$i]['created_time']);
            $user_id = getUserID($_SESSION['email']);
            $post_data = ['body'=> addslashes($body),
                            'id_post'=>$id_post,
                            'id_social'=>$id_social,
                            'timestamp'=>$timestamp,
                            'userID'=>$user_id];
            
            insert_post_data($post_data);   
            $i++;
        }
    }
    //se ho settato twitter 
    if($_SESSION['twitter_id_ok']){
        $i=0;
        while(isset($_SESSION['twitter_data'][$i])){
            //se il testo è presente
            if(isset($_SESSION['twitter_data'][$i]['text'])){
                $body=$_SESSION['twitter_data'][$i]['text'];
            }
            else{
                $body="Testo non inserito nel post";
            }
            $id_post=$_SESSION['twitter_data'][$i]['id_str'];
            $id_social = 2;
            $timestamp = parsing_twitter($_SESSION['twitter_data'][$i]['created_at']);

            $user_id = getUserID($_SESSION['email']);
            $post_data = ['body'=> addslashes($body),
                            'id_post'=>$id_post,
                            'id_social'=>$id_social,
                            'timestamp'=>$timestamp,
                            'userID'=>$user_id];
            insert_post_data($post_data);  
            $i++;
        }
    }

    if(isset($_SESSION['instagram_id_ok']) && isset($_SESSION['ig_data'])){
        $id_social = 3;
        $user_id = getUserID($_SESSION['email']);
        $i=0;

        foreach($_SESSION['ig_data'] as $ig_post){
            $post_data = ['body'=> addslashes(json_encode($ig_post)),
                        'id_post'=>$i,
                        'id_social'=>$id_social,
                        'timestamp'=>date("Y-m-d H:i:s", $ig_post['date']),
                        'userID'=>$user_id];
            insert_post_data($post_data);
            $i++;
        }
    }
}
//prelevo userID, fb_name e twitter_name
$_SESSION['user_data'] = getUserData($_SESSION['email']);
//separo il firstName da lastName
$FbName = explode(" ", $_SESSION['user_data']['facebook_name']);
//se ho premuto il bottone accedi oppure la registrazione è andata a buon fine sono nella schermata Home
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1">
        <link rel="stylesheet" href="../css/w3.css">
        <link rel="stylesheet" href="../css/mycss.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Popper JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="../css/timeline.css">
        <link rel="stylesheet" href="../css/popin.css">
        <link rel="icon" href="../content/social-image.ico" />
        <title>Home</title>
    </head> 
    <body>
        <nav class="navbar navbar-expand-xl"style="background-color:  #10707f">
            <table>
                <tr>
                    <td style="border-right: solid 1px lightgray; padding-right: 20px; padding-left: 15px">
                        <b class="myfont w3-opacity" style="font-size: 25px; color: white">Home</b>
                    </td>
                    <td style="padding-left: 20px">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px">
                            Menu
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" >
                            <a class="dropdown-item" href="../FacebookTimeLine/FacebookTimeline.php">View FaceBook posts time-line</a>
                            <a class="dropdown-item" href="../TwitterTimeLine/TwitterTimeline.php">View Twitter posts time-line</a>
                            <a class="dropdown-item" href="../InstragramTimeLine/InstragramTimeline.php">View Instragram posts time-line</a>
                        </div>
                    </td>
                    <td>
                        <form name="reload" action="Home.php" method="POST" style="margin: 0;">
                            <button type="submit" title="click me to upload new posts" id="reload" name="reload" value="submit" class="btn-success w3-hover-light-blue" style="border-style: none; height: 35px; border-radius: 4px; font-size: 15px">Reload!</button>
                        </form>
                    </td>
                    <td style="text-align: right" class="container">
                        <a class="nav-item alert-link w3-hover-text-lime" style="font-size: 15px; margin-right: 50px" href="../LoginRegistrazione/Login.php?home"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 25 30" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            LogOut
                        </a>
                    </td>
                </tr>
            </table>                   
        </nav>
        <div class="container" id="timeline" style = "margin-top: 0">
            <div class="page-header">
                <h1 class="myfont">Hi <?php echo $FbName[0] ?>, here your Posts-Timeline</h1>
            </div>
            <ul class="timeline">
                <?php
                //ricerco i post da stampare sulla time-line
                $risultato = getAllPost($_SESSION['user_data']['id_client']);
                $i=0;
                while ($_SESSION['riga'] = mysqli_fetch_assoc($risultato)){
                ?>
                    <?php
                    //gli indici pari metto i post a sinistra
                    //i dispari a destra
                    if($i%2===0){
                        echo"<li>";
                    }else{
                        echo"<li class='timeline-inverted'>";
                    }
                    ?>
                    <?php
                    //se il social è facebook il colore è arancione con il rispettivo simbolo
                    if($_SESSION['riga']['social']==1){
                    ?><div class="timeline-badge primary"><i> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="50" viewBox="3 -8 20 40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3v2h-2c-0.269 0-0.528 0.054-0.765 0.152-0.246 0.102-0.465 0.25-0.649 0.434s-0.332 0.404-0.434 0.649c-0.098 0.237-0.152 0.496-0.152 0.765v3c0 0.552 0.448 1 1 1h2.719l-0.5 2h-2.219c-0.552 0-1 0.448-1 1v7h-2v-7c0-0.552-0.448-1-1-1h-2v-2h2c0.552 0 1-0.448 1-1v-3c0-0.544 0.108-1.060 0.303-1.529 0.202-0.489 0.5-0.929 0.869-1.299s0.81-0.667 1.299-0.869c0.469-0.195 0.985-0.303 1.529-0.303zM18 1h-3c-0.811 0-1.587 0.161-2.295 0.455-0.735 0.304-1.395 0.75-1.948 1.303s-0.998 1.212-1.302 1.947c-0.294 0.708-0.455 1.484-0.455 2.295v2h-2c-0.552 0-1 0.448-1 1v4c0 0.552 0.448 1 1 1h2v7c0 0.552 0.448 1 1 1h4c0.552 0 1-0.448 1-1v-7h2c0.466 0 0.858-0.319 0.97-0.757l1-4c0.134-0.536-0.192-1.079-0.728-1.213-0.083-0.021-0.167-0.031-0.242-0.030h-3v-2h3c0.552 0 1-0.448 1-1v-4c0-0.552-0.448-1-1-1z"></path></svg></i></div> 
                    <?php
                    }elseif($_SESSION['riga']['social']==2){
                    ?><div class="timeline-badge info"><i><svg xmlns="http://www.w3.org/2000/svg" width="30" height="50" viewBox="-1 -2 35 40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M32 7.075c-1.175 0.525-2.444 0.875-3.769 1.031 1.356-0.813 2.394-2.1 2.887-3.631-1.269 0.75-2.675 1.3-4.169 1.594-1.2-1.275-2.906-2.069-4.794-2.069-3.625 0-6.563 2.938-6.563 6.563 0 0.512 0.056 1.012 0.169 1.494-5.456-0.275-10.294-2.888-13.531-6.862-0.563 0.969-0.887 2.1-0.887 3.3 0 2.275 1.156 4.287 2.919 5.463-1.075-0.031-2.087-0.331-2.975-0.819 0 0.025 0 0.056 0 0.081 0 3.181 2.263 5.838 5.269 6.437-0.55 0.15-1.131 0.231-1.731 0.231-0.425 0-0.831-0.044-1.237-0.119 0.838 2.606 3.263 4.506 6.131 4.563-2.25 1.762-5.075 2.813-8.156 2.813-0.531 0-1.050-0.031-1.569-0.094 2.913 1.869 6.362 2.95 10.069 2.95 12.075 0 18.681-10.006 18.681-18.681 0-0.287-0.006-0.569-0.019-0.85 1.281-0.919 2.394-2.075 3.275-3.394z"></path></svg></i></div>
                    <?php
                    }elseif($_SESSION['riga']['social'] == 3){
                    ?><div class="timeline-badge danger"><i><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="25" height="50" viewBox="-1 -1 25 30"><path d="M7 1c-0.811 0-1.587 0.161-2.295 0.455-0.735 0.304-1.395 0.75-1.948 1.302s-0.998 1.213-1.302 1.948c-0.294 0.708-0.455 1.484-0.455 2.295v10c0 0.811 0.161 1.587 0.455 2.295 0.304 0.735 0.75 1.395 1.303 1.948s1.213 0.998 1.948 1.303c0.707 0.293 1.483 0.454 2.294 0.454h10c0.811 0 1.587-0.161 2.295-0.455 0.735-0.304 1.395-0.75 1.948-1.303s0.998-1.213 1.303-1.948c0.293-0.707 0.454-1.483 0.454-2.294v-10c0-0.811-0.161-1.587-0.455-2.295-0.304-0.735-0.75-1.395-1.303-1.948s-1.213-0.998-1.948-1.303c-0.707-0.293-1.483-0.454-2.294-0.454zM7 3h10c0.544 0 1.060 0.108 1.529 0.303 0.489 0.202 0.929 0.5 1.299 0.869s0.667 0.81 0.869 1.299c0.195 0.469 0.303 0.985 0.303 1.529v10c0 0.544-0.108 1.060-0.303 1.529-0.202 0.489-0.5 0.929-0.869 1.299s-0.81 0.667-1.299 0.869c-0.469 0.195-0.985 0.303-1.529 0.303h-10c-0.544 0-1.060-0.108-1.529-0.303-0.489-0.202-0.929-0.5-1.299-0.869s-0.667-0.81-0.869-1.299c-0.195-0.469-0.303-0.985-0.303-1.529v-10c0-0.544 0.108-1.060 0.303-1.529 0.202-0.489 0.5-0.929 0.869-1.299s0.81-0.667 1.299-0.869c0.469-0.195 0.985-0.303 1.529-0.303zM16.989 11.223c-0.15-0.972-0.571-1.857-1.194-2.567-0.383-0.437-0.842-0.808-1.362-1.092-0.503-0.275-1.061-0.465-1.647-0.552-0.464-0.074-0.97-0.077-1.477-0.002-0.668 0.099-1.288 0.327-1.836 0.655-0.569 0.341-1.059 0.789-1.446 1.312s-0.674 1.123-0.835 1.766c-0.155 0.62-0.193 1.279-0.094 1.947s0.327 1.288 0.655 1.836c0.341 0.569 0.789 1.059 1.312 1.446s1.122 0.674 1.765 0.836c0.62 0.155 1.279 0.193 1.947 0.094s1.288-0.327 1.836-0.655c0.569-0.341 1.059-0.789 1.446-1.312s0.674-1.122 0.836-1.765c0.155-0.62 0.193-1.279 0.094-1.947zM15.011 11.517c0.060 0.404 0.037 0.798-0.056 1.168-0.096 0.385-0.268 0.744-0.502 1.059s-0.528 0.584-0.868 0.788c-0.327 0.196-0.698 0.333-1.101 0.393s-0.798 0.037-1.168-0.056c-0.385-0.096-0.744-0.268-1.059-0.502s-0.584-0.528-0.788-0.868c-0.196-0.327-0.333-0.698-0.393-1.101s-0.037-0.798 0.056-1.168c0.096-0.385 0.268-0.744 0.502-1.059s0.528-0.584 0.868-0.788c0.327-0.196 0.698-0.333 1.101-0.393 0.313-0.046 0.615-0.042 0.87-0.002 0.37 0.055 0.704 0.17 1.003 0.333 0.31 0.169 0.585 0.391 0.815 0.654 0.375 0.428 0.63 0.963 0.72 1.543zM18.5 6.5c0-0.552-0.448-1-1-1s-1 0.448-1 1 0.448 1 1 1 1-0.448 1-1z"></path></svg></i></div>
                    <?php
                    }
                    ?><div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="timeline-title"></h4><!--utilizzare il nome per timeline-->
                            <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?php print_r($_SESSION['riga']['date_time']) ?></small></p>
                        </div>
                        <!-- la proprietà word-wrap consente di spezzare le parole ed andare a capo quando occorre -->
                        <div class="timeline-body" style="word-wrap: break-word;">
                            <?php 
                            if($_SESSION['riga']['social']==1){
                                $stringa = explode('-pe-', $_SESSION['riga']['body']);
                                $message = $stringa[0];
                                $stringa1 = explode('-fo-', $stringa[1]);
                                $permalink = $stringa1[0];
                                $foto = $stringa1[1];
                                
                                //verifico se messaggio e foto sono assenti allora stampo solo il permalink
                                if($message==='null'&&$foto==='null'){
                                    echo "<p><b>Absent comment. Link to the post:</b></p><a href=".$permalink.">".$permalink."</a>";
                                //se solo il messaggio è assente stampo foto e permalink
                                }elseif($message==='null'){ 
                                     echo "<p><b>Absent comment. Link to the post:</b></p><a href=".$permalink.">".$permalink."</a>";echo"<br>";echo"<br>";
                                     echo"<img src='".$foto."' alt='Post Image' style='height: 12.9vw; width: 12.6vw'>";
                                //assente la foto                              
                                }elseif($foto==='null'){
                                    echo $message;echo"<br>";
                                    
                                //ho tutti(foto, permalink e messaggio)    
                                }else{
                                    $stringa = explode('://', $message);
                                        if($stringa[0]==='http'||$stringa[0]==='https'){
                                            echo "<p><b>Commento assente. Link al post:</b></p><a href=".$message.">".$message."</a>";echo"<br>";echo"<br>";
                                        }else{
                                           print_r($message);echo"<br>";echo"<br>"; 
                                        }          
                                    echo "<a href=".$permalink.">".$permalink."</a>";echo"<br>";echo"<br>";
                                    echo"<img src='".$foto."' alt='Post Image' style='height: 12.9vw; width: 12.6vw'>";
                                }
                            }elseif($_SESSION['riga']['social']==2){
                                $stringa = explode('://', $_SESSION['riga']['body']);
                                if($stringa[0]==='http'||$stringa[0]==='https'){
                                    echo "<p><b>Commento assente. Link al post:</b></p><a href=".$riga['body'].">".$riga['body']."</a>";
                                }else{ 
                                    echo"<p>".$_SESSION['riga']['body']."</p>";
                                } 
                            }elseif($_SESSION['riga']['social']==3){
                                $message = json_decode($_SESSION['riga']['body'],true);
                            ?>
                                <p><?= $message["msg"] ?></p><br />
                                <img style="width: 100%" src='<?= $message["img"] ?>' />
                            <?php }?>
                        </div>
                    </div>
                <?php
                $i++;
                }       
                ?></ul>
        </div>
        <?php
        //se ho premuto il pulsante reaload o ritorno dal pulsante di facebook carico id db gli ultimi post di tutti i social
        if(isset($_POST['reload']) || isset($_GET['code'])){
            $fb = new Facebook([
                'app_id' => $fb_app_id,
                'app_secret' => $fb_app_secret,
                'default_graph_version' => 'v3.2'
            ]);
            //verifico a quali social è iscritto l'utente. Solo la prima volta
            if(!isset($_SESSION['riga'])){
                //quando entro nel popin oscuro la timeline
                ?> 
                <script>
                    document.getElementById('timeline').style.display='none';
                </script>
                <?php

                $query_social = ("select facebook_id, twitter_id from tb_clienti where email = '".$_SESSION['email']."'");  
                $iscrizione_social = query($query_social);      
                $_SESSION['riga'] = mysqli_fetch_assoc($iscrizione_social);
            }
            //se sono un utente facebook allora richiedo il login
            if(isset($_SESSION['riga']['facebook_id'])&&($_SESSION['riga']['facebook_id']!=null)){
                //se non è settato il token di accesso e non arrivo dal pulsante accedi allora non richido il login
                if(!isset($_GET['code']) && !(isset($_SESSION['fb_token']))){
                ?>
                <div class="popin">    
                    <input id="popin_check_1" type="checkbox" class="popin_check" hidden checked="true" />
                    <label class="layer" for="popin_check_1"></label>
                    <div class="content fadeIn">
                        <p style="color: red"><b>Effettua l'accesso su facebook:</b></p>
                        <?php
                        echo"<a href='{$fb->getRedirectLoginHelper()->getLoginUrl($app_url."Home/Home.php")}'><img  id='btn_image' src='../content/login_fb_button.png' width=200 class='hoverable' style='position:absolute; margin-left:10%'></a>";
                        ?>
                    <label class="modal-button close" for="popin_check_1" onclick="closeFunction()">Close</label>          
                    </div>
                </div>
                <?php
                //ritorno dall'accesso facebook
                }else{
                    try {
                        //alla prima richiesta setto il token
                        if(!isset($_SESSION['fb_token'])){
                            //setto il token di accesso con il suo valore
                            $_SESSION['fb_token'] = $fb->getRedirectLoginHelper()->getAccessToken()->getValue();
                            //ho effettuato l'accesso
                            $_SESSION['fb_id'] = $fb->get('/me', $_SESSION['fb_token'])->getGraphUser()->getId();       
                        }    
                        //seleziono la data dell'ultimo post facebook dal db
                        $query_fb_last_post = ('select date_time as last_post from tb_post where social = 1 group by (date_time) desc limit 1');
                        $result = query($query_fb_last_post);
                        $last_post_time=mysqli_fetch_assoc($result);

                        $date_time=explode(" ", $last_post_time['last_post']);
                        
                        $date = explode('-', $date_time[0]);
                        $time = explode(':', $date_time[1]);
                        $timestamp=mktime(($time[0]+02), $time[1], $time[2], $date[1], $date[2], $date[0]);       
                        //richiamo l'API per ricevere post successivi a quelli salvati nel db
                        $posts_request= json_decode($fb->get("me?fields=feed.since(".$timestamp."){message, created_time, id,permalink_url, full_picture}&date_format=U", $_SESSION['fb_token'])->getBody(), true);
                        //parto a raccogliere i post dal primo fino a quello che ho già salvato
                        $i=0;
                        
                        //ricerco i valori per i post di facebook e li inserisco in tb_post
                        while(isset($posts_request['feed']['data'][$i])){
                            //se è presente il messaggio e la foto
                            if(isset($posts_request['feed']['data'][$i]['message'])&&isset($posts_request['feed']['data'][$i]['full_picture'])){
                                //metto nel body entrambi 
                                $body=$posts_request['feed']['data'][$i]['message']."-pe-".$posts_request['feed']['data'][$i]['permalink_url']."-fo-".$posts_request['feed']['data'][$i]['full_picture'];
                            //ho solo permalink e foto    
                            }elseif(isset($posts_request['feed']['data'][$i]['permalink_url'])&&isset($posts_request['feed']['data'][$i]['full_picture'])){
                                $body='null'.'-pe-'.$posts_request['feed']['data'][$i]['permalink_url'].'-fo-'.isset($posts_request['feed']['data'][$i]['full_picture']);
                            //ho solo il messaggio ma non la foto    
                            }elseif(isset($posts_request['feed']['data'][$i]['message'])){
                                $body=$posts_request['feed']['data'][$i]['message'].'-pe-'.$posts_request['feed']['data'][$i]['permalink_url'].'-fo-'.'null';
                            }else{
                                $body='null'.'-pe-'.$posts_request['feed']['data'][$i]['permalink_url'].'-fo-'.'null';
                            }
                            $id_post=$posts_request['feed']['data'][$i]['id'];
                            $id_social = 1;  

                            $user_id = getUserID($_SESSION['email']);

                            $post_data = ['body'=> addslashes($body),
                                          'id_post'=>$id_post,
                                          'id_social'=>$id_social,
                                          'timestamp'=>date('Y-m-d H:i:s',$posts_request['feed']['data'][$i]['created_time']),
                                          'userID'=>$user_id];
                            insert_post_data($post_data);   
                            $i++;
                        }
                        ?>
                        <script>document.getElementById('timeline').style.display='block';</script>
                        <?php
                    } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                        echo  'Graph returned an error: ' . $e->getMessage();
                    } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                        echo $e;                  
                    }
                }
            }
            //se utilizzo twitter come social
            if(isset($_SESSION['riga']['twitter_id'])&&($_SESSION['riga']['twitter_id']!=null)){
                //seleziono la data dell'ultimo post facebook dal db
                $query_tw_last_post = ('select id as last_post from tb_post where social = 2 group by (date_time) desc limit 1');
                $result = mysqli_fetch_assoc(query($query_tw_last_post));
                /** Set access token here - see: https://dev.twitter.com/apps/ **/
                $settings = array(
                    'oauth_access_token' => $tw_oauth_access_token,
                    'oauth_access_token_secret' => $tw_oauth_access_token_secret,
                    'consumer_key' => $tw_consumer_key,
                    'consumer_secret' => $tw_consumer_secret
                );
                $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
                $requestMethod = "GET";
                $_SESSION['twitter_name']= preg_replace("/[^A-Za-z0-9_]/", '', $_SESSION['twitter_name']);
                $getfield = "?screen_name=".$_SESSION['user_data']['twitter_name']."&since_id=".$result['last_post']."";
                $twitter = new TwitterAPIExchange($settings);
                //convert to an associative array
                $_SESSION['twitter_data']=json_decode($twitter->setGetfield($getfield)
                    ->buildOauth($url, $requestMethod)
                    ->performRequest(),$assoc = TRUE);
                $i=0;
                while(isset($_SESSION['twitter_data'][$i])){
                //se il testo è presente
                    if(isset($_SESSION['twitter_data'][$i]['text'])){
                    $body=$_SESSION['twitter_data'][$i]['text'];
                }
            else{
                $body="Testo non inserito nel post";
            }
            $id_post=$_SESSION['twitter_data'][$i]['id_str'];
            $id_social = 2;
            $timestamp = parsing_twitter($_SESSION['twitter_data'][$i]['created_at']);
            $user_id = getUserID($_SESSION['email']);
            $post_data = ['body'=> addslashes($body),
                          'id_post'=>$id_post,
                          'id_social'=>$id_social,
                          'timestamp'=>$timestamp,
                          'userID'=>$user_id];
            insert_post_data($post_data);  
            $i++;
        } ?>
        <div id='inst_connection' style="padding-left: 20px;">
            <h3>Inserisci le tue credenziali Instagram per aggiornare i dati:</h3>
            <form class='form-inline' name = 'get_ig' action = 'Home.php' method = 'POST'>
                <div class='form-group'>
                    <input type='text' class='form-control' id='ig_name' name='ig_name' placeholder='Username'>
                    <input type='password' class='form-control' id='ig_password' name='ig_password' placeholder='Password'>
                    <input type='hidden' name="ig_reload" value="1" />
                </div>
                <div>
                    <button type='submit' name='btnTwit' class='btn btn-light w3-hover-yellow' style='border-style: solid; border-color: lightgrey; background-color: greenyellow; color: blue'>Submit</button>
                </div>
            </form>
        </div>
        
    <?php }
}
        ?> 
        <script type="text/javascript">
            function closeFunction(){
                document.getElementById('timeline').style.display='block';
            }
        </script>
    </body>
</html>