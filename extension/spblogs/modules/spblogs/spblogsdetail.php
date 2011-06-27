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
////for stars
$star1="";
$star11="";
$star2="";
$star22="";
$star3="";
$star33="";


/////////////////////////////////////////////////////////Blog Directory Name///////////////////////////////////////////////////////////////////////////

$data=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='19' and ezcontentobject.status='1' order by published desc limit 0,4");
		//$startbody1.='<ul class="speedhorse_list">';
		foreach($data as $row)
		{
			
			$path=str_replace('_','-',$row['path']);
			//print $path;
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
				//print $title;
				}
		break;
        default: 
		} //end of switch
	}//end of inner foreach	
				
						$startbody.='<a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a>';
}

$myoutput=$startbody;
$tpl->setVariable( 'myoutput', $myoutput);

		
///////////////////////////////////////////////Blog Containt Start///////////////////////////////////////////////////////////
$tag="";
$data1=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name,ezcontentobject.owner_id as ownerid, ezcontentobject.published as publish, ezcontentobject_attribute.data_text FROM `ezcontentobject` , ezcontentobject_attribute WHERE ezcontentobject.id = ezcontentobject_attribute.contentobject_id AND  ezcontentobject.id ='893' and ezcontentobject.status='1' limit 0,1");
		//$startbody1.='<ul class="speedhorse_list">';
		foreach($data1 as $row)
		{
			//////Owner////////////////////////////
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
									//print $imagePathuser;
									if($imagePathuser!="")
										{
										
										}
									else
										{
										$imagePathuser="var/ezwebin_site/storage/images/noimages.jpg";
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
					    ////////////////end of owner////////////////
			$path=str_replace('_','-',$row['path']);
			//print $path;
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
					case 'ezdatetime':
					case 'ezkeyword': 
					
					if($key=='0'){
						$title=$value->toString();
						//print "TITLE==".$title."<BR>";
						}
					if($key=='1'){
						$headline=$value->toString();
						//print "HEADLINE".$headline."<BR>";
						}
					if($key=='2'){
						$author1=$value->toString();
						$author=explode('|',$author1);
						//print "AUTHOR".$author[0]."<BR>";
						}
					if($key=='3'){
						$body=$value->toString();
						//print "BODY".$body."<BR>";
						}
					if($key=='4'){
						$publishdate1=$value->toString();
						$publishdate=date("D, F d, Y", $publishdate1);
						//print "PUBLISH".$publishdate."<BR>";
						}	
					if($key=='6'){
						$tag=$value->toString();
						//print "TAG".$tag."<BR>";
						}
					if($key=='7'){
						$comment=$value->toString();
						//print "cOMMENT".$comment."<BR>";
						}
					if($key=='10'){
						$selection=$value->toString();
						//print "CATEGORY SELETION".$selection."<BR>";
						}								
				break;
				default: 
			} //end of switch
		}//end of inner foreach	
				
						$startbody1.='<h2>'.$title.'</h2>
                <div class="post-info large">Blog subhead goes right here</div>
            	
            	<div class="info_bar">
                    <div class="left-area">
                        <a href="#" class="print">Print</a>
                        <a href="#" class="email">Email</a>
                    </div><!--end div.left-area-->
                    <div class="social">
                        <img src="http://sandbox.speedhorse.com/var/ezwebin_site/storage/images/fb_like.jpg" />
                        <img src="http://sandbox.speedhorse.com/var/ezwebin_site/storage/images/fb_people_like.jpg" />
                        <img src="http://sandbox.speedhorse.com/var/ezwebin_site/storage/images/twitter.jpg" />
                    </div><!--end div.social-->
                </div><!--end div.info_bar-->
                
                <div id="the_post_content">
                	<h3>'.$headline.'<span>'.$publishdate.'</span></h3>
                    
                	<img src="http://sandbox.speedhorse.com/'.$imagePathuser.'" alt="Post Author" class="float_img_right" width="204px" height="194px"><!-- In case you have an image in your post that needs to be on the left and content flows around it use float_img_left for the class -->
                	'.$body.'
                    <img src="http://sandbox.speedhorse.com/'.$imagePath.'" class="post_img" />
                   
                </div><!--end div#the_post_content-->';
}

$myoutput1=$startbody1;
$tpl->setVariable( 'myoutput1', $myoutput1);


//////////////////////////////////////////////Blog Containt End////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////RELATED NEWS/////////////////////////////////////////////////////
$data2=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='144' and ezcontentobject.status='1' order by published desc limit 0,5");
		//$startbody4.='<ul class="speedhorse_list">';
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
		break;
        default: 
		} //end of switch
	}//end of inner foreach
			$startbody2.='<li><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></li>';
}
//$startbody4.='</ul>';

$myoutput2=$startbody2;
$tpl->setVariable( 'myoutput2', $myoutput2);

/////////////////////////////////////////////////////END OF RELATED NEWS//////////////////////////////////////////////


/////////////////////////////////////////////////////RECOMMENDED NEWS/////////////////////////////////////////////////////
$data3=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='143' and ezcontentobject.status='1' order by published desc limit 0,5");
		//$startbody4.='<ul class="speedhorse_list">';
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
		break;
        default: 
		} //end of switch
	}//end of inner foreach
			$startbody3.='<li><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></li>';
}
//$startbody4.='</ul>';

