<?php
session_start();
require '../database_query/db_conn_query.php';
require '../parsing_date/parsing_date.php';
require ("../vendor/autoload.php");
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
        <title>TwitterTimeline</title>
    </head> 
    <body>
        <nav class="navbar navbar-expand-xl"style="background-color:  #10707f">
            <table>
                <tr>
                    <td style="border-right: solid 1px lightgray; padding-right: 20px; padding-left: 15px">
                        <b class="myfont w3-opacity" style="font-size: 25px; color: white">TwitterHome</b>
                    </td>
                    <td style="padding-left: 20px">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px">
                            Menu
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" >
                            <a class="dropdown-item" href="../Home/Home.php">View all posts time-line</a>
                            <a class="dropdown-item" href="../FacebookTimeLine/FacebookTimeline.php">View Facebook posts time-line</a>
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
                <h1 class="myfont">Twitter-Timeline</h1>
            </div>
            <ul class="timeline">
                <?php
                //ricerco i post da stampare sulla time-line
                $risultato = getTwPost($_SESSION['user_data']['id_client']);
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
                    <div class="timeline-badge info"><i><svg xmlns="http://www.w3.org/2000/svg" width="30" height="50" viewBox="-1 -2 35 40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M32 7.075c-1.175 0.525-2.444 0.875-3.769 1.031 1.356-0.813 2.394-2.1 2.887-3.631-1.269 0.75-2.675 1.3-4.169 1.594-1.2-1.275-2.906-2.069-4.794-2.069-3.625 0-6.563 2.938-6.563 6.563 0 0.512 0.056 1.012 0.169 1.494-5.456-0.275-10.294-2.888-13.531-6.862-0.563 0.969-0.887 2.1-0.887 3.3 0 2.275 1.156 4.287 2.919 5.463-1.075-0.031-2.087-0.331-2.975-0.819 0 0.025 0 0.056 0 0.081 0 3.181 2.263 5.838 5.269 6.437-0.55 0.15-1.131 0.231-1.731 0.231-0.425 0-0.831-0.044-1.237-0.119 0.838 2.606 3.263 4.506 6.131 4.563-2.25 1.762-5.075 2.813-8.156 2.813-0.531 0-1.050-0.031-1.569-0.094 2.913 1.869 6.362 2.95 10.069 2.95 12.075 0 18.681-10.006 18.681-18.681 0-0.287-0.006-0.569-0.019-0.85 1.281-0.919 2.394-2.075 3.275-3.394z"></path></svg></i></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="timeline-title"></h4><!--utilizzare il nome per timeline-->
                            <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?php print_r($_SESSION['riga']['date_time']) ?></small></p>
                        </div>
                        <!-- la proprietà word-wrap consente di spezzare le parole ed andare a capo quando occorre -->
                        <div class="timeline-body" style="word-wrap: break-word;">
                            <?php 
                                $stringa = explode('://', $_SESSION['riga']['body']);
                                if($stringa[0]==='http'||$stringa[0]==='https'){
                                    echo "<p><b>Commento assente. Link al post:</b></p><a href=".$riga['body'].">".$riga['body']."</a>";
                                }else{ 
                                    echo"<p>".$_SESSION['riga']['body']."</p>";
                                }        
                            ?></div>
                    </div>
                <?php
                $i++;
                }       
                ?></ul>
        </div>
        <?php


