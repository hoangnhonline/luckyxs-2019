<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\BetType;
use App\Models\Bet;
use App\Models\Message;
use App\Models\MessageMau;
use Illuminate\Http\Request;
use Auth, Session;
class TestController extends Controller
{
    private $channelList;
    private $channelListKey;
    private $arrExpert = ['dp', 'dc', '2d'];
    private $channelByDay = 
    [
        '1' => [
            'tp',
            'dt',
            'cm'
        ],
        '2' => [
            'bt',
            'vt',
            'bli'
        ],
        '3' => [
            'dn',
            'ct',
            'st'
        ],
        '4' => [
            'tn',
            'ag',
            'bth'
        ],
        '5' => [
            'vl',
            'bd',
            'tv'
        ],
        '6' => [
            'tp',
            'la',
            'bp',
            'hg'
        ],
        '7' => [
            'tg',
            'kg',
            'dl'
        ],

    ];
    private $betTypeList;
    private $betTypeListKey;

    /**
     * Show the profile for the given user.
     *
     * @param  
     * @return View
     */
    public function __construct(){
        $this->channelList = Channel::pluck('code', 'id')->toArray();
        $this->channelListKey = array_flip($this->channelList); 
        $this->betTypeList = BetType::pluck('keyword', 'id')->toArray();   
        $this->betTypeListKey = array_flip($this->betTypeList);    
    }
    //preg_replace('/([ .])(\d)(\.5)([a-z])/', '${1}999n${4}', $input_lines);
    public function messagesList(){
        $user = Auth::user();
        $tel_id = $user->tel_id;
        $messageList = Message::where('tel_id', $tel_id)->whereDate('created_at', '=', date('Y-m-d'))->orderBy('id', 'desc')->get();
        return view('messages.list', compact('messageList'));
    }
    public function mau(){
       
        $messageList = MessageMau::where('status', 3)->get();
        return view('messages.mau', compact('messageList'));
    }
    public function messagesDetail(Request $request){
        $user = Auth::user();
        $tel_id = $user->tel_id;
        $message_id = $request->id;
        $detail = Message::where('tel_id', $tel_id)->where('id', $message_id)->first();        
        $betList = Bet::where('message_id', $message_id)->get();
        return view('messages.detail', compact('detail', 'betList'));
    }
    public function index()
    {        
        //$message = "00.01.03.04.05.06.07.08.09.20.dd da300n,dp t3";
  
        
        #17, 19, 20, 21, 23, 25,30, 37, 38, 39, 46,47, 49 (038.336.259.b2nxx8n), 52
        
        $message = "Dc 483.753.1nb10nx.2nxdao .79.39.da1n. 53.86.da1n. 63.10.da1n. 83.86.da1n. T19";
        $userDetail = Auth::user();
        $message_id = Message::create(['tel_id' => $userDetail->tel_id, 'content' => $message])->id;
        echo "<h3>".$message."</h3>";
        //$message = "T6.hn 77;88;99 đa vòng 15n";
        // 500 dong
        $message = preg_replace('/(05)(\s)(db)/', '9990ndb', $message);
        $message = preg_replace('/(05)(\s)(bl)/', '9990ndb', $message);
        $message = preg_replace('/(05)([a-z*])/', '9990n${2}', $message);
        //dd($message);
        // end 500 dong
        //$message = (preg_replace('/([ .])(\d)(\.5)([a-z*])/', ' 999${2}n${4}', $message));
        $message = (preg_replace('/([a-z*])(\d)(\.5)([ .])/', '${1}999${2}n${4}', $message));
        //dd($message);
        $message = (preg_replace('/([t])([0-9,{1,}])/', ' ', $message));
        //$message = preg_replace('/([a-zA-Z,{1,}])([0-9,{1,}])([\.\s])/', ' $1$2n ', $message); T21
        //dd($message); 

        $message = $this->formatMessage($message);    
   
        //$message = preg_replace('/([a-z]*)([0-9,{1,}]*)([n])/', ' $1$2$3', $message);   
        

        $message = preg_replace('/([0-9,{1,}]*)([n])([a-z]*)/', ' $3$1$2 ', $message);
 //dd($message); 
        $message = preg_replace('/([0-9]*)([n])/', ' $1$2 ', $message);
        
         
        $message = (preg_replace('/([0-9]{2,})([a-z]{2,})/', '$1 $2', $message));
       
        $message = $this->formatMessage($message);
        $message = str_replace("n n", "n", $message);
        //dd($message);       
        $tmpArr = explode(" ", $message);
        $countAmount = $countChannel = $countBetType = 0;
        $amountArr = $channelArr = $betTypeArr = [];    
        
        foreach($tmpArr as $k => $value){
            
            if($this->isChannel($value)){
                $countChannel++;
                $channelArr[] = $k;
            }

        }
        // nếu tin nhắn ko có đài thì mặc định là dc
        // TH chi co 1
        $betArr = [];
        
        //echo "<br>";
        if(count($channelArr) > 0){
            foreach($channelArr as $key => $value){           
                $position =   isset($channelArr[$key+1]) ? $channelArr[$key+1] : count($tmpArr);
                $start = $key > 0 ? $value : 0;
                $betArr[] = array_slice($tmpArr, $start, $position-$start);
                
            }
        }else{
            $betArr[] = $tmpArr;
        }   
        //dd($betArr);
        foreach($betArr as $arr){
            $betArrDetail[] = $this->parseBetToChannel($arr);
        }
        $betDetail = [];     
       // dd($message);
        //dd($betArrDetail);
        foreach($betArrDetail as $k => $betChannelDetail){
            $tmp2 = $this->parseDetail($betChannelDetail, $message);            
            $betDetail = array_merge($betDetail, $tmp2);
        }
       // dd($betDetail);
        $this->insertDB($betDetail, $message_id);
        
        Session::forget('arrSo');
    }
    function parseDetail($betArrDetail, $message){  
         $bettttt = []; 
        // dd($betArrDetail);
        foreach($betArrDetail as $channel => $arr){
            $countII = 0;
            foreach($arr as $tmp){
                
                $channel_bet = $channel;
                $price = str_replace("n", "", array_pop($tmp)); // lay so tien va xoa luon
                $price = $price == 0 ? 0.5 : $price;
                $price = $price == 9991 ? 1.5 : $price;
                $price = $price == 9992 ? 2.5 : $price;
                $price = $price == 9993 ? 3.5 : $price;
                $price = $price == 9994 ? 4.5 : $price;
                $price = $price == 9995 ? 5.5 : $price;
                $str = implode(' ', $tmp);
                $arr_number = [];
                foreach($tmp as $k1 => $tmp1){                 
                    if (preg_match('/[a-z*]/', $tmp1, $matches)){                        
                       $bet_type = $tmp1;        
                    }
                    if($tmp1 > 0 || $tmp1 == "00" || $tmp1 == "000" || $tmp1 == "0000"){
                        $arr_number[] = $tmp1; 
                    }
                }
                if(empty($arr_number) && isset($numberArr)){
                    $arr_number = $numberArr[$countII-1];
                }
                $numberArr[$countII] = $arr_number;
                //var_dump("<pre>", $numberArr);
                //dd($arr_number);
                // truong hop tao lao : loai de nam sau so tien
                if(!isset($bet_type) && count($arr) == 1){
                    $tmpMess = explode(" ",$message);
                    foreach($tmpMess as $tmpValue){
                        if (preg_match('/^[a-z]+$/i', $tmpValue, $matches)){                        
                           $bet_type = $tmpValue;
                           break;        
                        }
                    }                  
                }
                
                if(!isset($bet_type) && strlen($arr_number[0]) == 4){
                    
                    $bet_type = 'bl';
                }
                if(!isset($bet_type)){
                    dd($arr_number);
                }
                if($bet_type == 'dv' || $bet_type == 'dxv'){
                    
                    $bettttt[] = [
                        'channel' => $channel_bet,
                        'bet_type' => $this->formatBetType($bet_type),
                        'number' => $arr_number,
                        'price' => $price
                    ];
                }elseif($bet_type == 'da' || $bet_type == 'dx'){
                    
                    if(count($arr_number)%2==0){
                        $ii = 0;
                        $arrNumberNew = [];
                        foreach($arr_number as $tmpNumber){
                             $ii++;
                            $tmpArr[] = $tmpNumber;
                            if($ii%2 == 0 && $ii > 0){
                                $arrNumberNew[] = $tmpArr;
                                $tmpArr = [];
                            }
                           
                        }
                        if(!empty($arrNumberNew)){
                            foreach($arrNumberNew as $arrNumber){
                                $bettttt[] = [
                                    'channel' => $channel_bet,
                                    'bet_type' => $this->formatBetType($bet_type),
                                    'number' => $arrNumber,
                                    'price' => $price
                                ]; 
                            }
                        }
                    }else{ // truong hop "da" nhung chi co 3 so vd : da 12 24 23 => dang le la da vong moi dung
                        
                        $arrNumberNew = $this->getCapSoDaVong($arr_number);
                        
                        if(!empty($arrNumberNew)){
                            foreach($arrNumberNew as $arrNumber){
                                $bettttt[] = [
                                    'channel' => $channel_bet,
                                    'bet_type' => $this->formatBetType($bet_type),
                                    'number' => $arrNumber,
                                    'price' => $price
                                ]; 
                            }
                        }
                    }
                    

                   
                }else{
                   
                    if(($bet_type == 'd' && count($arr_number) == 1 && strlen($arr_number[0]) > 2) || $bet_type == 'db'){
                        $bet_type = 'bd';
                    }
                     
                    foreach($arr_number as $numberBet){
                        $bettttt[] = [
                            'channel' => $channel_bet,
                            'bet_type' => $this->formatBetType($bet_type),
                            'number' => $numberBet,
                            'price' => $price
                        ];
                    } 
                }
                $countII++;          
            }
            
        }
        return $bettttt;
    }
    function insertDB($betDetail, $message_id){
        //dd($betDetail);        
        foreach($betDetail as $k => $oneBet){  
            //dd($oneBet);die;
            $bet_type = $oneBet['bet_type'];
            $arrSo = Session::get('arrSo');
            if(!isset($arrSo[$oneBet['number']]) || empty($arrSo)){
                $arrSo[$oneBet['number']] = 1;
            }else{
                $arrSo[$oneBet['number']] += 1;
            }
            echo "<hr>";
            print_r($arrSo);
            echo "<hr>";
            Session::put('arrSo', $arrSo);
            $channelArr = $this->getChannelId($oneBet['channel']);
            $bet_type_id = $this->getBetTypeId($bet_type); 
            //dd($bet_type);
            if(!in_array($bet_type, ['dv', 'dx', 'dxv', 'da', 'dxc', 'bd'])){
                // check truong hop 4 con, 3 con
                $arrGiamso = [];
                foreach($betDetail as $k1 => $oneBet1){ 
                    if($k1 < $k && $oneBet['number'] == $oneBet1['number'] && $oneBet['bet_type'] == $oneBet1['bet_type'] && ((isset($arrSo[$oneBet['number']]) && $arrSo[$oneBet['number']] < 3) || !isset($arrSo[$oneBet['number']]))){
                        if(strlen($oneBet['number']) == 4){
                           
                            $oneBet['number'] = substr($oneBet['number'], 1, 3);
                            
                        }elseif(strlen($oneBet['number']) == 3){
                            
                            $oneBet['number'] = substr($oneBet['number'], 1, 3);
                           
                        }
                        
                    }
                    
                }
                Session::put('arrGiamso', $arrGiamso);
                $this->processNormal($oneBet, $bet_type_id, $channelArr, $message_id);
                
            }elseif($bet_type == 'da' || $bet_type == 'dx'){ 
                if(empty($channelArr)){
                    continue;
                }
                $countDv1 = 0;
                $refer_bet_id = null;
                foreach($channelArr as $channel_id){
                    $countDv1++;
                    $arr = [
                        'channel_id' => $channel_id,
                        'bet_type_id' => $bet_type_id,
                        'message_id' => $message_id,
                        'price' => $oneBet['price'],
                        'number_1' => $this->formatNumber($oneBet['number'][0]),
                        'number_2' => $this->formatNumber($oneBet['number'][1]),
                        'refer_bet_id' => $countDv1 > 1 ? $refer_bet_id : null,
                        'total' => $oneBet['price']*36, // 2 dai x 18 lo x 2 so = 72 lo
                        'is_main' => $refer_bet_id > 0 ? 0 : 1,
                        'bet_day' => date('Y-m-d')
                    ];
                    
                    $rs = Bet::create($arr);
                    if($countDv1 == 1){
                        $refer_bet_id = $rs->id;
                    }
                }
               // đá , đá xiên 
            }elseif($bet_type == 'dv' || $bet_type == 'dxv'){
                $this->processDvDxv($oneBet, $bet_type_id, $channelArr, $message_id);
            } // đá vòng
            elseif($bet_type == 'dxc'){
                $this->processDxc($oneBet, $bet_type_id, $channelArr, $message_id);
                
            }elseif($bet_type == 'bd'){
              
                $this->processBaoLoDao($oneBet, $bet_type_id, $channelArr, $message_id);
                
            }
        }
    }
    function processNormal($oneBet, $bet_type_id, $channelArr, $message_id){
        foreach($channelArr as $channel_id){                    
            if(empty($channelArr)){
                continue;
            }
            if($bet_type_id == 9 && strlen($oneBet['number']) == 4){
                $oneBet['number'] = substr($oneBet['number'], 1, 3);
            }
            $arr = [
                'channel_id' => $channel_id,
                'bet_type_id' => $bet_type_id,
                'message_id' => $message_id,
                'price' => $oneBet['price'],
                'number_1' => $this->formatNumber($oneBet['number']),
                'is_main' => 1,
                'total' => $this->calTotal($bet_type_id, $oneBet['price'],$oneBet['number']),
                'bet_day' => date('Y-m-d')                   
            ];                    
           
            Bet::create($arr);
        }
    }
    function processBaoLoDao($oneBet, $bet_type_id, $channelArr, $message_id){
        //dd($bet_type_id);
        $arrTatCaSo = $this->getTatCaSoDao($oneBet['number']);
        //dd($arrTatCaSo);
        $arrTatCaSo = array_unique($arrTatCaSo);
        
        if(!empty($arrTatCaSo)){
            foreach($arrTatCaSo as $number){
                foreach($channelArr as $channel_id){                    
                    if(empty($channelArr)){
                        continue;
                    }
                    $arr = [
                        'channel_id' => $channel_id,
                        'bet_type_id' => 4, // bao lo
                        'message_id' => $message_id,
                        'price' => $oneBet['price'],
                        'number_1' =>  $this->formatNumber($number),
                        'is_main' => 1,
                        'total' => $this->calTotal($bet_type_id, $oneBet['price'],  $number),
                        'bet_day' => date('Y-m-d')                   
                    ];                    
                   
                    Bet::create($arr);
                }
            }
        }
    }
    function processDxc($oneBet, $bet_type_id, $channelArr, $message_id){
        //dd($bet_type_id);
        $arrTatCaSo = $this->getTatCaSoDao($oneBet['number']);
        $arrTatCaSo = array_unique($arrTatCaSo);
        if(!empty($arrTatCaSo)){
            foreach($arrTatCaSo as $number){
                foreach($channelArr as $channel_id){                    
                    if(empty($channelArr)){
                        continue;
                    }
                    $arr = [
                        'channel_id' => $channel_id,
                        'bet_type_id' => 9, //xiu chu
                        'message_id' => $message_id,
                        'price' => $oneBet['price'],
                        'number_1' =>  $this->formatNumber($number),
                        'is_main' => 1,
                        'total' => $this->calTotal($bet_type_id, $oneBet['price'],  $number),
                        'bet_day' => date('Y-m-d')                   
                    ];                    
                   
                    Bet::create($arr);
                }
            }
        }
    }
    function processDvDxv($oneBet, $bet_type_id, $channelArr, $message_id){        
        if(count($oneBet['number']) == 1){
            for($i = 0; $i < strlen($oneBet['number'][0]); $i++){
                if($i%2 == 0){
                    $arrNumber[] = substr($oneBet['number'][0], $i, 2);
                }
            }
            $oneBet['number'] = $arrNumber;
        }        
        $arrCapSoDaVong = $this->getCapSoDaVong($oneBet['number']);
        //dd($arrCapSoDaVong);
        if(!empty($arrCapSoDaVong)){
            $countDv = 0;
            $refer_bet_id = null;
            foreach($arrCapSoDaVong as $capSoArr){
                foreach($channelArr as $channel_id){
                    $countDv++;
                    $arr = [
                        'channel_id' => $channel_id,
                        'bet_type_id' => $bet_type_id,
                        'message_id' => $message_id,
                        'price' => $oneBet['price'],
                        'number_1' => $this->formatNumber($capSoArr[0]),
                        'number_2' => $this->formatNumber($capSoArr[1]),
                        'refer_bet_id' => $countDv > 1 ? $refer_bet_id : null,
                        'total' => $oneBet['price']*36, // 1 dai x 18 lo x 2 so = 72 lo
                        'is_main' => $refer_bet_id > 0 ? 0 : 1,
                        'bet_day' => date('Y-m-d')
                    ];
                    
                    $rs = Bet::create($arr);
                    if($countDv == 1){
                        $refer_bet_id = $rs->id;
                    }
                }
            }
        }
    }
    function getCapSoDaVong($arr) {
        $rs = array();
        for ($i = 0; $i < count($arr) - 1; $i++) {
            for ($j = $i + 1; $j < count($arr); $j++) {
                $rs[] = [$arr[$i], $arr[$j]];
            }
        }

        return $rs;
    }
    function getTatCaSoDao($string) {
        $results = [];

        if (strlen($string) == 1) {
            array_push($results, $string);
            return $results;
        }

        for ($i = 0; $i < strlen($string); $i++) {
            $firstChar = $string[$i];
            $charsLeft = substr($string, 0, $i) . substr($string, $i + 1);
            $innerPermutations = $this->getTatCaSoDao($charsLeft);
            
            for ($j = 0; $j < count($innerPermutations); $j++) {
                
                array_push($results, $firstChar . $innerPermutations[$j]);
                
            }
        }
        return $results;
    }

