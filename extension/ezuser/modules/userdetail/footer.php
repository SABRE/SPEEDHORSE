<?php
// modul1/list.php - Funktionsdatei der View list

// notwendige php Bibliothek fuer Templatefunktionen bekannt machen
include_once( "kernel/common/template.php" );
include_once('lib/ezutils/classes/ezhttptool.php');
include_once("kernel/classes/ezcontentobject.php");
include_once( 'lib/ezdb/classes/ezdb.php' );
$http = eZHTTPTool::instance();
$db = eZDB::instance();
$tpl = templateInit();
$viewarr= array();

$startbody='';

$data=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='20' and ezcontentobject.status='1' limit 0,7");
		foreach($data as $row)
		{
			$path=str_replace('_','-',$row['path']);
			$co = eZContentObject::fetch($row['id']);
			$datamap =  $co->contentObjectAttributes('data_map');
			foreach( $datamap as $key => $value ) //looping through each field
			{
				$type = $value->dataType(); //looking at what type the current field is
    			switch( $type->DataTypeString ) //base the switch on the type name
    			{
        	case 'ezimage':
            $content = $value->attribute( 'content' ); 
            $displayText = $content->displayText();
            $imageAlias = $content->imageAlias('original');
            $imagePath = $imageAlias['url'];
            break;
        	case 'ezstring': //for basic text & ints
	    	case 'eztext':
        	case 'ezint':
        	case 'ezfloat':
			case 'ezxmltext':
			case 'ezauthor':
			
	
			if($key=='0'){
				$title=$value->toString();
				print $title;
				}
		break;
        default: 
		} //end of switch
	}//end of inner foreach

	$startbody.='<div class="post" style = "width:45%;float:left;height:130px;">
									<a href="http://localhost/ez45/index.php/ezwebin_site/'.$path.'"><img class="post_thumbnail" src="http://localhost/ez45/'.$imagePath.'" style="border: 0px none;" alt="Section title" title="Section title" width="80" height="80" /></a>
									<h4><a href="http://localhost/ez45/index.php/ezwebin_site/'.$path.'">'.$title.'</a></h4>
									<h3><a href="http://localhost/ez45/index.php/ezwebin_site/'.$path.'">'.$headline.'</a></h3>
									<h5>'.ucfirst($author[0]).'</h5>
									<div class="post_excerpt">
									'.$summary.'
									</div>
								</div>';
	
}

$myoutput=$startbody;
$tpl->setVariable( 'myoutput', $myoutput );

////////////////////////////////////////////FRONTPAGE HEADLINE/////////////////////////////////////////////////

$data1=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='57' and ezcontentobject.status='1' limit 0,7");
		$startbody1.='<ul class="speedhorse_list">';
		foreach($data1 as $row)
		{
			
			$path=str_replace('_','-',$row['path']);
			
			$co = eZContentObject::fetch($row['id']);
			$datamap =  $co->contentObjectAttributes('data_map');
			//print_r($datamap);
			foreach( $datamap as $key => $value ) //looping through each field
			{
			
				$type = $value->dataType(); //looking at what type the current field is
    			switch( $type->DataTypeString ) //base the switch on the type name
    			{
        	case 'ezimage':
            $content = $value->attribute( 'content' ); 
            $displayText = $content->displayText();
            $imageAlias = $content->imageAlias('original');
            $imagePath = $imageAlias['url'];
            //$cli->output( "$key: $displayText ($imagePath)" );
			//print "$key: $displayText ($imagePath)";
			
            break;
        	case 'ezstring': //for basic text & ints
		
        	case 'eztext':
        	case 'ezint':
        	case 'ezfloat':
			case 'ezxmltext':
			case 'ezauthor':
			
			/*if($key=='4'){
				print $value->toString()."aaa";
				}*/

			if($key=='0'){
				$title=$value->toString();
				}
			
						
		break;
        default: 
		} //end of switch
	}//end of inner foreach
	
				
						$startbody1.='<li><a href="http://localhost/ez45/index.php/ezwebin_site/'.$path.'">'.$title.'</a></li>';
						
	
}
$startbody1.='</ul>';


