<?
  // DOMElement->getElementsByTagName() -- Gets elements by tagname
  // nodeValue : The value of this node, depending on its type.
  // Load XML File. You can use loadXML if you wish to load XML data from a string

  $objDOM = new DOMDocument();
  $objDOM->load("test.xml"); //make sure path is correct


  $note = $objDOM->getElementsByTagName("note");
  // for each note tag, parse the document and get values for
  // tasks and details tag.

  foreach( $note as $value )
  {
    $tasks = $value->getElementsByTagName("tasks");
    $task  = $tasks->item(0)->nodeValue;


    $details = $value->getElementsByTagName("details");
    $detail  = $details->item(0)->nodeValue;

    echo "$task :: $detail <br>";
  }


?> 
