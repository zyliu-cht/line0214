<?php
    $json_str = file_get_contents('php://input'); //接收request的body
    $json_obj = json_decode($json_str); //轉成json格式
  
    $myfile = fopen("log.txt", "w+") or die("Unable to open file!"); //設定一個log.txt來印訊息
    fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前面加上\xEF\xBB\xBF轉成utf8格式
  
    $sender_userid = $json_obj->events[0]->source->userId; //取得訊息發送者的id
    $sender_txt = $json_obj->events[0]->message->text; //取得訊息內容

    if($sender_txt != "" && $sender_txt != NULL){
        $response = array (
            "to" => $sender_userid,
            "messages" => array (
                array (
                    "type" => "text",
                    "text" => "Hello. You said: ". $sender_txt
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