$myoutput1=$startbody1;
$tpl->setVariable( 'myoutput1', $myoutput1 );


//////////////////////FOR PRODUCTS SECTION//////////////////////////////////
$data2=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='21' and ezcontentobject.status='1' limit 0,6");
		$startbody1.='<ul class="speedhorse_list">';
		foreach($data2 as $row)
		{
			
			$path=str_replace('_','-',$row['path']);
			
			$co = eZContentObject::fetch($row['id']);
			$datamap =  $co->contentObjectAttributes('data_map');
			//print_r($datamap);
			foreach( $datamap as $key => $value ) //looping through each field
			{
			
				$type = $value->dataType(); //looking at what type the current field is
				//print_r($value->dataType());
    			switch( $type->DataTypeString ) //base the switch on the type name
    			{
        	case 'ezimage':

            $content = $value->attribute( 'content' ); 
			//print_r($content);
            $displayText = $content->displayText();
            $imageAlias = $content->imageAlias('original');
            $imagePath = $imageAlias['url'];
            //$cli->output( "$key: $displayText ($imagePath)" );
			//print "$key: $displayText ($imagePath)";
			
            break;
			
			case 'ezproductcategory':
            $content = $value->attribute( 'content' ); 
				
           //print_r($content);
			//print $content->Name;
			$categoryname=$content->Name;
	        break;
			
        	case 'ezstring': //for basic text & ints
			case 'ezprice':
        	case 'eztext':
        	case 'ezint':
        	case 'ezfloat':
			case 'ezxmltext':
			case 'ezauthor':
			
			
			
			/*if($key=='4'){
				print $value->toString()."aaa";
				}*/

			if($key=='0'){
				$productname=$value->toString();
				//print $productname;
				}
			if($key=='4'){
				$price1=$value->toString();
				$price=explode('|',$price1);
				}	
					
			
		break;
        default: 
		} //end of switch
	}//end of inner foreach
					$startbody2.='<div class="post" style = "width:45%;float:left;height:130px;"><img src="http://localhost/ez45/'.$imagePath.'" alt="product_thumbnail" />
						<h3><a href="http://localhost/ez45/index.php/ezwebin_site/'.$path.'">'.$productname.'</a></h3>
						<h6>'.$categoryname.'&nbsp;&nbsp;<span>$'.$price[0].'</span></h6>
						</div>';
}
//$startbody1.='</ul>';



$myoutput2=$startbody2;
$tpl->setVariable( 'myoutput2', $myoutput2 );

////////////////////////////////////////////FEATURE HEADLINE ARTICLE START FROM HERE


$data3=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='59' and ezcontentobject.status='1' ");
		$startbody1.='<ul class="speedhorse_list">';
		foreach($data3 as $row)
		{
			
			$path=str_replace('_','-',$row['path']);
			
			$co = eZContentObject::fetch($row['id']);
			$datamap =  $co->contentObjectAttributes('data_map');
			//print_r($datamap);
			foreach( $datamap as $key => $value ) //looping through each field
			{
			
				$type = $value->dataType(); //looking at what type the current field is
    			switch( $type->DataTypeString ) //base the switch on the type name
    			{
        	case 'ezimage':
            $content = $value->attribute( 'content' ); 
            $displayText = $content->displayText();
            $imageAlias = $content->imageAlias('original');
            $imagePath = $imageAlias['url'];
            //$cli->output( "$key: $displayText ($imagePath)" );
			//print "$key: $displayText ($imagePath)";
			
            break;
        	case 'ezstring': //for basic text & ints
		
        	case 'eztext':
        	case 'ezint':
        	case 'ezfloat':
			case 'ezxmltext':
			case 'ezauthor':
			
			/*if($key=='4'){
				print $value->toString()."aaa";
				}*/

			if($key=='0'){
				$title=$value->toString();
				}
			if($key=='2'){
				$headline=$value->toString();
				}
			if($key=='3'){
				$author1=$value->toString();
				$author=explode('|',$author1);
				
				}
			if($key=='4'){
				$summary=$value->toString();
				}
		
			
						
		break;
        default: 
		} //end of switch
	}//end of inner foreach
	
				
						$startbody3.='<div class="post">
								<h4>'.$title.'</h4>
								<h3><a href="http://localhost/ez45/index.php/ezwebin_site/'.$path.'">'.$headline.'</a></h3>	
						<div class="post_excerpt">
							<img class="post_content_image" src="http://localhost/ez45/'.$imagePath.'" alt="post_content_image" />
							<h6><a href="http://localhost/ez45/index.php/ezwebin_site/'.$path.'">'.$summary.'</a></h6>
							<div class="more_link_wrap"><a href="#" class="more_link">more &#xBB;</a></div>
						</div></div>';
						
	
}

