<?php

namespace Model;

class Demo extends \Model {
	
	public static function changeAchivement($performance,$benchmark,$threadshold) {
		$num = $performance - $threadshold;
		$deno = $benchmark - $threadshold;
		$division = $num / $deno;
		$product = 9 * $division;
		$result = $product + 0.5;
		$rounded = round($result);
		if($rounded > 10) {
			$rounded = 10;		
		}
		elseif($rounded < 0) {
			$rounded = 0;
		}
		return $rounded;
	}
	
	public static function changeImprovement($performance,$benchmark,$baseline) {
		$num = $performance - $baseline;
		$deno = $benchmark - $baseline;
		$division = $num / $deno;
		$product = 10 * $division;
		$result = $product - 0.5;
		$rounded = round($result);
		if($rounded > 9) {
			$rounded = 9;		
		}
		elseif($rounded < 0) {
			$rounded = 0;
		}
		return $rounded;
	}
	
	public static function changeMeasure($performance,$benchmark,$baseline, $threadshold) {
		$achieve = Demo::changeAchivement($performance,$benchmark,$threadshold);
		$perform = Demo::changeImprovement($performance,$benchmark,$baseline);
		return max($achieve, $perform);
	}
	
	public static function unweighted($total_m, $type) {
		$result = 0;
		switch($type) { #cases 0 = saftey, 1 = clinical, 2 = efficiency
			case 0:
				$result = $total_m / 70 * 100;
				break;
			case 1:
				$result = $total_m / 30 * 100;
				break;
			default:
				$result = $total_m / 10 * 100;
				break;
		}
		return $result;
	}
	
	public static function consistency($floor, $performance, $threadshold){
		$num = $performance - $floor;
		$deno = $threadshold - $floor;
		return $num/$deno;
	}

	public static function changeData($data,$changes) {
		$total_a = [];
		$total_i = [];
		$total_m = [];
		$exp = [];
		$decode = $data['decode'];
		$decode['title'] = $changes['title'];
		foreach($decode as $key => $val){
			if(is_array($val)){
				$sum_a= 0;
				$sum_i = 0;
				$sum_m = 0;
				foreach($val as $k => $v){
					
					$decode[$key][$k][$key.'baseline'.$k] = $changes[$key.'baseline'.$k];
					$decode[$key][$k][$key.'performance'.$k] = $changes[$key.'performance'.$k];
		
					$threadshold = $decode[$key][$k][$key.'threadshold'.$k];
					$benchmark = $decode[$key][$k][$key.'benchmark'.$k];
					$baseline = $decode[$key][$k][$key.'baseline'.$k];
					$performance = $decode[$key][$k][$key.'performance'.$k];
					if($key == "experience"){
						$floor = $decode[$key][$k]['floor'.$k];
						if($floor != "N/A")
							$dimension = Demo::consistency($floor,$performance,$threadshold);
						array_push($exp,$dimension);
					}
					#var_dump($threadshold);
					#var_dump($benchmark);
					#var_dump($baseline);
					#var_dump($performance);
					
					if($baseline != "N/A" || $performance != "N/A"){
						$decode[$key][$k][$key.'achievement'.$k] = Demo::changeAchivement($performance, $benchmark, $threadshold);
						$decode[$key][$k][$key.'improvement'.$k] = Demo::changeImprovement($performance, $benchmark, $baseline);
						$decode[$key][$k][$key.'measure'.$k] = Demo::changeMeasure($performance, $benchmark, $baseline, $threadshold);
						$sum_a += $decode[$key][$k][$key.'achievement'.$k];
						$sum_i += $decode[$key][$k][$key.'improvement'.$k];
						$sum_m += $decode[$key][$k][$key.'measure'.$k];					
					}
				}
				array_push($total_a,$sum_a);
				array_push($total_i,$sum_i);
				array_push($total_m,$sum_m);
			}

		}
		$lowest = min($exp);
		$raw_consistency = (20 * $lowest) - 0.5;
		$consistency = round($raw_consistency);
		if($consistency < 0){
			$consistency = 0; 
		}
		$decode['consistency'] = $consistency;

		# total_a array is expected to have [total_a_safety, total_a_clinical, total_a_eff, total_a_exp]
		# total_i array is expected to have [total_i_safety, total_i_clinical, total_i_eff, total_i_exp]
		# total_m array is expected to have [total_m_safety, total_m_clinical, total_m_eff, total_m_exp]
		$decode['safetyachievement7'] = $total_a[0]."/70";
		$decode['safetyimprovement7'] = $total_i[0]."/63";
		$decode['safetymeasure7'] = $total_m[0]."/70";
		$decode['clinicalachievement3'] = $total_a[1]."/30";
		$decode['clinicalimprovement3'] = $total_a[1]."/27";
		$decode['clinicalmeasure3'] = $total_a[1]."/30";
		$decode['efficiencyachievement1'] = $total_a[2]."/10";
		$decode['efficiencyimprovement'] = $total_a[2]."/9";
		$decode['efficiencymeasure1'] = $total_a[2]."/10";
		$decode['experienceachievement8'] = $total_a[3]."/80";
		$decode['experienceimprovement8'] = $total_a[3]."/72";
		$decode['experiencemeasure8'] = $total_a[3]."/80";
		$decode['basescore'] = $total_m[3];
		
		$unweighted_s = Demo::unweighted($total_m[0],0);
		$unweighted_c = Demo::unweighted($total_m[1],1);
		$unweighted_f = Demo::unweighted($total_m[2],2);
		$unweighted_x = $total_m[3] + $decode['consistency'];
		
		$weighted_s = $unweighted_s * 0.25;
		$weighted_c = $unweighted_c * 0.25;
		$weighted_f = $unweighted_f * 0.25;
		$weighted_x = $unweighted_x * 0.25;
		
		$tps = $weighted_s + $weighted_c + $weighted_f + $weighted_x;
		$decode['tps'] = $tps;
		$reimburse = $changes['reimbursement'];
		$twoPercent = $reimburse * 0.02;
		$decode['reduce'] = $reimburse - $twoPercent;
		$based_on_tps = $decode['reduce'] * ($tps/100);
		$decode['amount'] = $decode['reduce'] + $based_on_tps;
		
		$decode['comment'] = $changes['comment'];
		
		/*var_dump($reimburse);
		var_dump($twoPercent);
		var_dump($decode['reduce']);
		var_dump($based_on_tps);
		var_dump($decode['amount']);*/
	
		$filename = $decode['title'].'.json';
		$encode = json_encode($decode);
		file_put_contents($filename, $encode);
		return $decode;
	}
	
