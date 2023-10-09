<?php
if(isset($status) && $status == "end" && isset($filename) && file_exists("prepare/".$filename.".".$fileExt)){
	exec("ffprobe -i prepare/".$filename.".".$fileExt." -v 0 -select_streams v:0 -show_entries stream=bit_rate -hide_banner 2>&1",$ffprobe);
	for($i1 = 0;$i1<count($ffprobe);$i1++){
		if(strpos($ffprobe[$i1],"bit_rate") !== false){
			$bitrate = trim(str_replace("bit_rate=","",$ffprobe[$i1]));
			$bitrate = ceil($bitrate/1000);
			$bitrate = $bitrate."K";

		}
	}
	exec("ffmpeg -y -i prepare/".$filename.".".$fileExt." -f webm -c:v libvpx -b:v ".$bitrate." -crf 4 -c:a libvorbis -b:a 192K -threads 0 -cpu-used -5 -deadline realtime cinema/".$filename.".webm -progress progress/".$filename.".txt");
}elseif(file_exists("prepare/".$filename.".".$fileExt) == false){
	$options = array(
		'expires'=>time()-1000,
		'path'=>'/',
		'httponly'=>true,
		'samesite' => 'Strict'
	);
	setcookie("ConvertTime","0",$options);
	setcookie("filename","0",$options);
	setcookie("ext","0",$options);
	setcookie("status","0",$options);
	print "noExists";
}