$myoutput3=$startbody3;
$tpl->setVariable( 'myoutput3', $myoutput3 );


//////////////////////////FEATURE HEADLINE RIGHT SIDE BOTTOM /////////////////////////////////////////////////////

////////////////////////////////////////////FEATURE HEADLINE ARTICLE START FROM HERE


$data4=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='57' and ezcontentobject.status='1' limit 7,14");
		$startbody1.='<ul class="speedhorse_list">';
		foreach($data4 as $row)
		{
			
			$path=str_replace('_','-',$row['path']);
			
			$co = eZContentObject::fetch($row['id']);
			$datamap =  $co->contentObjectAttributes('data_map');
			//print_r($datamap);
			foreach( $datamap as $key => $value ) //looping through each field
			{
			
				$type = $value->dataType(); //looking at what type the current field is
    			switch( $type->DataTypeString ) //base the switch on the type name
    			{
        	case 'ezimage':
            $content = $value->attribute( 'content' ); 
            $displayText = $content->displayText();
            $imageAlias = $content->imageAlias('original');
            $imagePath = $imageAlias['url'];
            //$cli->output( "$key: $displayText ($imagePath)" );
			//print "$key: $displayText ($imagePath)";
			
            break;
        	case 'ezstring': //for basic text & ints
		
        	case 'eztext':
        	case 'ezint':
        	case 'ezfloat':
			case 'ezxmltext':
			case 'ezauthor':
			
			/*if($key=='4'){
				print $value->toString()."aaa";
				}*/

			if($key=='0'){
				$title=$value->toString();
				}
			
						
		break;
        default: 
		} //end of switch
	}//end of inner foreach
	
				
						$startbody4.='<li><a href="http://localhost/ez45/index.php/ezwebin_site/'.$path.'">'.$title.'</a></li>';
						
	
}
$startbody4.='</ul>';


$myoutput4=$startbody4;
$tpl->setVariable( 'myoutput4', $myoutput4 );

////////////////////////////////////////////FRONT PAGE SMALL ADD/////////////////////////////////////////////////////


$data5=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='61' and ezcontentobject.status='1' limit 0,1");
		
		foreach($data5 as $row)
		{
			
			$path=str_replace('_','-',$row['path']);
			$co = eZContentObject::fetch($row['id']);
			$datamap =  $co->contentObjectAttributes('data_map');
			foreach( $datamap as $key => $value ) //looping through each field
			{
				$type = $value->dataType(); //looking at what type the current field is
    			switch( $type->DataTypeString ) //base the switch on the type name
    			{
        	case 'ezimage':
            $content = $value->attribute( 'content' ); 
            $displayText = $content->displayText();
            $imageAlias = $content->imageAlias('original');
            $imagePath = $imageAlias['url'];
		break;
        default: 
		} //end of switch
	}//end of inner foreach
						$startbody5.='<a href="http://localhost/ez45/index.php/ezwebin_site/'.$path.'"><img src="http://localhost/ez45/'.$imagePath.'"  width="300px" height="100px"/></a>';
}
$myoutput5=$startbody5;
$tpl->setVariable( 'myoutput5', $myoutput5);

////////////////////////////////////////////FRONT PAGE BIG ADD/////////////////////////////////////////////////////