$myoutput3=$startbody3;
$tpl->setVariable( 'myoutput3', $myoutput3);

/////////////////////////////////////////////////////END OF RECOMMENDED NEWS//////////////////////////////////////////////


////////////////////////////////////////////FRONT PAGE BIG ADD/////////////////////////////////////////////////////

$data4=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='130' and ezcontentobject.status='1' order by published desc  limit 0,1");
		
		foreach($data4 as $row)
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
						$startbody4.='<a href="http://sandbox.speedhorse.com/'.$path.'"><img src="http://sandbox.speedhorse.com/'.$imagePath.'"  width="300px" height="250px"/></a>';
}

$myoutput4=$startbody4;
$tpl->setVariable( 'myoutput4', $myoutput4);


////////////////////////////////////////////FEATURE HEADLINE ARTICLE START FROM HERE


$data5=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='129' and ezcontentobject.status='1' order by published desc limit 0,7");
		foreach($data5 as $row)
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
			$startbody5.='<li><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></li>';
}

$myoutput5=$startbody5;
$tpl->setVariable( 'myoutput5', $myoutput5);


////////////////////////////For TAG/////////////////////////////////////////////////////////////////////////////

$data7=$db->arrayQuery("SELECT ezcontentobject.id AS id, ezcontentobject_attribute.data_text, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_attribute, ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_attribute.contentobject_id and ezcontentobject.id = ezcontentobject_tree.contentobject_id AND ezcontentobject.contentclass_id ='20' and ezcontentobject.status='1' group by id");
		//$startbody1.='<ul class="speedhorse_list">';
		foreach($data7 as $row)
		{
			
			$path=str_replace('_','-',$row['path']);
			//print $path;
			$co = eZContentObject::fetch($row['id']);
			$datamap =  $co->contentObjectAttributes('data_map');
			//print_r($datamap);
			foreach( $datamap as $key => $value ) //looping through each field
			{
			
				$type = $value->dataType(); //looking at what type the current field is
    			switch( $type->DataTypeString ) //base the switch on the type name
    			{
				case 'ezkeyword': 
				if($key=='6'){
						$tag=$value->toString();
				}
				break;
				default: 
			} //end of switch
		}//end of inner foreach	
						$startbody7.="<a href='http://sandbox.speedhorse.com/".$path."' style='font-size:".mt_rand(10, 18)."px;' >".$tag."</a>&nbsp;&nbsp;";
}

$myoutput7=$startbody7;
$tpl->setVariable( 'myoutput7', $myoutput7);

////////////////////////////////////ARCHIVE////////////////////////////////////////////////////////////
/*$yeararr=array();
$montharr=array();

$data8=$db->arrayQuery("SELECT ezcontentobject.id AS id, ezcontentobject.published as publish, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND ezcontentobject.contentclass_id ='20' and ezcontentobject.status='1' order by publish desc");
		//$startbody1.='<ul class="speedhorse_list">';
		$i=0; //for year and month array increment
		$j=0; //for month array increment
		foreach($data8 as $row)
		{
			
			$path=str_replace('_','-',$row['path']);
			//print $path;
			$co = eZContentObject::fetch($row['id']);
			$datamap =  $co->contentObjectAttributes('data_map');
			//print_r($datamap);
			foreach( $datamap as $key => $value ) //looping through each field
			{
			
				$type = $value->dataType(); //looking at what type the current field is
    			switch( $type->DataTypeString ) //base the switch on the type name
    			{
				case 'ezdatetime': 
				if($key=='4')
					{
						$publishdate1=$value->toString();
						$publishdate=date("D, F d, Y", $publishdate1);
						$montharr[$i]=date("Y,F", $publishdate1);
						//print $montharr[$i];
						$yeararr[$i]=date("Y", $publishdate1);
						$i=$i+1;
						//print $publishyear."<br />";
					}
						
				break;
				default: 
			} //end of switch
		}//end of inner foreach	
		
						//$startbody7.="<a href='http://localhost/ez45/index.php/ezwebin_site/".$path."' style='font-size:".mt_rand(10, 18)."px;' >".$tag."</a>&nbsp;&nbsp;";
}
$year1=array_unique($yeararr);
$month1=array_unique($montharr);
$match=array();

$startbody8.='<ul id="blog_archives" style="float:left;">';
foreach($year1 as $key=>$value)
	{
	$startbody8.='<li><a href="#">'.$value.'</a>
					<ul class="archives_year">';
	foreach($month1 as $key1=>$value1)
		{
			$match=explode(',',$value1);
				if($match[0]==$value1)
					{
					$startbody8.='<li><a href="#">'.$match[1].'</a></li>';
					}			
		}
		$startbody8.='</ul></li>';
	}	
$startbody8.='</ul>';	

$myoutput8=$startbody8;
$tpl->setVariable( 'myoutput8', $myoutput8);*/

/////////////////////////////////////END OF TAG////////////////////////////////////////////////////////////////////////////

$Result = array();
$Result['content'] = $tpl->fetch( 'design:spblogs/spblogsdetail.tpl' );

?>