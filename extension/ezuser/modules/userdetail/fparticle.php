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
$startbody11='';
$myoutput11='';			
$startbody12='';
$myoutput12='';			
$startbody13='';
$myoutput13='';		

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

////////////////////////////////////////////FRONTPAGE HEADLINE/////////////////////////////////////////////////

$data1=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='129' and ezcontentobject.status='1' order by published desc limit 0,7");
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


//////////////////////FOR PRODUCTS SECTION//////////////////////////////////
$data2=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='21' and ezcontentobject.status='1' order by published desc limit 0,6");
		
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
			if($key=='0'){
				$productname=$value->toString();
				//print $productname;
				}
			if($key=='7'){
				$price1=$value->toString();
				$price=explode('|',$price1);
				}	
		break;
        default: 
		} //end of switch
	}//end of inner foreach  
	//for price <span>$'.$price[0].'</span>
		$startbody2.='<div class="post" style = "width:140px;float:left;height:140px;"><a href="http://sandbox.speedhorse.com/'.$path.'"><img src="http://sandbox.speedhorse.com/'.$imagePath.'" alt="product_thumbnail" width="140px" height="100px"  /></a>
						<h3><a href="http://sandbox.speedhorse.com/'.$path.'">'.$productname.'</a></h3>
						<h6>'.$categoryname.'&nbsp;&nbsp;</h6>
						</div>';
}

$myoutput2=$startbody2;
$tpl->setVariable( 'myoutput2', $myoutput2 );

////////////////////////////////////////////FEATURE HEADLINE ARTICLE START FROM HERE


$data3=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='128' and ezcontentobject.status='1' order by published desc LIMIT 0,1");
		
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
								<h3><a href="http://sandbox.speedhorse.com/'.$path.'">'.$headline.'</a></h3>	
						<div class="post_excerpt">
							<a href="http://sandbox.speedhorse.com/'.$path.'"><img class="post_content_image" src="http://sandbox.speedhorse.com/'.$imagePath.'" alt="post_content_image" style="width:87px;height=100px;"/></a>
							<h6><a href="http://sandbox.speedhorse.com/'.$path.'">'.$summary.'</a></h6>
							<div class="more_link_wrap"><a href="http://sandbox.speedhorse.com/RACING-NEWS/Features" class="more_link">more &#xBB;</a></div>
						</div></div>';
}

$myoutput3=$startbody3;
$tpl->setVariable( 'myoutput3', $myoutput3 );


//////////////////////////FEATURE HEADLINE RIGHT SIDE BOTTOM /////////////////////////////////////////////////////

////////////////////////////////////////////FEATURE HEADLINE ARTICLE START FROM HERE


$data4=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='129' and ezcontentobject.status='1' order by published desc limit 7,14");
		//$startbody4.='<ul class="speedhorse_list">';
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
			$startbody4.='<li><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></li>';
}
//$startbody4.='</ul>';

$myoutput4=$startbody4;
$tpl->setVariable( 'myoutput4', $myoutput4);

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

$data6=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='130' and ezcontentobject.status='1' order by published desc limit 0,1");
		
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

////////////////////////////////////////////FRONTPAGE EVENTS/////////////////////////////////////////////////

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

