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
        <title>InstagramTimeline</title>
    </head> 
    <body>
        <nav class="navbar navbar-expand-xl"style="background-color:  #10707f">
            <table>
                <tr>
                    <td style="border-right: solid 1px lightgray; padding-right: 20px; padding-left: 15px">
                        <b class="myfont w3-opacity" style="font-size: 25px; color: white">InstagramHome</b>
                    </td>
                    <td style="padding-left: 20px">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px">
                            Menu
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" >
                            <a class="dropdown-item" href="../Home/Home.php">View all posts time-line</a>
                            <a class="dropdown-item" href="../FacebookTimeLine/FacebookTimeline.php">View Facebook posts time-line</a>
                            <a class="dropdown-item" href="../TwitterTimeLine/TwitterTimeline.php">View Twitter posts time-line</a>
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
                <h1 class="myfont">Instagram-Timeline</h1>
            </div>
            <ul class="timeline">
                <?php
                //ricerco i post da stampare sulla time-line
                $risultato = getIgPost($_SESSION['user_data']['id_client']);
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
                    <div class="timeline-badge danger"><i><svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="25" height="50" viewBox="-1 -1 25 30"><path d="M7 1c-0.811 0-1.587 0.161-2.295 0.455-0.735 0.304-1.395 0.75-1.948 1.302s-0.998 1.213-1.302 1.948c-0.294 0.708-0.455 1.484-0.455 2.295v10c0 0.811 0.161 1.587 0.455 2.295 0.304 0.735 0.75 1.395 1.303 1.948s1.213 0.998 1.948 1.303c0.707 0.293 1.483 0.454 2.294 0.454h10c0.811 0 1.587-0.161 2.295-0.455 0.735-0.304 1.395-0.75 1.948-1.303s0.998-1.213 1.303-1.948c0.293-0.707 0.454-1.483 0.454-2.294v-10c0-0.811-0.161-1.587-0.455-2.295-0.304-0.735-0.75-1.395-1.303-1.948s-1.213-0.998-1.948-1.303c-0.707-0.293-1.483-0.454-2.294-0.454zM7 3h10c0.544 0 1.060 0.108 1.529 0.303 0.489 0.202 0.929 0.5 1.299 0.869s0.667 0.81 0.869 1.299c0.195 0.469 0.303 0.985 0.303 1.529v10c0 0.544-0.108 1.060-0.303 1.529-0.202 0.489-0.5 0.929-0.869 1.299s-0.81 0.667-1.299 0.869c-0.469 0.195-0.985 0.303-1.529 0.303h-10c-0.544 0-1.060-0.108-1.529-0.303-0.489-0.202-0.929-0.5-1.299-0.869s-0.667-0.81-0.869-1.299c-0.195-0.469-0.303-0.985-0.303-1.529v-10c0-0.544 0.108-1.060 0.303-1.529 0.202-0.489 0.5-0.929 0.869-1.299s0.81-0.667 1.299-0.869c0.469-0.195 0.985-0.303 1.529-0.303zM16.989 11.223c-0.15-0.972-0.571-1.857-1.194-2.567-0.383-0.437-0.842-0.808-1.362-1.092-0.503-0.275-1.061-0.465-1.647-0.552-0.464-0.074-0.97-0.077-1.477-0.002-0.668 0.099-1.288 0.327-1.836 0.655-0.569 0.341-1.059 0.789-1.446 1.312s-0.674 1.123-0.835 1.766c-0.155 0.62-0.193 1.279-0.094 1.947s0.327 1.288 0.655 1.836c0.341 0.569 0.789 1.059 1.312 1.446s1.122 0.674 1.765 0.836c0.62 0.155 1.279 0.193 1.947 0.094s1.288-0.327 1.836-0.655c0.569-0.341 1.059-0.789 1.446-1.312s0.674-1.122 0.836-1.765c0.155-0.62 0.193-1.279 0.094-1.947zM15.011 11.517c0.060 0.404 0.037 0.798-0.056 1.168-0.096 0.385-0.268 0.744-0.502 1.059s-0.528 0.584-0.868 0.788c-0.327 0.196-0.698 0.333-1.101 0.393s-0.798 0.037-1.168-0.056c-0.385-0.096-0.744-0.268-1.059-0.502s-0.584-0.528-0.788-0.868c-0.196-0.327-0.333-0.698-0.393-1.101s-0.037-0.798 0.056-1.168c0.096-0.385 0.268-0.744 0.502-1.059s0.528-0.584 0.868-0.788c0.327-0.196 0.698-0.333 1.101-0.393 0.313-0.046 0.615-0.042 0.87-0.002 0.37 0.055 0.704 0.17 1.003 0.333 0.31 0.169 0.585 0.391 0.815 0.654 0.375 0.428 0.63 0.963 0.72 1.543zM18.5 6.5c0-0.552-0.448-1-1-1s-1 0.448-1 1 0.448 1 1 1 1-0.448 1-1z"></path></svg></i></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="timeline-title"></h4><!--utilizzare il nome per timeline-->
                            <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?php print_r($_SESSION['riga']['date_time']) ?></small></p>
                        </div>
                        <!-- la proprietà word-wrap consente di spezzare le parole ed andare a capo quando occorre -->
                        <div class="timeline-body" style="word-wrap: break-word;">
                            <?php $message = json_decode($_SESSION['riga']['body'],true); ?>
                            <p><?= $message["msg"] ?></p><br />
                            <img style="width: 100%" src='<?= $message["img"] ?>' />
                    </div>
                <?php
                $i++;
                }       
                ?></ul>
        </div>
        <?php


