<?php
$file = new SplFileObject("progress/".$filename.".txt");
$line = 0;
while (!$file->eof()) {
	$file->current();
    $file->next();
    $line++;
}
$file->rewind();
$time = 0;
for($i=0;$i<$line;$i++){
	$file->seek($line-$i);
	if(strpos($file->current(),"progress") !== false){
		if(trim(str_replace("progress=","",$file->current())) == "end"){
			print "end";
			$options = array(
				'expires'=>time()-100,
				'path'=>'/',
				'httponly'=>true,
				'samesite' => 'Strict'
			);
			unlink("progress/".$filename.".txt");
			unlink("prepare/".$filename.".".$fileExt);
			setcookie("ConvertTime","0",$options);
			setcookie("filename","0",$options);
			setcookie("ext","0",$options);
			setcookie("status","0",$options);
			setcookie("Progress","0",$options);
			break;
		}
	}
	if(strpos($file->current(),"out_time") !== false){
		$time = str_replace("out_time=","",$file->current());
		$time = explode(":",$time);
		$hours = floor($time[0]);
		$minutes = floor($time[1]);
		$seconds = floor($time[2]);
		$trueSeconds = ($hours*60*60)+($minutes*60)+$seconds;
		$progress = $trueSeconds/$_COOKIE['ConvertTime']*100;
		if(round($progress,2) < 99){
			$options = array(
				'expires'=>strtotime("+10 hours"),
				'path'=>'/',
				'httponly'=>true,
				'samesite' => 'Strict'
			);
			setcookie("Progress",$progress,$options);
			setcookie("status","convert",$options);	
		}
		print round($progress,2);
		break;
	}
	
}
