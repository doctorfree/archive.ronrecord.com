<?php

if(isset($mainfile)) { include("mainfile.php"); }
include("header.php");
include("comic_backend.php");

  function PrintComic( $Name, $PictureUrl, $Link )
  {
    $html = " 
          
          <tr><td bgcolor=CCCCCC>
          <font face=Arial,Helvetica size=2>
          <b><a href=\"$Link\">$Name</a></b><br>
	  </td>
          </tr>
          <tr>
          <td bgcolor=999999 valign=middle>
          <font face=Arial,Helvetica><center><img src=\"$PictureUrl\"></center>
          </td></tr>          
          </td>
          </tr>\n";
   
    
    echo( $html );
    flush(); //Added by nielsj
  
  }


?>

<font face=Arial,Helvetica size=3>
<p align=justify>
<center> All comic copyrights are held by their respective owner.  <br>
Please visit the sites to support the continuation of the comic.<br>

<table border=0 cellpadding=3 cellspacing=1 width=100%>

<?
   if( ($DBHandle = @mysql_connect ( $DBServer, $DBUserName, $DBPassword))  < 0 )
   {
      $DBHandle = 0;
   }
   else
   {
      @mysql_select_db( $DBDataBase, $DBHandle );
   }

  $PictureLocation = DoUserFriendly();
  PrintComic( "User Friendly", $PictureLocation, "http://www.userfriendly.org/" );

  $PictureLocation = DoPennyArcade();
  PrintComic( "Penny Arcade", $PictureLocation, "http://www.penny-arcade.com/");

  $PictureLocation = DoHelpDex();
  PrintComic( "HelpDex", $PictureLocation, "http://www.linuxtoday.com/helpdex");
  
  $PictureLocation = DoSpazLabs();
  PrintComic( "Spaz Labs", $PictureLocation, "http://www.stonebrokestudios.com/"); 
 
  $PictureLocation = DoDilbert();
  PrintComic( "Dilbert", $PictureLocation, "http://www.comics.com/comics/dilbert/ab.html" );
  
  $PictureLocation = DoRealLifeComics();
  PrintComic( "Real Life", $PictureLocation, "http://www.reallifecomics.com/" );
  
  $PictureLocation = DoSinfest();
  PrintComic( "Sinfest", $PictureLocation, "http://www.sinfest.net/" );
   
  $PictureLocation = DoInkTank();
  PrintComic( "Inktank", $PictureLocation, "http://www.inktank.com/" );
  
  $PictureLocation = DoKrazyLarry();
  PrintComic( "Krazy Larry", $PictureLocation,   "http://www.krazylarry.com/" );

  if( $DBHandle > 0 )
     mysql_close($DBHandle);
?>



       </table><br>
       
<!-- Footer --!>
</p>
<?php
include("footer.php");
?>

