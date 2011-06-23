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

$startbody0='';
$myoutput0='';			
$startbody='';
$myoutput='';			
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
////for stars
$star1="";
$star11="";
$star2="";
$star22="";
$star3="";
$star33="";

/////////////////////////////////////////////////////////Blog Top Section (Use of Article Class)///////////////////////////////////////////////////////////////////////

$data=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='140' and ezcontentobject.status='1' order by published desc limit 0,1");
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
					}
			if($key=='1'){
					$author1=$value->toString();
					$author=explode('|',$author1);
					}		
			if($key=='2'){
				$summary1=$value->toString();
				$summary=substr($summary1,0,650);
				}
			break;
			default: 
		} //end of switch
	}//end of inner foreach	
				
				$startbody0.='<a href="http://sandbox.speedhorse.com/'.$path.'"><img src="http://sandbox.speedhorse.com/'.$imagePath.'" class="post_img" /></a>
                <div class="post_content">
                    <h2><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></h2>
                    <p><a href="http://sandbox.speedhorse.com/'.$path.'">'.$author[0].'</a></p>
                    <p><a href="http://sandbox.speedhorse.com/'.$path.'">'.$summary.'</a></p>
                    <a href="http://sandbox.speedhorse.com/'.$path.'" class="read-more">Read More &raquo;</a>
                </div>';
}
$myoutput0=$startbody0;
$tpl->setVariable( 'myoutput0', $myoutput0);

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
				if($key=='0'){
					$title=$value->toString();
					//print $title;
					}
				break;
				default: 							
			} //end of switch
		}//end of inner foreach	
						$startbody.='<a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a>';
						//$startbody55.='http://sandbox.speedhorse.com/'.$path;
						
}
$myoutput=$startbody;
$tpl->setVariable( 'myoutput', $myoutput);
//$myoutput55=$startbody55;
//print $startbody55;
//$tpl->setVariable( 'myoutput55', $myoutput55);
	
		
///////////////////////////////////////////////////Blog Category Name//////////////////////////////////////////////////////////////////////////////////////////////////
$optionArray=array();
$data1=$db->arrayQuery("SELECT data_text5 FROM `ezcontentclass_attribute` WHERE `contentclass_id`='20' and data_type_string='ezselection' and identifier='category'");
			$xmlDoc =$data1[0]['data_text5'];
                        if( $xmlDoc != null )
                        {
                            $domDocument = new DOMDocument( '1.0', 'utf-8' );
                            $success = $domDocument->loadXML( $xmlDoc );
                            if ( $success )
                            {
                                $root = $domDocument->documentElement;
								$options = $root->getElementsByTagName( 'option' );
								 foreach ( $options as $optionNode )
								 {
									 $optionArray[] = array( 'id' => $optionNode->getAttribute( 'id' ),'name' => $optionNode->getAttribute( 'name' ) );
								 }
								 
									$tpl->setVariable( 'optionArray',$optionArray);
							}
									
                        }        


