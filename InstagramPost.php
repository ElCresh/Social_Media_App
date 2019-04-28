<?php
    session_start();

    // Loading composer packages
    require __DIR__.'/vendor/autoload.php';
    require __DIR__.'/settings.php';

    $instagram = new \Instagram\Instagram();
?>
<html>
    <!--TODO:Inserire codice html per interfaccia elaborazione header e accesso alle statistiche-->
    <head>
      <meta charset="UTF-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1">
        <link rel="stylesheet" href="css/w3.css">
        <link rel="stylesheet" href="bootstrap/btt.css">
        <link rel="stylesheet" href="css/mycss.css">
        <title>Instagram Post</title>
    </head> 
    <body class="body-style">
        <nav class="navbar navbar-expand-md"style="background-color:  #10707f">
            <div style="text-align: center"
                <ul class="navbar-nav">
                    <li>
                        <div>
                            <b class="myfont w3-opacity" style="font-size: 22px; color: white">Instagram Post</b
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
        <!-- Contet will be here -->
    </body>
</html>
<div class="row">
    <?php
        // Repo DOCS: https://github.com/liamcottle/Instagram-SDK-PHP

        $username = "";
        $password = ";

        try {
            //Instagram Login
            $instagram->login($username, $password);

            //Get User Pst
            do{
                if(isset($timelineFeed)){
                    $timelineFeed = $instagram->getMyUserFeed($timelineFeed->getNextMaxId());
                }else{
                    $timelineFeed = $instagram->getMyUserFeed();
                }
                
                foreach($timelineFeed->getItems() as $timelineFeedItem){ ?>
                    <div class="col-sm-3">
                        <div class="card">
                        <img src="<?= $timelineFeedItem->getImageVersions2()->getCandidates()[0]->getUrl() ?>" class="card-img-top" alt="...">
                        <div class="card-body">
                            <p class="card-text">
                                <?php
                                    if(!is_null($timelineFeedItem->getCaption())){
                                        echo $timelineFeedItem->getCaption()->getText();
                                    }
                                ?>
                            </p>
                        </div>
                        </div>
                    </div>
                <?php }
            }while(!is_null($timelineFeed->getNextMaxId()));
            $instagram->logout();
        } catch(Exception $e){
            //Something went wrong...
            echo $e->getMessage() . "\n";
        }
    ?>
</div>