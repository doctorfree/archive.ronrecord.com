<?

  
  function DoUserFriendly()
  {
     $CurrentMonth = date("M");
     $CurrentYear  = date("y");
     $CurrentMonth = strtolower($CurrentMonth);
     
     $path = sprintf("%s%s", $CurrentYear, $CurrentMonth );
     $url = ConnectAndParse("UserFriendly", "http://www.userfriendly.org/static", "http://www.userfriendly.org/cartoons/archives/$path/" );     
     
     return $url;	
  }
  
  function DoPennyArcade()
  {
     $CurrentYear  = date("Y");     
          
     $url = ConnectAndParse("PennyArcade", "http://www.penny-arcade.com/view.php3", "images/$CurrentYear/" );
     $ret = sprintf("%s%s", "http://www.penny-arcade.com/", $url );
     return $ret;	
  }
  
  function DoHelpDex()
  {
     $CurrentYear = date("Y");

     $url = ConnectAndParse("HelpDex", "http://linuxtoday.com/helpdex", "/helpdex/$CurrentYear");
     $ret = sprintf("%s%s", "http://www.linuxtoday.com", $url );
     return $ret;
  }
  
  function DoSpazLabs()
  {
     $CurrentYear = date("y");     
     $url = ConnectAndParse("SpazLabs", "http://www.stonebrokestudios.com/", "spaz/$CurrentYear");     
     $ret = sprintf("%s%s", "http://www.stonebrokestudios.com/", $url );
     return $ret;
  }
  
  function DoDilbert()
  {
     $url = ConnectAndParse("Dilbert", "http://www.comics.com/comics/dilbert/", "/comics/dilbert/archive/images/dilbert");         
     $ret = sprintf("%s%s", "http://www.comics.com", $url );
     return $ret;
  }

   function doUnitedMedia($name)
   {
      //Added by: nchip
      //Supports atleast: dilbert,pcnpixel
      $url=ConnectAndParse($name, "http://www.unitedmedia.com/universal/$name/ab.html","/universal/$name/archive/images/$name");
      $ret = sprintf("%s%s", "http://www.unitedmedia.com", $url );
      return $ret;
   }

   function doComicsDotCom($name)
   {
      //Supports atleast: garfield
      $url=ConnectAndParse($name, "http://www.unitedmedia.com/comics/$name/","/comics/$name/archive/images/$name");
      $ret = sprintf("%s%s", "http://www.unitedmedia.com", $url );
      return $ret;
   }
   
   function doKingFeatures($name)
   {
    
     $url = ConnectAndParse($name, "http://www.kingfeatures.com/features/comics/$name/aboutBody.php", "http://est.rbma.com/content/");
     $ret = $url;
     return $ret;
   }

  function DoRealLifeComics()
  {
     $url = ConnectAndParse("RealLife", "http://www.reallifecomics.com/", "/comics/" );
     $ret = sprintf("%s%s", "http://www.reallifecomics.com", $url );
     return $ret;
  }
  
  function DoSinfest()
  {     
     $url = ConnectAndParse("Sinfest", "http://www.sinfest.net/", "/comics" );
     $ret = sprintf("%s%s", "http://www.sinfest.net/", $url );
     return $ret;
  }
  
  function DoInkTank()
  {     
     $url = ConnectAndParse("InkTank", "http://www.inktank.com/", "/images/cartoons" );
     $ret = sprintf("%s%s", "http://www.inktank.com/", $url );
     return $ret;
  }
  
  function DoKrazyLarry()
  {    
     $url = ConnectAndParse("KrazzyLarry", "http://www.krazylarry.com/", "/comics/kl" );
     $ret = sprintf("%s%s", "http://www.krazylarry.com/", $url );
     return $ret;
  }

  function DoGoats()  // Added by Cort
  {
     $url = ConnectAndParse("Goats", "http://www.goats.com/", "/comix/");
     $ret = sprintf("%s%s", "http://www.goats.com/", $url );
     return $ret;
  }

  function DoJerkCity()
  {
	$frame_url = ConnectAndParseRegexp("http://www.jerkcity.com/",
					   "jerkcity", "jerkcity[0-9]");
	$url = ConnectAndParseRegexp("http://www.jerkcity.com/$frame_url",
				     "jerkcity", "jerkcity[0-9]");
	$ret = sprintf("%s%s", "http://www.jerkcity.com/", $url);
	return $ret;
  }
  
   function DoMegatokyo()
   {
       $url = ConnectAndParse("Megatokyo", "http://www.megatokyo.com/", "strips/" );
       $imageUrl="http://www.megatokyo.com/".$url;
       return $imageUrl;
   }


   function DoPvp()
   {
       $CurrentYear  = date("Y");
       $path = $CurrentYear;
       $url = ConnectAndParse("PVP", "http://www.pvponline.com/", "archive/$path/" );
       $imageUrl="http://www.pvponline.com/".$url;
       return $imageUrl;
   }

   function DoGarfield()
   {
      $CurrentMonth = date("M");
      $CurrentYear  = date("y");
      $CurrentMonth = strtolower($CurrentMonth);

      $path = $CurrentYear . $CurrentMonth;
      $url = ConnectAndParse("Garfield", "http://www.garfield.com/comics/pages/", "/comics/strips" );
      $imageUrl="http://www.garfield.com/".$url;
      return $imageUrl;
   }

   function DoDoonesbury()
   {
     $fullyear = date("Y");
     $month = date("m");
     $datestring = date("ymd", time()/*-14*86400*/ );
     $ret = "http://a1736.g.akamai.net/7/1736/1392/1dcf6501c8f9fa/images.ucomics.com/comics/db/$fullyear/db$datestring.gif";
     return $ret;
   }

   function DoFoxTrot()
   {
     $datestring = date("ymd", time()-14*86400);
     $ret = "http://www.foxtrot.com/comics/strips/ft$datestring.gif";
     return $ret;
   }

   function DoThinHLine()
   {
     $url = ConnectAndParse("Thin H Line",
			    "http://www.thinhline.com/thisweek.html",
			    "thlcomic/");
     $ret = sprintf("%s%s", "http://www.thinhline.com/", $url);
     return $ret;
   }

   function DoPLIF()
   {
     $ret = "http://www.plif.com/thisweek.gif";
     return $ret;
   }

   function DoRedMeat()
   {
     $ret = "http://www.redmeat.com/redmeat/current/index-1.gif";
     return $ret;
   }
?>
