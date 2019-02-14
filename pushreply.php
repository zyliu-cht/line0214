<?php
    $json_str = file_get_contents('php://input'); //接收request的body
    $json_obj = json_decode($json_str); //轉成json格式
  
    $myfile = fopen("log.txt", "w+") or die("Unable to open file!"); //設定一個log.txt來印訊息
    fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
  
    $sender_userid = $json_obj->events[0]->source->userId; //取得訊息發送者的id
    $sender_type = $json_obj->events[0]->message->type; //取得訊息類型

    if($sender_type == "text"){
        $sender_txt = $json_obj->events[0]->message->text; //取得訊息內容
        if($sender_txt != "範本"){
            $response = array (
                "to" => $sender_userid,
                "messages" => array (
                    array (
                        "type" => "text",
                        "text" => "Hello. You said: ". $sender_txt
                    )
                )
            );
        } else {
            $response = array (
                "to" => $sender_userid,
                "messages" => array (
                    array (
                        "type" => "template",
                        "altText" => "BUTTON",
                        "template" => array (
                            "type" => "buttons",
                            "thumbnailImageUrl" => "https://www.w3schools.com/css/paris.jpg",
                            "title" => "Menu",
                            "text" => "Please select",
                            "actions" => array (
                                array (
                                    "type" => "postback",
                                    "label" => "Buy",
                                    "data" => "action=buy&itemid=123"
                                ),
                                array (
                                    "type" => "message",
                                    "label" => "Return",
                                    "text" => "This is text"
                                ),
                                array (
                                    "type" => "datetimepicker",
                                    "label" => "Select date",
                                    "data" => "storeId=12345",
                                    "mode" => "datetime",
                                    "initial" => "2017-12-25t00:00",
                                    "max" => "2018-01-24t23:59",
                                    "min" => "2017-12-25t00:00"
                                )
                            )
                        )
                    )
                )
            );
        }
    }
    elseif($sender_type == "sticker"){
        $sticker_id = $json_obj->events[0]->message->stickerId;
        $package_id = $json_obj->events[0]->message->packageId;
        $response = array (
            "to" => $sender_userid,
            "messages" => array (
                array (
                    "type" => "text",
                    "text" => "Hello. You post: stickerId=". $sticker_id. ", packageId=". $package_id
                )
            )
        );  //"type":"sticker","id":"9352799347319","stickerId":"21069261","packageId":"9601"
    }
//"type":"image","id":"9352825640135","contentProvider":{"type":"line"}}
    elseif($sender_type == "image"){
        $image_id = $json_obj->events[0]->message->id;
        $response = array (
            "to" => $sender_userid,
            "messages" => array (
                array (
                    "type" => "text",
                    "text" => "Hello, you've post an image(". $image_id. ")!"
                )
            )
        );
    }
    else
    {
        $response = array (
            "to" => $sender_userid,
            "messages" => array (
                array (
                    "type" => "text",
                    "text" => "I dont understand what you have done!"
                )
            )
        );
    }
  
    //fwrite($myfile, "\xEF\xBB\xBF".json_encode($response)); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
    $header[] = "Content-Type: application/json";
    $header[] = "Authorization: Bearer XZGnhd1WjmVz94+VelWCZ+tJYhcm7UIMqCEjWLyTVXXzjQSbfscxyiIW6EL2HV7U0DYs9xPGqn7wZ75Oq3iL+pTCX14VTQRHLS5bclwN3vC/o1auuiK7w5PLgJ2PiZVx19Bzpbg6F7wm+j0jRkXB2wdB04t89/1O/w1cDnyilFU=";
    $ch = curl_init("https://api.line.me/v2/bot/message/push");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
    $result = curl_exec($ch);
    curl_close($ch);
?>