$data6=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='62' and ezcontentobject.status='1' limit 0,1");
		
		foreach($data6 as $row)
		{
			$path=str_replace('_','-',$row['path']);
			$co = eZContentObject::fetch($row['id']);
			$datamap =  $co->contentObjectAttributes('data_map');
			foreach( $datamap as $key => $value ) //looping through each field
			{
				$type = $value->dataType(); //looking at what type the current field is
    			switch( $type->DataTypeString ) //base the switch on the type name
    			{
        	case 'ezimage':
            $content = $value->attribute( 'content' ); 
            $displayText = $content->displayText();
            $imageAlias = $content->imageAlias('original');
            $imagePath = $imageAlias['url'];
        default: 
		} //end of switch
	}//end of inner foreach
						$startbody6.='<a href="http://localhost/ez45/index.php/ezwebin_site/'.$path.'"><img src="http://localhost/ez45/'.$imagePath.'"  width="300px" height="250px"/></a>';
}

$myoutput6=$startbody6;
$tpl->setVariable( 'myoutput6', $myoutput6);


////////////////////////////////////////////FRONT PAGE PARTNER/////////////////////////////////////////////////////

$data7=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='54' and ezcontentobject.status='1' limit 0,3");
		
		foreach($data7 as $row)
		{
			$path=str_replace('_','-',$row['path']);
			$co = eZContentObject::fetch($row['id']);
			$datamap =  $co->contentObjectAttributes('data_map');
			foreach( $datamap as $key => $value ) //looping through each field
			{
				$type = $value->dataType(); //looking at what type the current field is
    			switch( $type->DataTypeString ) //base the switch on the type name
    			{
        	case 'ezimage':
            $content = $value->attribute( 'content' ); 
            $displayText = $content->displayText();
            $imageAlias = $content->imageAlias('original');
            $imagePath = $imageAlias['url'];
        default: 
		} //end of switch
	}//end of inner foreach
						$startbody7.='<div class="featured_partners"><a href="http://localhost/ez45/index.php/ezwebin_site/'.$path.'"><img src="http://localhost/ez45/'.$imagePath.'"  width="90px" height="90px"/></a></div>';
}

$myoutput7=$startbody7;
$tpl->setVariable( 'myoutput7', $myoutput7);

////////////////////////////////////////////FRONTPAGE EVENTS/////////////////////////////////////////////////

$data8=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='40' and ezcontentobject.status='1' order by published desc limit 0,3");
		//$startbody8.='<ul class="speedhorse_list">';
		foreach($data8 as $row)
		{
			
			$path=str_replace('_','-',$row['path']);
			
			$co = eZContentObject::fetch($row['id']);
			$datamap =  $co->contentObjectAttributes('data_map');
			//print_r($datamap);
			foreach( $datamap as $key => $value ) //looping through each field
			{
			
				$type = $value->dataType(); //looking at what type the current field is
    			switch( $type->DataTypeString ) //base the switch on the type name
    			{
        	case 'ezimage':
            $content = $value->attribute( 'content' ); 
            $displayText = $content->displayText();
            $imageAlias = $content->imageAlias('original');
            $imagePath = $imageAlias['url'];
            //$cli->output( "$key: $displayText ($imagePath)" );
			//print "$key: $displayText ($imagePath)";
			
            break;
        	case 'ezstring': //for basic text & ints
		
        	case 'eztext':
        	case 'ezint':
        	case 'ezfloat':
			case 'ezxmltext':
			case 'ezauthor':
			
			/*if($key=='4'){
				print $value->toString()."aaa";
				}*/

			if($key=='0'){
				$title=$value->toString();
				}
			
						
		break;
        default: 
		} //end of switch
	}//end of inner foreach
	
				
						$startbody8.='<li><a href="http://localhost/ez45/index.php/ezwebin_site/'.$path.'">'.$title.'</a></li>';
						
	
}
//$startbody8.='</ul>';


$myoutput8=$startbody8;
$tpl->setVariable( 'myoutput8', $myoutput8);




$Result = array();
$Result['content'] = $tpl->fetch( 'design:userdetail/footer.tpl' );

?>