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
?>