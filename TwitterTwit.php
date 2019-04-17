<?php
    // Loading composer packages
    require __DIR__.'/vendor/autoload.php';
?>
<html>
    <!--TODO:Inserire codice html per interfaccia elaborazione header e accesso alle statistiche-->
    <head>
      <meta charset="UTF-8">
        <meta name="viewport" content="width = device-width, initial-scale = 1">
        <link rel="stylesheet" href="css/w3.css">
        <link rel="stylesheet" href="bootstrap/btt.css">
        <link rel="stylesheet" href="css/mycss.css">
        <title>TwitterTwit</title>
    </head> 
    <body class="body-style">
        <nav class="navbar navbar-expand-md"style="background-color:  #10707f">
            <div style="text-align: center"
                <ul class="navbar-nav">
                    <li>
                        <div>
                            <b class="myfont w3-opacity" style="font-size: 22px; color: white">TwitterTwit</b
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
        <p>
            <ul>
                <li>Please enter your twitter name in the text box</li>
            </ul>
        </p>
        <form class="form-inline" name = "get_twit" action = "TwitterTwit.php" method = "POST" style="margin-top: 1.5vw; margin-left: 3vw; max-width: max-content">
            <div class="form-group">
                <input type="text" class="form-control" id="twitter_name" name="twitter_name" placeholder="Enter Twitter Name">
            </div>
            <div style="margin-left: 1vw">
                <button type="submit" class="btn btn-light w3-hover-yellow" style="border-style: solid; border-color: lightgrey; background-color: greenyellow; color: blue">Submit</button>
            </div>
        </form>
    </body>
</html>

<?php
    /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
    $settings = array(
        'oauth_access_token' => "1116240988954071040-sE450blS1aBYGNDiAh338jlum6KEJX",
        'oauth_access_token_secret' => "zmKuK6tQMr2CO07KxZgEDaYlbULOxwnlxbd5jcLebGBiF",
        'consumer_key' => "PKPZ8tUJVDXPK7RwZUMz3Z8tJ",
        'consumer_secret' => "MkMXiFpvNfDEXawZZnieInFd8g3d8LTdGK7krmu3JvYtWA67r5"
    );

    $url = "https://api.twitter.com/1.1/statuses/user_timeline.json";

    $requestMethod = "GET";

    if (isset($_POST['twitter_name'])) {
        $twitter_name = preg_replace("/[^A-Za-z0-9_]/", '', $_POST['twitter_name']);
        $getfield = "?screen_name=".$twitter_name."&count=20";
        $twitter = new TwitterAPIExchange($settings);
        //convert to an associative array
        $string = json_decode($twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest(),$assoc = TRUE);
        //stampo l'eventuale errore ritornato da twitter
        if(array_key_exists('errors', $string)) {
            echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string['errors'][0]["message"]."</em></p>";exit();}
            //stampo l'array con testo preformattato
            echo"<pre>";print_r($string);
    }
?>