    function parseBetToChannel($arr){  
        
        $channel = $arr[0]; // dc, dp, 2d, vl, tp, kg...
       
        $arrNew = array_slice($arr, 1, count($arr));
        //dd($arrNew);    
        foreach($arrNew as $k => $v){
            
            $patternAmount = '/[0-9]*[n]/';            
            if (preg_match_all($patternAmount, $v, $matches)){
                $betTypeKey[] = $k;             
            }
        }
        //echo "<hr><pre>";
        //print_r($arrNew);
        foreach($betTypeKey as $key => $value){

            $end =  $value+1;
            
            $start = $key > 0 ? $betTypeKey[$key-1]+1 : 0; 
                //var_dump($end, $start);
               // echo "<hr><pre>";
            $tmp3 = array_slice($arrNew, $start, $end-$start);
           // dd($tmp3);
            if(!empty($tmp3)){
                $tmp2[] = $tmp3;
            }            
            
        }  
        return [$channel => $tmp2];

    }
    function formatMessage($message){    
       
        $message = str_replace("d.phu", "dp", $message);
        $message = str_replace("D.phu", "dp", $message);
        $message = str_replace("D.Phu", "dp", $message);
        $message = str_replace("dbao", "db", $message);
        $message = str_replace("0.5", "1n", $message);        
        $message = str_replace("...", " ", $message);
        $message = str_replace(":", " ", $message);
        $message = str_replace("..", " ", $message);
        $message = str_replace(".", " ", $message);
        $message = str_replace(";", " ", $message);
        $message = str_replace("---", " ", $message);
        $message = str_replace("--", " ", $message);
        $message = str_replace("-", " ", $message);
        $message = str_replace("___", " ", $message);
        $message = str_replace("__", " ", $message);
        $message = str_replace("_", " ", $message);
        $message = str_replace(",,,", " ", $message);
        $message = str_replace(",,", " ", $message);
        $message = str_replace(",", " ", $message);
        $message = str_replace("   ", " ", $message);
        $message = str_replace("  ", " ", $message);
        $message = str_replace(" ", " ", $message);
       
        $message = str_slug($message, " ");
        $message = strtolower($message);
        
        $message = str_replace("lay tin nay", "", $message);
        $message = str_replace("bd", "db", $message);
        $message = str_replace("daoxc", "dxc", $message);
        $message = str_replace("bdao", "db", $message);        
        $message = str_replace("xdao", "dxc", $message);
        $message = str_replace("xd", "dxc", $message);
        $message = str_replace("chanh", "dc", $message);
        $message = str_replace("dacap", "da", $message);
        $message = str_replace("da vong", "dv", $message);
        $message = str_replace("2 đài", "2d", $message);
        $message = str_replace("2 dai", "2d", $message);
        $message = str_replace("2dai", "2d", $message);
        $message = str_replace("phu", "dp", $message);        
        $message = str_replace("fu", "dp", $message);
        $message = str_replace("ch", "dc", $message);    
            
        $message = str_replace("dav", "dv", $message);
        
        
        return $message;
    }
        
