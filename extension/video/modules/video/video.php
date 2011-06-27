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
$myoutput='';			
$startbody1='';
$myoutput1='';			
$startbody2='';
$myoutput2='';			
$startbody3='';
$myoutput3='';			
$startbody4='';
$myoutput4='';			
$startbody5='';
$myoutput5='';			
$startbody6='';
$myoutput6='';			
$startbody7='';
$myoutput7='';			
$startbody8='';
$myoutput8='';			
$startbody9='';
$myoutput9='';			
$startbody10='';
$myoutput10='';			

/////////////////////////////////////////////////////////SPECIAL FOCUS TITLE///////////////////////////////////////////////////////////////////////////

$data4=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='129' and ezcontentobject.status='1' order by published desc limit 0,1");
		//$startbody1.='<ul class="speedhorse_list">';
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
			case 'ezselection':
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
				$author1=$value->toString();
				$author=explode('|',$author1);
				
				}
			if($key=='3'){
				$summary1=$value->toString();
				$summary=substr($summary1,0,700);
				}
						
		break;
        default: 
		} //end of switch
	}//end of inner foreach	
				
						$startbody4.='<a href="http://sandbox.speedhorse.com/'.$path.'"><img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/featured_post_top.jpg" class="post_img" /></a>
                <div class="post_content">
                    <h2>'.$title.'</h2>
                    <p>'.ucfirst($author[0]).'</p>
                    <p>'.$summary.'...</p>
                    <div style="text-align:right"><a href="http://sandbox.speedhorse.com/RACING-NEWS/Features" class="more_link">Read More &raquo;</a></div>
                </div>';
						
	
}
//$startbody1.='</ul>';

$myoutput4=$startbody4;
$tpl->setVariable( 'myoutput4', $myoutput4);

		
		
///////////////////////////////////////////////////FEATURE STORIES//////////////////////////////////////////////////////////////////////////////////////////////////
$chk1=1;	
$data=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='127' and ezcontentobject.status='1' order by published desc limit 0,8");
		foreach($data as $row)
		{
			//print $row['id'];
			//$innerbody.='<tr>';
			//$viewarr1[$j]['name']=$row['name'];
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
			case 'ezselection':
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
				$summary1=$value->toString();
				$summary=substr($summary1,0,325);
				}

		//print $key.' ' . $type->DataTypeString . ' - ' . $value->toString();
		break;
        default: 
		} //end of switch
	}//end of inner foreach
	
				if($chk1%2==1)
				{
				$startbody.='<tr><td><div class="post">
												<a href="http://sandbox.speedhorse.com/'.$path.'"><img class="post_thumbnail" src="http://sandbox.speedhorse.com/'.$imagePath.'" style="border: 0px none;" alt="Section title" title="Section title" width="80" height="80" /></a>
												<h4><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></h4>
												<h3><a href="http://sandbox.speedhorse.com/'.$path.'">'.$headline.'</a></h3>
												<h5>'.ucfirst($author[0]).'</h5>
												<div class="post_excerpt">
												'.$summary.'
												</div>
											</div></td>';
				}
				if($chk1%2==0)
					{
					$startbody.='<td><div class="post">
												<a href="http://sandbox.speedhorse.com/'.$path.'"><img class="post_thumbnail" src="http://sandbox.speedhorse.com/'.$imagePath.'" style="border: 0px none;" alt="Section title" title="Section title" width="80" height="80" /></a>
												<h4><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></h4>
												<h3><a href="http://sandbox.speedhorse.com/'.$path.'">'.$headline.'</a></h3>
												<h5>'.ucfirst($author[0]).'</h5>
												<div class="post_excerpt">
												'.$summary.'
												</div>
											</div></td></tr>';					
					}							
	$chk1=$chk1+1;
}

$myoutput=$startbody;
$tpl->setVariable( 'myoutput', $myoutput );

////////////////////////////////////////////SPECIAL HEADLINE/////////////////////////////////////////////////

$data1=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='129' and ezcontentobject.status='1' order by published desc limit 1,11");
		//$startbody1.='<ul class="speedhorse_list">';
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
	
				
						$startbody1.='<li><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></li>';
						
	
}
//$startbody1.='</ul>';


