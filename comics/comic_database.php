<?

  // Global Variables

   $DBServer   = "localhost";
   $DBUserName = "root";
   $DBPassword = "mysqladmin";
   $DBDataBase = "comics";
   $DBHandle   = 0;

  // 

  function DoesNameExist( $name )
  {
        global $DBHandle;
	$sql = "SELECT * FROM phpComic WHERE name='$name'";
	$res_id = mysql_query($sql, $DBHandle);
	$numrows = mysql_num_rows($res_id );
	
	if( $numrows == 1 )
        {           	           	   
           return $res_id;
        }
        else
           return false;           
  } 
?>  
