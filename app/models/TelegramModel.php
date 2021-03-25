<?php

/*
*
* TelegramModel - Untuk Integration antara Telegram dengan PHP communication over HTTPS
*
* 1. Web server mesti diakses dalam mode HTTPS, otherwise Telegram rejects communicate dengan webserver kita
* 2. Create Bot dalam Telegram, copy API key dan paste ke dalam function PHP project menggunakan variable $robot_id dalam function setRobot()
*
* Function - getData() untuk terima data dari telegram sebaik sahaja menerima request dari Telegram
* setChatId($chatId) - Tambah user ke dalam list untuk kita execute message ke user menerusi Telegram
* send($method, $message) - $method sila guna 'sendMessage' untuk hantar message dari webserver ke Telegram pengguna
*
*/

class TelegramModel
{
	public $url = 'https://api.telegram.org/botXXXXXXXXXXXXX';
	private $users = [];
	
	public function __construct(){
		
	}
	
	public function setChatId($chatId){
		array_push($this->users, $chatId);
	}
	
	public function getData(){
		$request = file_get_contents('php://input');
		$request = json_decode($request, TRUE);
		
		$data = [
			"message" => $request['message']['text'],
			"userid" => $request['message']['chat']['id'],
			"username" => $request['message']['chat']['username'],
			"fullname" => $request['message']['chat']['first_name'] . " " . $request['message']['chat']['last_name']
		];
		
		return $data;
	}
	
	public function setRobot($robot_id){
		$this->url = "https://api.telegram.org/bot" . $robot_id;
	}
	
	public function showChat(){
		$data = $this->url . '/getUpdates';
		$json = json_decode(file_get_contents($data), true);
		$json = $json;
		echo '<pre>';
		print_r($json);
		echo '</pre>';
	}
	
	public function sendPhoto($file_id){
		foreach($this->users as $chatter){
			$data = $this->url . '/sendPhoto?chat_id=' . $chatter . '&photo=' . $file_id;
			$json = json_decode(file_get_contents($data), true);
			return $json;
		}
	}
	
	public function send($method, $message, $inline = [])
	{
		$encoded = json_encode($inline);
		
		foreach($this->users as $chatter){
			
			$data = [
				"chat_id" => $chatter,
				"text" => $message,
			];
			
			if(!empty($inline) && count($inline) > 0){
				$data['reply_markup'] = $encoded;
			}
			
			$url = $this->url . '/' . $method;

			if (!$curld = curl_init()) {
				exit;
			}
			curl_setopt($curld, CURLOPT_POST, true);
			curl_setopt($curld, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curld, CURLOPT_URL, $url);
			curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($curld);
			curl_close($curld);
			return $output;
		}
	}
	
	/* public function sendInline($inline){
		$encoded = json_encode($inline,JSON_PRETTY_PRINT);
		foreach($this->users as $chatter){
			$data = $this->url . '/sendMessage?chat_id=' . $chatter . '&text=' . urlencode("Please choose any option below") . '&reply_markup=' . $encoded;
			$json = json_decode(file_get_contents($data), true);
			return $json;
		}
	} */
	
	/* public function sendMessage($message){
		foreach($this->users as $chatter){
			$data = $this->url . '/sendMessage?chat_id=' . $chatter . '&text=' . urlencode($message);
			$json = json_decode(file_get_contents($data), true);
			return $json;
		}
	} */
}