////////////////////////////////////////////Blog Post Fot CAT A /////////////////////////////////////////////////
$chk=0;
$chk1=0;
$chk2=0;
$data2=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject.owner_id as owner,ezcontentobject.published as publish, ezcontentobject_tree.path_identification_string AS path, ezcontentobject_attribute.data_text as keyid FROM `ezcontentobject` , ezcontentobject_tree, ezcontentobject_attribute  WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND ezcontentobject.id = ezcontentobject_attribute.contentobject_id and  ezcontentobject.contentclass_id ='20' and ezcontentobject.status='1' order by published desc  ");
		
		foreach($data2 as $row)
		{
			
				if($row['keyid']==$optionArray[0]['id'] && $chk<2)
					{
							//////Owner////////////////////////////
							$currentUser = eZUser::fetch($row['owner']);
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
						
						//////////////////////////////////////////////Star rating/////////////////////////////////////////////
						$datas1=$db->arrayQuery("SELECT contentobject_id,rating_average,rating_count FROM `ezstarrating` WHERE contentobject_id = '".$row['id']."'");
								$rate=$datas1['0']['rating_average'];
									for($i=0;$i<round($rate);$i++)
									{
										$star1.='&#x2605;';
									}
									if(round($rate)<5)
									{
										for(;$i<5;$i++)
										{
											$star11.='<span class="unattained_star">&#x2605;</span>';
										}
									}	
				////////////////////////////////////////////End Of Star Rating//////////////////////////////////////////////////////
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
						case 'ezdatetime':
						/*if($key=='4'){
							print $value->toString()."aaa";
							}*/
			
								if($key=='0'){
									$title=$value->toString();
									}
								if($key=='1'){
									$headline=$value->toString();
									}
								if($key=='2'){
									$author1=$value->toString();
									$author=explode('|',$author1);
									
									}
								if($key=='3'){
									$summary1=$value->toString();
									$summary=substr($summary1,0,325);
									}
								if($key=='4'){
									$publishdate1=$value->toString();
									$publishdate=date("D, F d, Y", $publishdate1);
									}						
									break;
									default: 
							} //end of switch
						}//end of inner foreach
							if($chk==0)
							{
							$startbody2.='<div class="post" style="margin-right:20px; height:180px;">
							<a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['owner'].'"><img src="http://sandbox.speedhorse.com/'.$imagePathuser.'" alt="Post Author" class="author"  width="87px" height="100px"/></a>
								<div class="post_right">
									<h4><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></h4>
									<cite><a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['owner'].'">'.ucfirst($author[0]).'</a></cite>
									<h5><a href="http://sandbox.speedhorse.com/'.$path.'">'.$headline.'</a></h5>
									<p><a href="http://sandbox.speedhorse.com/'.$path.'">'.$summary.'</a></p>
									<em><a href="http://sandbox.speedhorse.com/'.$path.'">Read More &raquo;</a></em>
								</div>
								<div class="post_foot">
									<span class="posted">Posted on: '.$publishdate.'</span><br />

									<span class="comments">21 Comments <cite>|</cite></span>
									<span class="views">219 Views <cite>|</cite></span>
									<span class="rating"><span>&nbsp;&nbsp;Rating &nbsp;'.$star1.'</span>'.$star11.'</span>
								</div><!--end div.post_foot-->
							</div>';
									$star1="";
									$star11="";

							}
							if($chk==1)
							{
							$startbody2.='<div class="post" style="height:180px;">
								<a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['owner'].'"><img src="http://sandbox.speedhorse.com/'.$imagePathuser.'" alt="Post Author" class="author" width="87px" height="100px"/></a>
								<div class="post_right">
									<h4><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></h4>
									<cite><a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['owner'].'">'.ucfirst($author[0]).'</a></cite>
									<h5><a href="http://sandbox.speedhorse.com/'.$path.'">'.$headline.'</a></h5>
									<p><a href="http://sandbox.speedhorse.com/'.$path.'">'.$summary.'</a></p>
									<em><a href="http://sandbox.speedhorse.com/'.$path.'">Read More &raquo;</a></em>
								</div><!--end div.post_right-->
								<div class="post_foot">
									<span class="posted">Posted on: '.$publishdate.'</span><br />

									<span class="comments">21 Comments <cite>|</cite></span>
									<span class="views">219 Views <cite>|</cite></span>
									<span class="rating"><span>&nbsp;&nbsp;Rating &nbsp;'.$star1.'</span>'.$star11.'</span>
								</div><!--end div.post_foot-->
							</div>';
									$star1="";
									$star11="";

							}
							$chk=$chk+1;
				}
			if($row['keyid']==$optionArray[1]['id'] && $chk1<2)
				{
						//////Owner////////////////////////////
							$currentUser = eZUser::fetch($row['owner']);
							//print $row['owner'];
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
						//////////////////////////////////////////////Star rating/////////////////////////////////////////////
						$datas1=$db->arrayQuery("SELECT contentobject_id,rating_average,rating_count FROM `ezstarrating` WHERE contentobject_id = '".$row['id']."'");
								$rate=$datas1['0']['rating_average'];
									for($i=0;$i<round($rate);$i++)
									{
										$star2.='&#x2605;';
									}
									if(round($rate)<5)
									{
										for(;$i<5;$i++)
										{
											$star22.='<span class="unattained_star">&#x2605;</span>';
										}
									}	
				////////////////////////////////////////////End Of Star Rating//////////////////////////////////////////////////////
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
								case 'ezdatetime':
								/*if($key=='4'){
									print $value->toString()."aaa";
									}*/
					
								if($key=='0'){
									$title=$value->toString();
									}
								if($key=='1'){
									$headline=$value->toString();
									}
								if($key=='2'){
									$author1=$value->toString();
									$author=explode('|',$author1);
									
									}
								if($key=='3'){
									$summary1=$value->toString();
									$summary=substr($summary1,0,325);
									}
								if($key=='4'){
									$publishdate1=$value->toString();
									$publishdate=date("D, F d, Y", $publishdate1);
									}				
							break;
							default: 
							} //end of switch
						}//end of inner foreach
									if($chk1==0)
									{
									$startbody3.='<div class="post" style="margin-right:20px;height:180px;">
									<a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['owner'].'"><img src="http://sandbox.speedhorse.com/'.$imagePathuser.'" alt="Post Author" class="author"  width="87px" height="100px"/></a>
										<div class="post_right">
											<h4><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></h4>
											<cite><a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['owner'].'">'.ucfirst($author[0]).'</a></cite>
											<h5><a href="http://sandbox.speedhorse.com/'.$path.'">'.$headline.'</a></h5>
											<p><a href="http://sandbox.speedhorse.com/'.$path.'">'.$summary.'...</a></p>
											<em><a href="http://sandbox.speedhorse.com/'.$path.'">Read More &raquo;</a></em>
										</div>
										<div class="post_foot">
											<span class="posted">Posted on: '.$publishdate.'</span>
											<span class="comments">21 Comments <cite>|</cite></span>
											<span class="views">219 Views <cite>|</cite></span>
											<span class="rating"><span>&nbsp;&nbsp;Rating &nbsp;'.$star2.'</span>'.$star22.'</span>
										</div><!--end div.post_foot-->
									</div>';
									}
									if($chk1==1)
									{
									$startbody3.='<div class="post"  style="height:180px;">
										<a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['owner'].'"><img src="http://sandbox.speedhorse.com/'.$imagePathuser.'" alt="Post Author" class="author"  width="87px" height="100px"/></a>
										<div class="post_right">
											<h4><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></h4>
											<cite><a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['owner'].'">'.ucfirst($author[0]).'</a></cite>
											<h5><a href="http://sandbox.speedhorse.com/'.$path.'">'.$headline.'</a></h5>
											<p><a href="http://sandbox.speedhorse.com/'.$path.'">'.$summary.'...</a></p>
											<em><a href="http://sandbox.speedhorse.com/'.$path.'">Read More &raquo;</a></em>
										</div><!--end div.post_right-->
										<div class="post_foot">
											<span class="posted">Posted on: '.$publishdate.'</span>
											<span class="comments">21 Comments <cite>|</cite></span>
											<span class="views">219 Views <cite>|</cite></span>
											<span class="rating"><span>&nbsp;&nbsp;Rating &nbsp;'.$star2.'</span>'.$star22.'</span>
										</div><!--end div.post_foot-->
									</div>';
									}
									$chk1=$chk1+1;
									$star2="";
									$star22="";
				}	
			if($row['keyid']==$optionArray[2]['id'] && $chk2<2)
				{
				//////Owner////////////////////////////
				//print $row['owner'];
							$currentUser = eZUser::fetch($row['owner']);
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
						
						//////////////////////////////////////////////Star rating/////////////////////////////////////////////
						$datas1=$db->arrayQuery("SELECT contentobject_id,rating_average,rating_count FROM `ezstarrating` WHERE contentobject_id = '".$row['id']."'");
								$rate=$datas1['0']['rating_average'];
									for($i=0;$i<round($rate);$i++)
									{
										$star3.='&#x2605;';
									}
									if(round($rate)<5)
									{
										for(;$i<5;$i++)
										{
											$star33.='<span class="unattained_star">&#x2605;</span>';
										}
									}	
				////////////////////////////////////////////End Of Star Rating//////////////////////////////////////////////////////
				
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
				case 'ezdatetime':
				/*if($key=='4'){
					print $value->toString()."aaa";
					}*/
	
				if($key=='0'){
					$title=$value->toString();
					}
				if($key=='1'){
					$headline=$value->toString();
					}
				if($key=='2'){
					$author1=$value->toString();
					$author=explode('|',$author1);
					
					}
				if($key=='3'){
					$summary1=$value->toString();
					$summary=substr($summary1,0,325);
					}
				if($key=='4'){
					$publishdate1=$value->toString();
					$publishdate=date("D, F d, Y", $publishdate1);
					}				
			break;
			default: 
			} //end of switch
		}//end of inner foreach
					if($chk2==0)
					{
					$startbody4.='<div class="post" style="margin-right:20px;height:180px;">
					<a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['owner'].'"><img src="http://sandbox.speedhorse.com/'.$imagePathuser.'" alt="Post Author" class="author"  width="87px" height="100px"/></a>
						<div class="post_right">
							<h4><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></h4>
							<cite><a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['owner'].'">'.ucfirst($author[0]).'</a></cite>
							<h5><a href="http://sandbox.speedhorse.com/'.$path.'">'.$headline.'</a></h5>
							<p><a href="http://sandbox.speedhorse.com/'.$path.'">'.$summary.'...</a></p>
							<em><a href="http://sandbox.speedhorse.com/'.$path.'">Read More &raquo;</a></em>
						</div>
						<div class="post_foot">
							<span class="posted">Posted on: '.$publishdate.'</span>
							<span class="comments">21 Comments <cite>|</cite></span>
							<span class="views">219 Views <cite>|</cite></span>
							<span class="rating"><span>&nbsp;&nbsp;Rating &nbsp;'.$star3.'</span>'.$star33.'</span>
						</div><!--end div.post_foot-->
					</div>';
					}
					if($chk2==1)
					{
					$startbody4.='<div class="post"  style="height:180px;">
						<a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['owner'].'"><img src="http://sandbox.speedhorse.com/'.$imagePathuser.'" alt="Post Author" class="author"  width="87px" height="100px"/></a>
						<div class="post_right">
							<h4><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></h4>
							<cite><a href="http://sandbox.speedhorse.com/userdetail/list/'.$row['owner'].'">'.ucfirst($author[0]).'</a></cite>
							<h5><a href="http://sandbox.speedhorse.com/'.$path.'">'.$headline.'</a></h5>
							<p><a href="http://sandbox.speedhorse.com/'.$path.'">'.$summary.'...</a></p>
							<em><a href="http://sandbox.speedhorse.com/'.$path.'">Read More &raquo;</a></em>
						</div><!--end div.post_right-->
						<div class="post_foot">
							<span class="posted">Posted on: '.$publishdate.'</span>
							<span class="comments">21 Comments <cite>|</cite></span>
							<span class="views">219 Views <cite>|</cite></span>
							<span class="rating"><span>&nbsp;&nbsp;Rating &nbsp;'.$star3.'</span>'.$star33.'</span>
						</div><!--end div.post_foot-->
					</div>';
					}
					$chk2=$chk2+1;
					$star3="";
					$star33="";
				}
			
					
}
//print_r($tagarr);
$myoutput2=$startbody2;
$tpl->setVariable( 'myoutput2', $myoutput2);

