<!--

phpComic - Created By: Ryan Phillips - An opensourced PHP Comic Strip Parser
Copyright (C) 2000 Ryan Phillips

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
	
--!>

<!-- Start Code --!>
<?
  include "comic_database.php";
  include "comic_functions.php";
  
  /* Function:   ConnectAndParse    
     Parameters: $name    = The name of comic. (String)
                 $url     = The url to the html page that includes the comic. (String)
                 $findstr = The string to search for in $url.  (String)
     Purpose:
                 If the database functions are enabled this function will search through
                 the database for the comic's name as the key returning the image's url.
                 If the date is greater than the date stored in the database, the function
                 will try and update the url.

                 If the database is disabled then the remote page will be loaded and parsed 
                 through until the $findstr string is found.

     Return:
                 Success:
                   Returns the url to the image of the comic.
                 Failed:
                   Returns an empty string: ""
                  
  */
  function ConnectAndParse( $name, $url, $findstr )
  {
	// Find Current Date
        global $DBHandle, $DBDataBase;
        $DateToday = date("Ymd");
        $Update = false;  // Variable to look after updates after DoesNameExist is called.
        $Found = false;

        if( $DBHandle > 0 ) // Look Up link in table;
        {           
           if( ($res_id=DoesNameExist( $name )) != false )
                {                   
                   $myrow = mysql_fetch_row($res_id);  
                   if( $myrow[1] < $DateToday ) // Stored Date is less than today.  Update
                   {
                      $Update = true;
                      $Found = true;
                   }
                   else
                   {                      
                      $Location = $myrow[2];  // Location
                   }   
                }
           else
           {
              $Found = false;
              $Update = true;
           }           
        }
        else
        {
           $Update = true;
        }
        
        // Find ImageUrl, and update database if needed
        if( $Update == true )
        {
	  	$file = fopen($url, "r");
		if( !$file )
  		{
		  echo "ERROR: could not open file $url<BR>\n";
		  return "";
		} 
  	
	 	while( !feof($file))
		{
			$line = fgets($file, 1024);
    			$response = strstr( $line, $findstr );
			if( $response != false )
				break;
		} 	
		$pos = strpos( $response, "\"" );  	
  		$Location = substr( $response, 0, $pos );
		if ($Location == "")
		  {
		    $pos = strpos( $response, "'" );
		    $Location = substr( $response, 0, $pos);
		  }

		if( $DBHandle > 0 ) // Add to Table
         	{
         	   if( $Found == true )
         	   {
         	      $sql = "replace INTO phpComic VALUES ('$name', $DateToday, '$Location')";         	      
         	   }
         	   else
         	      $sql = "INSERT INTO phpComic VALUES ('$name', $DateToday, '$Location')";
           	   
           	   if( strstr( $Location, $myrow[2] ) == false )  // On an new day, make sure there is a new comic.
           	   {           	      
           	      $res_id = mysql_query($sql);
           	   }
           	   else // If there is no new comic, just fall through and don't update database
           	   {           	        
			// Fall through           	      
           	   }
           	}
	
	   fclose($file);     
	}
	return $Location;	
}

  /* Function:   ConnectAndParseRegexp

  */
  function ConnectAndParseRegexp( $url, $findstr, $regexp )
  {
  	$file = fopen($url, "r");
  	if( !$file )
  	{
    		echo("could not open file");
		exit;
  	} 
  	
  	while( !feof($file))
  	{
    		$line = fgets($file, 1024);
		if (ereg($regexp, $line) == true)
		  {
		    $response = strstr( $line, $findstr );
		    if( $response != false )
		      break;
		  }
	} 
	$pos = strpos( $response, "\"" );
  	$Location = substr_replace( $response, '', $pos, strlen($response) );
	if ($Location == "")
	  {
	    $pos = strpos( $response, "'" );
	    $Location = substr_replace($response, '', $pos, strlen($response));
	  }
  	
	fclose($file);     
	return $Location;
  }
 
?>
