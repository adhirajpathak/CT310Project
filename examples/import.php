<?php

//import.php

 $file_data = fopen("table.csv", 'r');
 fgetcsv($file_data);
 while($row = fgetcsv($file_data))
 {
  $data[] = array(
   'emp_id'  => $row[0],
   'emp_name'  => $row[1],
   'income'  => "<input type=\"text\" value=".$row[2].">",
   'cost'  => "<input type=\"text\" value=".$row[3].">",
   'total'  => "<input type=\"text\" value=".$row[4].">"
  );
 }
 echo json_encode($data);


?>