$myoutput1=$startbody1;
$tpl->setVariable( 'myoutput1', $myoutput1 );



////////////////////////////////////////////FEATURE HEADLINE ARTICLE START FROM HERE


$data2=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='128' and ezcontentobject.status='1' order by published desc LIMIT 0,3");
		
		foreach($data2 as $row)
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
				$summary1=$value->toString();
				$summary=substr($summary1,0,400);
				}
						
		break;
        default: 
		} //end of switch
	}//end of inner foreach
	
				
						$startbody2.='<h4>'.$title.'</h4>
						<h3><a href="http://sandbox.speedhorse.com/'.$path.'">'.$headline.'</a></h3>
						<div class="post_excerpt">
							<a href="http://sandbox.speedhorse.com/'.$path.'"><img class="post_content_image" src="http://sandbox.speedhorse.com/'.$imagePath.'" alt="post_content_image" style="width:87px;height=100px;"/></a>
							<h6><a href="http://sandbox.speedhorse.com/'.$path.'">'.$summary.'...</a></h6>
							<div class="more_link_wrap"><a href="http://sandbox.speedhorse.com/RACING-NEWS/Features" class="more_link">more &#xBB;</a></div>
						</div>';
}

$myoutput2=$startbody2;
$tpl->setVariable( 'myoutput2', $myoutput2);

////////////////////////////////////////////FRONT PAGE SMALL ADD/////////////////////////////////////////////////////


$data5=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='131' and ezcontentobject.status='1' order by published desc limit 0,1");
		
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
						$startbody5.='<a href="http://sandbox.speedhorse.com/'.$path.'"><img src="http://sandbox.speedhorse.com/'.$imagePath.'"  width="300px" height="100px"/></a>';
}
$myoutput5=$startbody5;
$tpl->setVariable( 'myoutput5', $myoutput5);

////////////////////////////////////////////FRONT PAGE BIG ADD/////////////////////////////////////////////////////

$data6=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='130' and ezcontentobject.status='1' order by published desc  limit 0,1");
		
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
						$startbody6.='<a href="http://sandbox.speedhorse.com/'.$path.'"><img src="http://sandbox.speedhorse.com/'.$imagePath.'"  width="300px" height="250px"/></a>';
}

$myoutput6=$startbody6;
$tpl->setVariable( 'myoutput6', $myoutput6);


////////////////////////////////////////////FRONT PAGE PARTNER/////////////////////////////////////////////////////

$data7=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='132' and ezcontentobject.status='1' order by published desc limit 0,3");
		
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
						$startbody7.='<div class="featured_partners"><a href="http://sandbox.speedhorse.com/'.$path.'"><img src="http://sandbox.speedhorse.com/'.$imagePath.'"  width="90px" height="90px"/></a></div>';
}

$myoutput7=$startbody7;
$tpl->setVariable( 'myoutput7', $myoutput7);

////////////////////////////////////////////EVENTS/////////////////////////////////////////////////

$data8=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='40' and ezcontentobject.status='1' order by published desc limit 0,4");
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
						$startbody8.='<li><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></li>';
}
//$startbody8.='</ul>';

 
$myoutput8=$startbody8;
$tpl->setVariable( 'myoutput8', $myoutput8);

////////////////////////////////////////////Normal Article ////////////////////////////////////////////////////////////////////////////

$chk=0;
$data9=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='16' and ezcontentobject.status='1' order by published desc LIMIT 0,2");
		
		foreach($data9 as $row)
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
				if($chk=='0')
				{
					$startbody9.='<div class="post" style="background-image:url(http://sandbox.speedhorse.com/'.$imagePath.');">
                	<a href="http://sandbox.speedhorse.com/'.$path.'"><h3>'.$title.'</h3></a></div>';
				}
				elseif($chk=='1')
				{
				$startbody9.='<div class="post right_post" style="background-image:url(http://sandbox.speedhorse.com/'.$imagePath.');">
                	<a href="http://sandbox.speedhorse.com/'.$path.'"><h3>'.$title.'</h3></a></div>';
				}
				$chk=$chk+1;
}

$myoutput9=$startbody9;
$tpl->setVariable( 'myoutput9', $myoutput9);

$Result = array();
$Result['content'] = $tpl->fetch( 'design:video/video.tpl' );

?>