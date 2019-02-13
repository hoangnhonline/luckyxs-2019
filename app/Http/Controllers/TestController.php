<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\BetType;
use App\Models\Bet;
use App\Models\Message;
use Illuminate\Http\Request;
use Auth;
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
    public function messagesList(){
        $user = Auth::user();
        $tel_id = $user->tel_id;
        $messageList = Message::where('tel_id', $tel_id)->whereDate('created_at', '=', date('Y-m-d'))->get();
        return view('messages.list', compact('messageList'));
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
        //$message = "dc . 841.915.279.xc.50n. 2d . 62.03.da10n. 723..491 x.50n 13.64 da5n. t2";
        
        $message = "2 đài 32-23.da.10n.21.28.da5n.26.66.da1n..da 98.57.2n dav 41.76.95.39.1n.2d da 39.41.1n.69.51.1n.83.39.1n.t.1";
        $message = "2d.dav 13.95.07.39.1n da 13.51.1n 63.51.1n 98.57.1n 22.98.1n 22.39.1n 22.95.1n 22.13.1n t.4";
        $message = "2dai da 14.54--94.14--54.94--18.58--98.18--58.98--38.78-1n 2d.36.32.72 da2n.t5";
        $message = "2dai da 14.54  1n t5";
       /// chua dc $message = "2d da 07.76.1n.22.41.1n. la.18.68.19.99.dau,100n.81.dau.100n.dui.400n.t6";
        $message = "2d . 68 - 14 da3n . 10 - 01 da3n . t7";
        // chua dc b 10n // $message = "dc.79.dui.200n.2d.72.79.da1n.33.73.da10n.2d 46 64 da 5n b 10n.t6";
        // chua dc $message = "00.01.03.04.05.06.07.08.09.20.dd da300n,dp t3";
        $message = "dc . 3312 b10n 2d 62 03 da10n . t1";
        $message = "2d..da 14.54--94.14--54.94--18.58--98.18--58.98--20.02--58.85-1n 2d dav 07.95.41.76.1n da 22.41.1n 22.98.1n 22.76.1n 22.95.1n.t.2";
        // chua dc $message = "2d . 92 - 38 da3n . 115163.đav5n. 185807.đav5n . phu 1668 b20n. 2d . 57, 53da 8n .18.32 da2n . 15 55 95 da5n t3";
        $message = "2d 35-53-89-98 dav10n.";
       // chua dc sai code // $message = "Ch bl 2852.10n 2852.d2n 2dai b 773.733.2n xc.126.20n 772.658.384.5n B 06.10n dc.dd.37.19.30n b.33.10n xc.651.146.172.107.30n 481.981.338.833.20n.251.042.468.145.551.583.10n T5";
        $message = "2dai b 773.733.2n xc.126.20n 772.658.384.5n B 06.10n";
        $message = "dc . 841.915.279.xc.50n. 2d . 62.03.da10n. 723..491 x.50n 13.64 da5n dc 20 30 40 dv10n. t2";
        $message = "2d da 22.98.1n. 13.41.1n. 95.13.1n. 29.95.1n. 29.41.1n...2d dav 76.41.95.1n da 41.02.1n 95.20.1n 41.20.1n 95.02.1n 13.51.1n t3";
        
        $message = "2d.da.29.69.da1n.06.23.da2n.t5";
        //$message = "2d.dacap 18.58-98-18-58.98-14.54-94.14-54.94-89.98-10.51-1n t4";
        // chua dc sai code //$message = "Ch bl 2852.10n 2852.d2n 
// 2dai b 773.733.2n
// xc.126.20n 772.658.384.5n
// B 06.10n
// dc.dd.37.19.30n
// b.33.10n
// xc.651.146.172.107.30n 481.981.338.833.20n.251.042.468.145.551.583.10n
// T5";
        //$message = "2d 123 dxc 10n dp 12 13 14 dxv 10n";
        $userDetail = Auth::user();
        $message_id = Message::create(['tel_id' => $userDetail->tel_id, 'content' => $message])->id;
        echo "<h3>".$message."</h3>";
        $message = (preg_replace('/([t])([0-9,{1,}])/', ' ', $message));
        $message = $this->formatMessage($message);

        $message = (preg_replace('/([0-9]*)([n])/', ' $1$2 ', $message));
        $message = (preg_replace('/([0-9]{2,})([a-z]{2,})/', '$1 $2', $message));
        $message = $this->formatMessage($message);
        echo $message;
       
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
        //dd($channelArr);
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

        foreach($betArr as $arr){
            $betArrDetail[] = $this->parseBetToChannel($arr);
        }
        $betDetail = [];     
        //dd($betArrDetail);
        foreach($betArrDetail as $k => $betChannelDetail){
            $tmp2 = $this->parseDetail($betChannelDetail);            
            $betDetail = array_merge($betDetail, $tmp2);
        }
           
        $this->insertDB($betDetail, $message_id);
    }
    function insertDB($betDetail, $message_id){
        //dd($betDetail);        
        foreach($betDetail as $k => $oneBet){  
                
            $bet_type = $oneBet['bet_type'];
            
            $channelArr = $this->getChannelId($oneBet['channel']);
            $bet_type_id = $this->getBetTypeId($bet_type); 
            //dd($bet_type);
            if(!in_array($bet_type, ['dv', 'dx', 'dxv', 'da', 'dxc'])){
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
                        'number_1' => $oneBet['number'][0],
                        'number_2' => $oneBet['number'][1],
                        'refer_bet_id' => $countDv1 > 1 ? $refer_bet_id : null,
                        'total' => $oneBet['price']*72, // 2 dai x 18 lo x 2 so = 72 lo
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
                
            }
        }
    }
    function processNormal($oneBet, $bet_type_id, $channelArr, $message_id){
        foreach($channelArr as $channel_id){                    
            if(empty($channelArr)){
                continue;
            }
            $arr = [
                'channel_id' => $channel_id,
                'bet_type_id' => $bet_type_id,
                'message_id' => $message_id,
                'price' => $oneBet['price'],
                'number_1' => $oneBet['number'],
                'is_main' => 1,
                'total' => $this->calTotal($bet_type_id, $oneBet['price'],$oneBet['number']),
                'bet_day' => date('Y-m-d')                   
            ];                    
           
            Bet::create($arr);
        }
    }
    function processDxc($oneBet, $bet_type_id, $channelArr, $message_id){
        //dd($bet_type_id);
        $arrTatCaSo = $this->getTatCaSoDao($oneBet['number']);
        if(!empty($arrTatCaSo)){
            foreach($arrTatCaSo as $number){
                foreach($channelArr as $channel_id){                    
                    if(empty($channelArr)){
                        continue;
                    }
                    $arr = [
                        'channel_id' => $channel_id,
                        'bet_type_id' => $bet_type_id,
                        'message_id' => $message_id,
                        'price' => $oneBet['price'],
                        'number_1' =>  $number,
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
        // dd($oneBet);
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
                        'number_1' => $capSoArr[0],
                        'number_2' => $capSoArr[1],
                        'refer_bet_id' => $countDv > 1 ? $refer_bet_id : null,
                        'total' => $oneBet['price']*72, // 2 dai x 18 lo x 2 so = 72 lo
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
        return array_unique($results);
    }
    function parseDetail($betArrDetail){  
         $bettttt = []; 
        // dd($betArrDetail);
        foreach($betArrDetail as $channel => $arr){
            
            foreach($arr as $tmp){
                $channel_bet = $channel;
                $price = str_replace("n", "", array_pop($tmp)); // lay so tien va xoa luon
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
                //dd($arr_number);
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
                    foreach($arr_number as $numberBet){
                        $bettttt[] = [
                            'channel' => $channel_bet,
                            'bet_type' => $this->formatBetType($bet_type),
                            'number' => $numberBet,
                            'price' => $price
                        ];
                    } 
                }
                               
            }
            
        }
        return $bettttt;
    }
    function parseBetToChannel($arr){       
        $channel = $arr[0]; // dc, dp, 2d, vl, tp, kg...
       
        $arrNew = array_slice($arr, 1, count($arr));       
        foreach($arrNew as $k => $v){
            
            $patternAmount = '/[0-9]*[n]/';
       
            if (preg_match_all($patternAmount, $v, $matches)){
                $betTypeKey[] = $k;             
            }
        }
        foreach($betTypeKey as $key => $value){           
            $end =  $value+1;
            
            $start = $key > 0 ? $betTypeKey[$key-1]+1 : 0; 

            $tmp3 = array_slice($arrNew, $start, $end-$start);
            if(!empty($tmp3)){
                $tmp2[] = $tmp3;
            }
            
        }
        return [$channel => $tmp2];

    }
    function formatMessage($message){    

        $message = str_replace("...", " ", $message);
        $message = str_replace("..", " ", $message);
        $message = str_replace(".", " ", $message);
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
        $message = str_replace("2 đài", "2d", $message);
        $message = str_replace("2 dai", "2d", $message);
        $message = str_replace("2dai", "2d", $message);
        $message = str_replace("phu", "dp", $message);
        $message = str_replace("fu", "dp", $message);
        $message = str_replace("ch", "dc", $message);       
        $message = str_replace("dav", "dv", $message);
        $message = str_replace("dacap", "da", $message);
        
        
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
        //var_dump("<br>Kiểu đánh : ", $bet_type);        
        return $bet_type_id = BetType::where('keyword', $bet_type)->first()->id;    
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
        //var_dump($bet_type_id);
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
            default:
                # code...
                break;
        }
        return $total;
    }
}