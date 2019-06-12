<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
<h3>Dropdown list always point to the first item in the list</h3>
<?php 
	use \Model\Demo;
	$files = Demo::getFiles('/');
	echo Form::open(array('action' => 'index/home/submit', 'method' => 'post'));
	echo Form::select('myDropDown','none',$files,array('selected'));
	echo "&nbsp";
	echo Form::button('load-btn', 'Load', array('class' => 'load'));
	echo Form::close();
?>
	
<!---------------------- Reading contents from json file------------------>
<?php 
	echo Form::open(array('action' => 'index/home/whatif', 'method' => 'post'));
?>
	<h3>The <u>Title</u> will be your new file's name. Don't forget to change it otherwise your original data will be overwritten.</h3>
	<h2>Title: <?php echo Form::input('title', $decode["title"]);?></h2>
	<h2>Total Performance Score (TPS): <span id="tps"><?php echo $decode['tps'];?> </span></h2>
	<h2>The Expected Medical Reimbursement: <span id="reimbursement">$<?php echo Form::input('reimbursement', $decode["reimbursement"]);?> </span></h2>
	<h2>The Amount After Reduction: <span id="reduction">$<?php echo $decode['reduction'];?> </span></h2>
	<h2>The Redistributed Amount Based on TPS: <span id="amount">$<?php echo $decode['amount'];?></span> </h2>
<?php		
		foreach($decode as $key => $val){ # Iterating through the arrays 'title', 'tps'
			if(is_array($val)){ 
?>			
			<br>
			<h2><span><?php echo strtoupper($key[0]) . substr($key,1) . " Measurements";?></span></h2> <!--label for each table-->
			<table border='1' class="table">
			<?php 
				$header = array("","Achievement Threadshold", "Benchmark", "Baseline Rate", 
						"Performance Rate", "Achivement Points (Out of 10",
						"Improvement Point (Out of 9)", "Measure Score (Out of 10)");
				if($key == "experience"){
					array_splice( $header, 1, 0, array("Floor"));			
				}
					foreach ($header as $h) {
			?>
   				<th class="table-header"><?php echo $h;?></th> <!--dynamically print out headers-->
			<?php	}	#end of header for loop
	 			foreach($val as $k => $v){?> 
				<tr>
				<td class="label"> 
				<?php echo $v["id"]; ?>
				</td>
				<?php 
					if($key == "experience") {
						echo	"<td class='cell'>".$v["floor".$k] . "</td>".
								"<td class='cell'>".$v[$key."threadshold".$k] . "</td>".
								"<td class='cell'>".$v[$key."benchmark".$k]."</td>";
					}
					else{
				?>
					<?php echo	"<td class='cell'>".$v[$key."threadshold".$k] . "</td>".
									"<td class='cell'>".$v[$key."benchmark".$k]."</td>";
					}?>
									<td><?php echo Form::input($key.'baseline'.$k, $v[$key.'baseline'.$k], array('class' => 'cell'));?></td>
									<td><?php echo Form::input($key.'performance'.$k, $v[$key.'performance'.$k], array('class' => 'cell'));?></td>
						
					<?php echo	"<td class='cell' id='achievement'>".$v[$key."achievement".$k] . "</td>".
									"<td class='cell' id='improvement'>".$v[$key."improvement".$k] . "</td>".
									"<td class='cell' id='measure'>" . $v[$key."measure".$k] ."</td>";?>
				</tr>
		<?php }?>
		</table>
		<br>
	<?php	}
		}
	?>
	
	<br><br>
	<table border="1" class="table"> 
		<th class="table-header">Base Score</th>
		<th class="table-header">Consistency</th>
		<tr>
			<td class="cell"><?php echo $decode['basescore'];?></td>		
			<td class="cell"><?php echo $decode['consistency'];?></td>	
		</tr>	
	</table>
	<br><br>
	<?php echo Form::textarea('comment', $decode['comment'], array('rows' => 5, 'cols' => 50)); ?>
	<br><br><br>
	<?php 	
		echo Form::button('update-btn', 'Update', array('class' => 'update'));
		echo Form::close();
	?>
	<br><br><br>
</body>
</html>