/////////////////////////////////////////////////Blog///////////////////////////////////////////////////////////////////////////
$imagePathuser="";
$data9=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name,ezcontentobject.owner_id as ownerid,ezcontentobject.published as publish, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='20' and ezcontentobject.status='1' order by published desc limit 0,2");
		foreach($data9 as $row)
		{
			
			//////////////////////////////////////////////Star rating/////////////////////////////////////////////
			$data10=$db->arrayQuery("SELECT contentobject_id,rating_average,rating_count FROM `ezstarrating` WHERE contentobject_id = '".$row['id']."'");
			$rate=$data10['0']['rating_average'];
				for($i=0;$i<round($rate);$i++)
				{
					$startbody10.='&#x2605;';
				}
				if(round($rate)<5)
				{
					for(;$i<5;$i++)
					{
						$startbody11.='<span class="unattained_star">&#x2605;</span>';
					}
				}	
/*			
			if($data10['0']['rating_average']=='0')
				{
				$startbody10='<span class="unattained_star">&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;</span>';
				}
			elseif($data10['0']['rating_average']=='1')
				{
				$startbody10='&#x2605;<span class="unattained_star">&#x2605;&#x2605;&#x2605;&#x2605;</span>';
				}
			if($data10['0']['rating_average']=='2')
				{
				$startbody10='&#x2605;&#x2605;<span class="unattained_star">&#x2605;&#x2605;&#x2605;</span>';
				}
			if($data10['0']['rating_average']=='3')
				{
				$startbody10='&#x2605;&#x2605;&#x2605;<span class="unattained_star">&#x2605;&#x2605;</span>';
				}
			if($data10['0']['rating_average']=='4')
				{
				$startbody10='&#x2605;&#x2605;&#x2605;&#x2605;<span class="unattained_star">&#x2605;</span>';
				}
			if($data10['0']['rating_average']=='5')
				{
				$startbody10='&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;';
				}					
*/			
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$currentUser = eZUser::fetch($row['ownerid']);
			$contentObject = $currentUser->attribute( 'contentobject' );
			$dataMap1 = $contentObject->attribute( 'data_map' );
			{
			foreach( $dataMap1 as $key => $value ) //looping through each field
				{
    			$type = $value->dataType(); //looking at what type the current field is
    			switch( $type->DataTypeString ) //base the switch on the type name
    				{
        			case 'ezimage':
            		$content = $value->attribute( 'content' ); 
            		$displayText = $content->displayText();
            		$imageAlias = $content->imageAlias('original');
            		$imagePathuser = $imageAlias['url'];
					if($imagePathuser!="")
					{
						//$tpl->setVariable( 'imagePath', $imagePath );
					}
					else
					{
						$imagePathuser="var/ezwebin_site/storage/images/noimages.jpg";
						//$tpl->setVariable( 'imagePath', $imagePath );
					}
			//$cli->output( "$key: $displayText ($imagePath)" );
			//print "$key: $displayText ($imagePath)";
            		break;
			        default: //by default let's show what the type is (along with the toString representation):
            		//$cli->output( $key.' ' . $type->DataTypeString . ' - ' . $value->toString() );
					//print $key.' ' . $type->DataTypeString . ' - ' . $value->toString();
        			break;
    				}
				}
			}
			
			///////////////////////////////////////////////////
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
			//print $imagePath;
            break;
        	case 'ezstring': //for basic text & ints
	    	case 'eztext':
        	case 'ezint':
        	case 'ezfloat':
			case 'ezxmltext':
			case 'ezauthor':
			case 'ezdatetime':
	
			if($key=='0'){
				$title=$value->toString();
				//print $title;
				}
				
			if($key=='1'){
				$headline=$value->toString();
				//print $headline;
				}
			if($key=='2'){
				$author1=$value->toString();
				$author=explode('|',$author1);
				//print $author;
				}
			if($key=='3'){
				$body1=$value->toString();
				
				//print $body;
				}
				if($key=='4'){
				$publishdate1=$value->toString();
				$publishdate=date("D, F d, Y", $publishdate1);
				}
			
		break;
        default: 
		} //end of switch
	}//end of inner foreach
	if($imagePath!="")
	{
	$body=substr($body1,0,325);
	$startbody9.='<div class="post" style="width:280px;">
								<img class="post_thumbnail" src="http://sandbox.speedhorse.com/'.$imagePathuser.'" alt="post_thumbnail"  width="33px" height="33px"/>
								<h3><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></h3>
								<h5><a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['ownerid'].'">'.ucfirst($author[0]).'</a></h5>
								<div class="post_excerpt">
									<img class="post_content_image" src="http://sandbox.speedhorse.com/'.$imagePath.'" alt="post_content_image" style="width:87px;height=100px;"/>
									<h6><a href="http://sandbox.speedhorse.com/'.$path.'">'.$headline.'</a></h6>
									'.$body.'
								</div>
								<div class="more_link_wrap"><a href="http://sandbox.speedhorse.com/'.$path.'" class="more_link">read more &#xBB;</a></div>
								<div class="post_meta">
									<h6 class="post_date">posted on: '.$publishdate.'</h6>
									<h4><a href="http://sandbox.speedhorse.com/'.$path.'">21 comments</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="http://sandbox.speedhorse.com/'.$path.'">219 views</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="http://sandbox.speedhorse.com/'.$path.'">rating</a> '.$startbody10.$startbody11.'</h4>
								</div>
							</div>';
	$startbody10="";
	$startbody11="";						
	}
	else
	{
	$body=substr($body1,0,500);
	$startbody9.='<div class="post">
								<img class="post_thumbnail" src="http://sandbox.speedhorse.com/'.$imagePathuser.'" alt="post_thumbnail"  width="33px" height="33px" />
								<h3><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></h3>
								<h5><a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['ownerid'].'">'.ucfirst($author[0]).'</a></h5>
								<div class="post_excerpt">
								<h6><a href="http://sandbox.speedhorse.com/'.$path.'">'.$headline.'</a></h6>
								'.$body.'
								</div>
								<div class="more_link_wrap"><a href="http://sandbox.speedhorse.com/'.$path.'" class="more_link">read more &#xBB;</a></div>
								<div class="post_meta">
									<h6 class="post_date">posted on: '.$publishdate.'</h6>
									<h4><a href="http://sandbox.speedhorse.com/'.$path.'">21 comments</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="http://sandbox.speedhorse.com/'.$path.'">219 views</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="http://sandbox.speedhorse.com/'.$path.'">rating</a> '.$startbody10.$startbody11.'</h4>
								</div>
							</div>';
	$startbody10="";
	$startbody11="";						
	}
		
}

