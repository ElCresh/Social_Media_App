<?php
session_start();
require '../database_query/db_conn_query.php';
require '../parsing_date/parsing_date.php';
require ("../vendor/autoload.php");
use Facebook\Facebook;

db_select("zpj83vpaccjer3ah.chr7pe7iynqr.eu-west-1.rds.amazonaws.com", "qu660m0yzr7x5pjv", "rwwj6y6awue2yjis", "gw9d5a897do2l0p4");
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
        <title>FacebookTimeline</title>
    </head> 
    <body>
        <nav class="navbar navbar-expand-xl"style="background-color:  #10707f">
            <table>
                <tr>
                    <td style="border-right: solid 1px lightgray; padding-right: 20px; padding-left: 15px">
                        <b class="myfont w3-opacity" style="font-size: 25px; color: white">FacebookHome</b>
                    </td>
                    <td style="padding-left: 20px">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px">
                            Menu
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" >
                            <a class="dropdown-item" href="../Home/Home.php">View all posts time-line</a>
                            <a class="dropdown-item" href="../TwitterTimeLine/TwitterTimeline.php">View Twitter posts time-line</a>
                            <a class="dropdown-item" href="../InstragramTimeLine/InstragramTimeline.php">View Instragram posts time-line</a>
                        </div>
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
                <h1 class="myfont">Facebook-Timeline</h1>
            </div>
            <ul class="timeline">
                <?php
                //ricerco i post da stampare sulla time-line
                $risultato = getFbPost($_SESSION['user_data']['id_client']);
                $i=0;
                while ($_SESSION['riga']=mysqli_fetch_assoc($risultato)){
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
                    <div class="timeline-badge primary"><i> <svg xmlns="http://www.w3.org/2000/svg" width="25" height="50" viewBox="3 -8 20 40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3v2h-2c-0.269 0-0.528 0.054-0.765 0.152-0.246 0.102-0.465 0.25-0.649 0.434s-0.332 0.404-0.434 0.649c-0.098 0.237-0.152 0.496-0.152 0.765v3c0 0.552 0.448 1 1 1h2.719l-0.5 2h-2.219c-0.552 0-1 0.448-1 1v7h-2v-7c0-0.552-0.448-1-1-1h-2v-2h2c0.552 0 1-0.448 1-1v-3c0-0.544 0.108-1.060 0.303-1.529 0.202-0.489 0.5-0.929 0.869-1.299s0.81-0.667 1.299-0.869c0.469-0.195 0.985-0.303 1.529-0.303zM18 1h-3c-0.811 0-1.587 0.161-2.295 0.455-0.735 0.304-1.395 0.75-1.948 1.303s-0.998 1.212-1.302 1.947c-0.294 0.708-0.455 1.484-0.455 2.295v2h-2c-0.552 0-1 0.448-1 1v4c0 0.552 0.448 1 1 1h2v7c0 0.552 0.448 1 1 1h4c0.552 0 1-0.448 1-1v-7h2c0.466 0 0.858-0.319 0.97-0.757l1-4c0.134-0.536-0.192-1.079-0.728-1.213-0.083-0.021-0.167-0.031-0.242-0.030h-3v-2h3c0.552 0 1-0.448 1-1v-4c0-0.552-0.448-1-1-1z"></path></svg></i></div> 
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="timeline-title"></h4><!--utilizzare il nome per timeline-->
                            <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?php print_r($_SESSION['riga']['date_time']) ?></small></p>
                        </div>
                        <!-- la proprietà word-wrap consente di spezzare le parole ed andare a capo quando occorre -->
                        <div class="timeline-body" style="word-wrap: break-word;">
                            <?php 
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
                            ?></div>
                    </div>
                <?php
                $i++;
                }       
                ?></ul>
        </div>
        <?php
