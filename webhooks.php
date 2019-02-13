<?php // callback.php

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = 'kXYOVyMrhLy2n+B6NmsuVkE4swrE3+retQyX1EddxT5TZe1heLzFras6x7E0uGvW9pNMmbFj1ioMamSeHjcKiDkL0DHHNnZTQW+YPu8Q5Zkp62RxCqEtFTWm9BqW73KdHJugBbr5loimnK3fRUlyJQdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
            $receivedText = $event['message']['text'];
			$text = $receivedText." ".$event['source']['userId'];
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];

			// Make a POST Request to Messaging API to reply to sender

			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
		}else if ($event['type'] == 'message' && $event['message']['type'] == 'sticker') {
   			$replyToken = $event['replyToken'];			
            		$receivedStickerID = $event['message']['stickerId'];
            		$receivedPackageID = $event['message']['packageId'];
			$messages[0] = [
				'type' => 'text',
                		'text' => 'Reply'
			];
			$messages[1] = [
				'type' => 'sticker',
                		'packageId' => 1,
                		'stickerId' => 2
			];
			// Make a POST Request to Messaging API to reply to sender

			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
        }
	$post = json_encode($data);
	$url = 'https://api.line.me/v2/bot/message/reply';
	$ch = curl_init($url);
    	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        echo $result . "\r\n";
	}
}
echo "OK ".date("Y-m-d h:i:sa", filemtime("webhooks.php"));