	public static function getFiles($chdir){
		$dirname = getcwd(); #current working directory m2
		#var_dump($dirname);
		/*if($chdir != "/"){
			chdir($chdir);
			$dirname = getcwd();
		}*/
		#var_dump($dirname);
      $files = [];
      $dir = opendir($dirname);
      while(($file = readdir($dir)) !== false) {
      	if($file !== '.' && $file !== '..' && !is_dir($file) && strpos($file,'json')) {
      		$name = substr($file,0,-5);
         	$files[$file] = $name;
         }
      }
      closedir($dir);
      #sort($files);	
      #var_dump($dirname);
      return $files;
	}
	
	public static function getOriginal(){
		#chdir('m2');
		$dir = getcwd();
		#var_dump($dir);
		$json = file_get_contents($dir.'/original.json');
		$decode = json_decode($json, true);
		return $decode;
	}
	
	public static function getData($fileIndex){
		$files = Demo::getFiles('m2');
		#var_dump($files);
		$name = $files[$fileIndex];
		#var_dump($name);
		$dir = getcwd();
		#var_dump($dir);
		$json = file_get_contents($dir.'/'.$name.'.json');
		$decode = json_decode($json, true);
		#var_dump($decode);
		return $decode;
	}
	
	public static function send_request($first, $last, $email, $request) {
		
		\Package::load("email");
        $e1 = \Email::forge();
        $e1->from($email, $first . ' ' . $last);
        $e1->subject('Demo request from ' . $first . ' ' . $last);
        $e1->to(array(
          'ct310@cs.colostate.edu',
          'csuzjh@rams.colostate.edu' => 'Junhan Zhang',
          //'adhirajp@rams.colostate.edu' => 'Adhiraj Pathak',
         ));
        $e1->body('This is the demo request from ' . $first . ' ' . $last . ': ' . $request);
        try
        {
          $e1->send();
         }
         catch(\EmailValidationFailedException $e)
         {
           echo "The validation failed";
         }
        catch(\EmailSendingFailedException $e)
        {
           echo "The driver could not send the email";
        }
        return true;
	}

}