$myoutput3=$startbody3;
$tpl->setVariable( 'myoutput3', $myoutput3);

$myoutput4=$startbody4;
$tpl->setVariable( 'myoutput4', $myoutput4);


////////////////////////////////////////////FRONT PAGE BIG ADD/////////////////////////////////////////////////////

$data5=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='130' and ezcontentobject.status='1' order by published desc  limit 0,1");
		
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
        default: 
		} //end of switch
	}//end of inner foreach
						$startbody5.='<a href="http://sandbox.speedhorse.com/'.$path.'"><img src="http://sandbox.speedhorse.com/'.$imagePath.'"  width="300px" height="250px"/></a>';
}

$myoutput5=$startbody5;
$tpl->setVariable( 'myoutput5', $myoutput5);


////////////////////////////////////////////FEATURE HEADLINE ARTICLE START FROM HERE


$data6=$db->arrayQuery("SELECT ezcontentobject.id AS id,ezcontentobject.name AS name, ezcontentobject_tree.path_identification_string AS path FROM `ezcontentobject` , ezcontentobject_tree WHERE ezcontentobject.id = ezcontentobject_tree.contentobject_id AND  ezcontentobject.contentclass_id ='129' and ezcontentobject.status='1' order by published desc limit 0,7");
		//$startbody4.='<ul class="speedhorse_list">';
		foreach($data6 as $row)
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
			$startbody6.='<li><a href="http://sandbox.speedhorse.com/'.$path.'">'.$title.'</a></li>';
}
//$startbody4.='</ul>';

$myoutput6=$startbody6;
$tpl->setVariable( 'myoutput6', $myoutput6);

//$tag="";
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
						$startbody7.="<a href='http://localhost/ez45/index.php/ezwebin_site/".$path."' style='font-size:".mt_rand(10, 18)."px;' >".$tag."</a>&nbsp;&nbsp;";
}

$myoutput7=$startbody7;
$tpl->setVariable( 'myoutput7', $myoutput7);



$Result = array();
$Result['content'] = $tpl->fetch( 'design:spblogs/spblogs.tpl' );

?>