$myoutput9=$startbody9;
$tpl->setVariable( 'myoutput9', $myoutput9 );

////////////////////////////////////////////News Sponsored/////////////////////////////////////////////////

$data12=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='142' and ezcontentobject.status='1' order by published desc limit 0,1");
		//$startbody8.='<ul class="speedhorse_list">';
		foreach($data12 as $row)
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
			//print "http://localhost/ez45/".$imagePath;
			
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
			if($key=='8'){
				$website=$value->toString();
				//print $website;
				}
			
		break;
        default: 
		} //end of switch
	}//end of inner foreach
						$startbody12.='<a id="features_ad_link" href="'.$website.'" target="_blank"><img src="http://sandbox.speedhorse.com/var/ezwebin_site/storage/images/sponsored.png" width="65px" height="28px"><img src="http://sandbox.speedhorse.com/'.$imagePath.'" alt="post_thumbnail" width="154px" height="28px"/></a>';
}

$myoutput12=$startbody12;
$tpl->setVariable( 'myoutput12', $myoutput12);

////////////////////////////////////////////Sponsored blog/////////////////////////////////////////////////

$data13=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='141' and ezcontentobject.status='1' order by published desc limit 0,1");
		//$startbody8.='<ul class="speedhorse_list">';
		foreach($data13 as $row)
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
			//print "http://localhost/ez45/".$imagePath;
			
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
			if($key=='8'){
				$website=$value->toString();
				//print $website;
				}
			
		break;
        default: 
		} //end of switch
	}//end of inner foreach
						$startbody13.='<a id="blogs_preview_ad_link" href="'.$website.'" target="_blank"><img src="http://sandbox.speedhorse.com/var/ezwebin_site/storage/images/sponsored.png" width="65px" height="28px"><img src="http://sandbox.speedhorse.com/'.$imagePath.'" alt="post_thumbnail" width="104px" height="28px"/></a>';
}

$myoutput13=$startbody13;
$tpl->setVariable( 'myoutput13', $myoutput13);

$Result = array();
$Result['content'] = $tpl->fetch( 'design:userdetail/fparticle.tpl' );

?>