    function isAmount($value){
        $flag = false;
        if(strpos($value, 'n')){
            $value = str_replace("n", "", $value);
            if($value > 0){
                $flag = true;
            }
        }
        return $flag;
    }

    function isChannel($value){

        if(in_array($value, $this->channelList) || in_array($value, $this->arrExpert)){
            return true;
        }else{
            return false;
        }
    }

    function isBetType($value){        
        return in_array($value, $this->betTypeList);
    }
    function getBetTypeId($bet_type){  
        var_dump("<hr>", $bet_type);
        $rs  = BetType::where('keyword', $bet_type)->first();
        if($rs){
           return $rs->id;
        }else{
            $bet_type = (preg_replace('/([0-9]*)([a-z])/', '$2', $bet_type));
            $bet_type = $this->formatBetType($bet_type);
            $rs  = BetType::where('keyword', $bet_type)->first();
            if($rs){
               return $rs->id;
            }else{
                dd("11111", $bet_type);
            }
        }
    }
    function formatNumber($number){
        return $number = (preg_replace('/([0-9]*)([a-z])/', '$1', $number));
    }
    function getChannelId($channel = ''){
        $channelSelected = [];
        $today = date('N');
        if($channel == '2d'){
            $channelSelected = [$this->channelListKey[$this->channelByDay[$today][0]], $this->channelListKey[$this->channelByDay[$today][1]]];
        }elseif($channel == 'dc' || $channel == ""){
            $channelSelected = [$this->channelListKey[$this->channelByDay[$today][0]]];
        }elseif($channel == 'dp'){
            $channelSelected = [$this->channelListKey[$this->channelByDay[$today][1]]];
        }

        return $channelSelected;
    }
    function formatBetType($bet_type){
        switch ($bet_type) {
            case 'd':
                $bet_type = 'da';
                break;
            case 'b' : 
                $bet_type = 'bl';
                break;
            case 'xc' : 
                $bet_type = 'x';
                break;    
            default:
                # code...
                break;
        }
        return $bet_type;
    }
    function calTotal($bet_type_id, $price, $number){
        $length = strlen($number);
      
        switch ($bet_type_id) {
            case 9: // xiu, xiu chu 2 lô;
                $total = $price*2;
                break;
            case 10: // dao xiu 2 lô;
                $total = $price*2;
                break;
            case 2: // dau 1 lo
                $total = $price;
                break;
            case 3: // duoi 1 lo
                $total = $price;
                break;
            case 1: // dau duoi 2 lo
                $total = $price*2;
                break;    
            case 4: // bao lo
                if($length == 2){
                    $total = $price*18;    
                }
                if($length == 3){
                    $total = $price*17;    
                }
                if($length == 4){
                    $total = $price*16;    
                }
                
                break;    
            case 13: // bao lo
                
                if($length == 3){
                    $total = $price*17;    
                }
                if($length == 4){
                    $total = $price*16;    
                }
                
                break;
            default:
                dd($bet_type_id);
                # code...
                break;
        }
        
        var_dump($number);
        if(!isset($total)){
            dd($bet_type_id);
        }
        return $total;
    }
}