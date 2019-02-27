<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use App\Models\Channel;
use App\Models\BetType;
use App\Models\Bet;
use App\Models\Message;
use App\User;
class TelegramController extends Controller
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
    public function __invoke()
    {
    	$config = [
		    'telegram' => config('botman.telegram')
		];
		// Load the driver(s) you want to use
		DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
		// Create an instance
		$botman = BotManFactory::create($config);
		// Give the bot something to listen for.
		$botman->hears('/pass', function (BotMan $bot) {
		    $bot->reply('Bạn Anh Hoàng.');
		});

		$botman->hears('/hi', function (BotMan $bot) {
		    $bot->reply('Hi cc.');
		});

		$botman->hears('((.|\n)*)', function (BotMan $bot, $message) {
			$userId = $bot->getUser()->getId();

			$userExists = User::where('tel_id', '=', $userId)->count() >= 1;
			if (!$userExists) {
				$user = new User();
				$user->username = $message;
				$user->password = '';
				$user->tel_id   = $userId;
				$user->save();
			}
			
			$bot->reply('OK: ' . $message);

			$message_id = Message::create(['tel_id' => $userId, 'content' => $message])->id;
			
			$this->processMessage($message, $message_id);
			$bot->reply('OK: ' . $mess);
		});
		// Start listening
		$botman->listen();
    }
    function processMessage($message, $message_id){    	
        $message = (preg_replace('/([t])([0-9,{1,}])/', ' ', $message));
        $message = $this->formatMessage($message);

        $message = (preg_replace('/([0-9]*)([n])/', ' $1$2 ', $message));
        $message = (preg_replace('/([0-9]{2,})([a-z]{2,})/', '$1 $2', $message));
        $message = $this->formatMessage($message);
       
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
                        'bet_day' => date('Y-m-d'),
                        'len' => strlen($oneBet['number'][0]),
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
                'bet_day' => date('Y-m-d'),
                'len' => strlen($oneBet['number'])
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
                        'len' => strlen($number),
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
                        'len' => strlen($capSoArr[0]),
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