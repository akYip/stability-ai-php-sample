<?php


$apikey = '{YOUR API KEY}'; //put your key
$prompt = 'bikini girl'; //put your prompt

$steps = 15;
$num_samples=1;
$width = 512;
$height = 512;


		$ch = curl_init();
		$headers  = array(
		    'Accept: application/json',
		    'Content-Type: application/json',
		    'Authorization: Bearer ' . $apikey . ''
		);

		$postData = array(
		    'text_prompts'=>[array('text'=>$prompt)],
		    'cfg_scale'=>7,
		    'clip_guidance_preset'=> 'FAST_BLUE',
		    'height'=> $height,
		    'width'=> $width,
		    'samples'=> $num_samples,
		    'steps'=> $steps
		);

		curl_setopt($ch, CURLOPT_URL, 'https://api.stability.ai/v1/generation/stable-diffusion-v1-5/text-to-image');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);

		$output = curl_exec($ch);



$joutput = json_decode($output,true);

if($joutput["artifacts"][0]["finishReason"] != "SUCCESS"){
	print $joutput["artifacts"][0]["finishReason"];
}

file_put_contents("stab.png", base64_decode($joutput["artifacts"][0]["base64"]));


$image = imagecreatefromstring( base64_decode($joutput["artifacts"][0]["base64"]));

header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);


?>