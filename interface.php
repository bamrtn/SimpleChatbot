<?php
class message{
    public $name;
    public $t;
    public $id;
}
class Connection{
    function __construct(){
        
    }
    
    
    public function get($query, $until){
        $temp = array(); 
        $data = json_decode(file_get_contents ($query.'.json'), TRUE);
        $data = $data["statuses"];
        for ($i = 0; $i < count($data); $i++){
            $temp[$i] = new tweet();
            $temp[$i]-> name = '@'.$data[$i]["user"]["screen_name"];
            $temp[$i]-> t = strtotime($data[$i]["created_at"]);
            $temp[$i]-> id = $data[$i]["id"];
        }
        return $temp;
    }
    
    public function send($replyto, $text){
        $file=fopen("reply.txt","a+");
        fwrite($file, "Reply To: ".$replyto."; Message: ".$text.PHP_EOL);
        fclose($file);
    }
}

?>