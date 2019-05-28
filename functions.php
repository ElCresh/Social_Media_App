<?php
    // Repo DOCS: https://github.com/liamcottle/Instagram-SDK-PHP
    function getInstagramPost($user,$password){
        $instagram = new \Instagram\Instagram();
        $data = array();
        
        try {
            //Instagram Login
            $instagram->login($user, $password);

            //Get User Post
            do{
                if(isset($timelineFeed)){
                    $timelineFeed = $instagram->getMyUserFeed($timelineFeed->getNextMaxId());
                }else{
                    $timelineFeed = $instagram->getMyUserFeed();
                }
                
                foreach($timelineFeed->getItems() as $timelineFeedItem){
                    $post = array();
                    $post['img'] = $timelineFeedItem->getImageVersions2()->getCandidates()[0]->getUrl();
                    $post['date'] = $timelineFeedItem->getTakenAt();
                    if(!is_null($timelineFeedItem->getCaption())){
                        $post['msg'] = $timelineFeedItem->getCaption()->getText();
                    }else{
                        $post['msg'] = '';
                    }

                    $data[] = $post;
                }
            }while(!is_null($timelineFeed->getNextMaxId()));

            $instagram->logout();
        } catch(Exception $e){
            //Something went wrong...
        }

        return $data;
    }

    function redirect_if_completed(){
        // Conto quanti sono i social abilitati
        $num_social = 0;
        
        if(isset($_SESSION['facebook'])){
            $num_social++;
        }else{
            $_SESSION['facebook_id_ok'] = false;
        }

        if(isset($_SESSION['twitter'])){
            $num_social++;
        }else{
            $_SESSION['twitter_id_ok'] = false;
        }

        if(isset($_SESSION['instagram'])){
            $num_social++;
        }

        // Verifico se tutti i login sono validi
        $finished = 0;

        if(isset($_SESSION['facebook']) && isset($_SESSION['facebook_id_ok']) && $_SESSION['facebook_id_ok']){
            $finished++;
        }

        if(isset($_SESSION['twitter']) && isset($_SESSION['twitter_id_ok']) && $_SESSION['twitter_id_ok']){
            $finished++;
        }

        if(isset($_SESSION['instagram']) && isset($_SESSION['instagram_id_ok']) && $_SESSION['instagram_id_ok']){
            $finished++;
        }

        if($num_social == $finished){
            echo '<script> window.location.href = "../Home/Home.php";</script>';
        }
    }
?>