{literal}
<script type="text/javascript">
var activeImage = false;
var imageGalleryLeftPos = false;
var imageGalleryWidth = false;
var imageGalleryObj = false;
var maxGalleryXPos = false;
var slideSpeed = 0;
var imageGalleryCaptions = new Array();
function startSlide(e){
	if(document.all)e = event;
	var id = this.id;
	if(this.id=='arrow_right'){
		slideSpeedMultiply = Math.floor((e.clientX - this.offsetLeft) / 3);
		slideSpeed = -1*slideSpeedMultiply;
		slideSpeed = Math.max(-10,slideSpeed);
	}else{
		slideSpeedMultiply = 10 - Math.floor((e.clientX - this.offsetLeft) / 3);
		slideSpeed = 1*slideSpeedMultiply;
		slideSpeed = Math.min(10,slideSpeed);
		if(slideSpeed<0)slideSpeed=10;
	}
}
function releaseSlide(){
	var id = this.id;
	this.getElementsByTagName('IMG')[0].src = 'http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/' + this.id + '.png';
	slideSpeed=0;
}
function gallerySlide(){
	if(slideSpeed!=0){
		var leftPos = imageGalleryObj.offsetLeft;
		leftPos = leftPos/1 + slideSpeed;
		if(leftPos>maxGalleryXPos){
			leftPos = maxGalleryXPos;
			slideSpeed = 0;
		}
		if(leftPos<minGalleryXPos){
			leftPos = minGalleryXPos;
			slideSpeed=0;
		}
		imageGalleryObj.style.left = leftPos + 'px';
	}
	setTimeout('gallerySlide()',20);
}
function initSlideShow(){
	document.getElementById('arrow_left').onmousemove = startSlide;
	document.getElementById('arrow_left').onmouseout = releaseSlide;
	document.getElementById('arrow_right').onmousemove = startSlide;
	document.getElementById('arrow_right').onmouseout = releaseSlide;
	imageGalleryObj = document.getElementById('theImages');
	imageGalleryLeftPos = imageGalleryObj.offsetLeft;
	var galleryContainer = document.getElementById('galleryContainer');
	imageGalleryWidth = galleryContainer.offsetWidth - 80;
	maxGalleryXPos = imageGalleryObj.offsetLeft;
	minGalleryXPos = imageGalleryWidth - document.getElementById('slideEnd').offsetLeft;
	var divs = imageGalleryObj.getElementsByTagName('DIV');
	for(var no=0;no<divs.length;no++){
		if(divs[no].className=='imageCaption')imageGalleryCaptions[imageGalleryCaptions.length] = divs[no].innerHTML;
	}
	gallerySlide();
}
function showPreview(imagePath,imageIndex){
	var subImages = document.getElementById('previewPane').getElementsByTagName('IMG');
	if(subImages.length==0){
		var img = document.createElement('IMG');
		document.getElementById('previewPane').appendChild(img);
	}else{
		img = subImages[0];
	}
	document.getElementById('largeImageCaption').style.display='none';
	img.onload = function() { hideWaitMessageAndShowCaption(imageIndex-1); };
	img.src = imagePath;
}
function hideWaitMessageAndShowCaption(imageIndex){
	document.getElementById('largeImageCaption').innerHTML = imageGalleryCaptions[imageIndex];
	document.getElementById('largeImageCaption').style.display='block';
}
window.onload = initSlideShow;
</script>
{/literal}
<div style="width:940px;float:left;">
	<div style="width:640px;float:left;">
	
		<div style="width: 599px;height: 448px;padding: 5px 5px 0 5px;background: url(http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/rotator_background.png)no-repeat;position: relative;left: 5px;">
			<div id="previewPane">
				<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image1_big.jpg" alt="big_image" />
					<span id="waitMessage">Loading image. Please wait</span>
					<div id="largeImageCaptionWrap">
						<div id="largeImageCaption"><h3>title goes here</h3>Lorem ipsum dolor sit amet, cons ec tetur adipiscing elit est commodo malesuada, felis tellus sagittis orci, sit amet semper mi ipsum dolor faucibus.</div>
						<div class="more_link_wrap"><a href="#" class="more_link">read more &#xBB;</a></div>
					</div>
				</div>
				<ul id="slideshow_menu">
					<li><a class="current_view" href="#top_stories">top stories</a></li>
					<li><a href="#photos">photos</a></li>
					<li><a href="#video">video</a></li>
				</ul>
				<div id="arrow_left"><img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/arrow_left.png" alt="left_arrow" /></div>
				<div id="arrow_right"><img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/arrow_right.png" alt="right_arrow" /></div>
				<div id="galleryContainer">
					<div id="theImages">

						<!-- Thumbnails -->
						<a href="#" onClick="showPreview('http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image1_big.jpg','1');return false">
							<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image1.jpg" alt="carousel_thumbnail" />
							<div class="thumb_caption"></div>
							<div class="thumb_frame"></div>
						</a>
						<a href="#" onClick="showPreview('http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image2_big.jpg','2');return false">
							<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image2.jpg" alt="carousel_thumbnail" />
							<div class="thumb_caption"></div>
							<div class="thumb_frame"></div>
						</a>
						<a href="#" onClick="showPreview('http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image3_big.jpg','3');return false">
							<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image3.jpg" alt="carousel_thumbnail" />
							<div class="thumb_caption"></div>
							<div class="thumb_frame"></div>
						</a>
						<a href="#" onClick="showPreview('http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image4_big.jpg','4');return false">
							<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image4.jpg" alt="carousel_thumbnail" />
							<div class="thumb_caption"></div>
							<div class="thumb_frame"></div>
						</a>
						<a href="#" onClick="showPreview('http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image5_big.jpg','5');return false">
							<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image5.jpg" alt="carousel_thumbnail" />
							<div class="thumb_caption"></div>
							<div class="thumb_frame"></div>
						</a>
						<a href="#" onClick="showPreview('http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image6_big.jpg','6');return false">
							<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image6.jpg" alt="carousel_thumbnail" />
							<div class="thumb_caption"></div>
							<div class="thumb_frame"></div>
						</a>
						<a href="#" onClick="showPreview('http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image7_big.jpg','7');return false">
							<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image7.jpg" alt="carousel_thumbnail" />
							<div class="thumb_caption"></div>
							<div class="thumb_frame"></div>
						</a>
						<a href="#" onClick="showPreview('http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image8_big.jpg','8');return false">
							<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/image8.jpg" alt="carousel_thumbnail" />
							<div class="thumb_caption"></div>
							<div class="thumb_frame"></div>
						</a>
						<!-- End thumbnails -->

						<!-- Image captions -->
						<div class="imageCaption"><h3>title goes here</h3>This is the caption of image number 1</div>
						<div class="imageCaption"><h3>title goes here</h3>This is the caption of image number 2</div>
						<div class="imageCaption"><h3>title goes here</h3>This is the caption of image number 3</div>
						<div class="imageCaption"><h3>title goes here</h3>This is the caption of image number 4</div>
						<div class="imageCaption"><h3>title goes here</h3>This is the caption of image number 5</div>
						<div class="imageCaption"><h3>title goes here</h3>This is the caption of image number 6</div>
						<div class="imageCaption"><h3>title goes here</h3>This is the caption of image number 7</div>
						<div class="imageCaption"><h3>title goes here</h3>This is the caption of image number 8</div>
						<!-- End image captions -->

						<div id="slideEnd"></div>

					</div>
			</div>
		</div>
		
		
		<div id="features">
				<h2 class="section_title">featured stories</h2>
				{$myoutput12}
				<div id="features_content">
					<table id="features_table">
						<colgroup>
							<col style="width:324px;">
							<col style="width:285px;">
						</colgroup>
				{$myoutput}
					</table><!-- #features_table -->
				</div><!-- #features_content -->
		</div><!-- #features section -->
				
		<div id="blogs_preview_wrap">
				<div id="blogs_preview"> 
				
					<h2 class="section_title">from the speedhorse blogs</h2>
					{$myoutput13}

					<div style="width:620px;float:left;">
						{$myoutput9}
					</div><!--div-->

				</div><!-- #blogs_preview -->
			</div><!-- #blogs_preview_wrap -->	
		
		
			<div id="bottom_content_wrap">
					<div id="left_bottom_panel">
						<h2 class="section_title">special focus title</h2>
						<ul class="speedhorse_list">
						{$myoutput1}
						</ul>
						{$myoutput3}
					</div>
			</div>
			<div id="products_preview" class="post">
						<h2 class="section_title">from the shop</h2>
						{$myoutput2}
			</div>
		
		
	</div>
		<div style="width:300px;float:right">
		<div id="sidebar">
			<div id="accordion_wrap">
				<div id="accordion">

					<!-- "Racing Results" -->
					<h3><a id="results" href="#">racing results</a></h3>
					<div id="results_wrap">
						<div id="results_tabs" class="tabs">
							<ul>
								<li><a href="#stakes">stakes</a></li>
								<li><a href="#trials">trials</a></li>
								<li><a href="#claiming">claiming</a></li>
							</ul>

							<div id="stakes">
								<ul class="results_pane">
									<li style="background-position:0px -88px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -204px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -320px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -436px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -552px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -668px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
								</ul>
								<div class="more_link_wrap"><a href="#" class="more_link">more &#xBB;</a></div>
							</div><!-- #stakes -->

							<div id="trials">
								<ul class="results_pane">
									<li style="background-position:0px -88px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -204px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -320px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -436px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -552px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -668px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
								</ul>
								<div class="more_link_wrap"><a href="#" class="more_link">more &#xBB;</a></div>
							</div><!-- #trials -->

							<div id="claiming">
								<ul class="results_pane">
									<li style="background-position:0px -88px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -204px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -320px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -436px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -552px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
									<li style="background-position:0px -668px;">
									West Texas Futurity*, (G1) $253,004*<br />
									1st Valiant War Hero: Valiant Hero, Prarie War
									</li>
								</ul>
								<div class="more_link_wrap"><a href="#" class="more_link">more &#xBB;</a></div>
							</div><!-- #claiming -->

						</div>
					</div>

					<!-- "Leaderboards" -->
					<h3><a id="leaderboards" href="#">leaderboards</a></h3>
					<div id="leaderboards_wrap">
						<div id="leaderboards_tabs" class="tabs">
							<ul>
								<li><a href="#sires">sires</a></li>
								<li><a href="#sires_of_2yo">sires of 2yo</a></li>
								<li><a href="#horses">horses</a></li>
							</ul>

							<div id="sires">
								<table>
									<caption>Money Earned</caption>
									<tr><td>1st - PYC Paint Your Wagon</td><td class="right_col">$836,786</td></tr>
									<tr><td>2nd - Tres Seis</td><td class="right_col">$666,132</td></tr>
									<tr><td>3rd - Walk Thru Fire</td><td class="right_col">$586,983</td></tr>
								</table>								
								<table>
									<caption>Stakes Wins</caption>
									<tr><td>1st - PYC Paint Your Wagon</td><td class="right_col">4</td></tr>
									<tr><td>2nd - Tres Seis</td><td class="right_col">4</td></tr>
									<tr><td>3rd - Walk Thru Fire</td><td class="right_col">4</td></tr>
								</table>								
								<table>
									<caption>Wins</caption>
									<tr><td>1st - PYC Paint Your Wagon</td><td class="right_col">30</td></tr>
									<tr><td>2nd - Tres Seis</td><td class="right_col">28</td></tr>
									<tr><td>3rd - Walk Thru Fire</td><td class="right_col">9</td></tr>
								</table>
								<div class="more_link_wrap"><a href="#" class="more_link">more&#xBB;</a></div>
							</div><!-- #sires -->

							<div id="sires_of_2yo">
								<table>
									<caption>Money Earned</caption>
									<tr><td>1st - PYC Paint Your Wagon</td><td class="right_col">$836,786</td></tr>
									<tr><td>2nd - Tres Seis</td><td class="right_col">$666,132</td></tr>
									<tr><td>3rd - Walk Thru Fire</td><td class="right_col">$586,983</td></tr>
								</table>								
								<table>
									<caption>Stakes Wins</caption>
									<tr><td>1st - PYC Paint Your Wagon</td><td class="right_col">4</td></tr>
									<tr><td>2nd - Tres Seis</td><td class="right_col">4</td></tr>
									<tr><td>3rd - Walk Thru Fire</td><td class="right_col">4</td></tr>
								</table>								
								<table>
									<caption>Wins</caption>
									<tr><td>1st - PYC Paint Your Wagon</td><td class="right_col">30</td></tr>
									<tr><td>2nd - Tres Seis</td><td class="right_col">28</td></tr>
									<tr><td>3rd - Walk Thru Fire</td><td class="right_col">9</td></tr>
								</table>
								<div class="more_link_wrap"><a href="#" class="more_link">more&#xBB;</a></div>
							</div><!-- #sires_of_2yo -->

							<div id="horses">
								<table>
									<caption>Money Earned</caption>
									<tr><td>1st - PYC Paint Your Wagon</td><td class="right_col">$836,786</td></tr>
									<tr><td>2nd - Tres Seis</td><td class="right_col">$666,132</td></tr>
									<tr><td>3rd - Walk Thru Fire</td><td class="right_col">$586,983</td></tr>
								</table>								
								<table>
									<caption>Stakes Wins</caption>
									<tr><td>1st - PYC Paint Your Wagon</td><td class="right_col">4</td></tr>
									<tr><td>2nd - Tres Seis</td><td class="right_col">4</td></tr>
									<tr><td>3rd - Walk Thru Fire</td><td class="right_col">4</td></tr>
								</table>								
								<table>
									<caption>Wins</caption>
									<tr><td>1st - PYC Paint Your Wagon</td><td class="right_col">30</td></tr>
									<tr><td>2nd - Tres Seis</td><td class="right_col">28</td></tr>
									<tr><td>3rd - Walk Thru Fire</td><td class="right_col">9</td></tr>
								</table>
								<div class="more_link_wrap"><a href="#" class="more_link">more&#xBB;</a></div>
							</div><!-- #horses -->

						</div>
					</div>

					<!-- "News Briefs" -->
					<h3><a id="news" href="#">news briefs</a></h3>
					<div id="news_wrap">
						<div id="news_list">
							<ul class="speedhorse_list">
								<li><img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/post_content_image.png" width="70" height="50" alt="post_thumbnail" /><a href="#">top story headline will go here on two lines</a></li>
								<li><img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/post_content_image.png" width="70" height="50" alt="post_thumbnail" /><a href="#">top story headline will go here on two lines</a></li>
								<li><img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/post_content_image.png" width="70" height="50" alt="post_thumbnail" /><a href="#">top story headline will go here on two lines</a></li>
								<li><img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/post_content_image.png" width="70" height="50" alt="post_thumbnail" /><a href="#">top story headline will go here on two lines</a></li>
							</ul>
							<div class="more_link_wrap"><a href="#" class="more_link">more&#xBB;</a></div>
						</div>
					</div>

					<!-- "What People are Sharing" -->
					<h3><a id="share" href="#">what people are sharing</a></h3>
					<div id="share_wrap">
						<div id="share_list">
							<ul class="speedhorse_list">
								<li>
									<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/post_content_image.png" width="70" height="50" alt="post_thumbnail" />
									<a href="#">sophie martin</a>
									<h6>recommended</h6>
									<p>Top Story Headline Will Go...</p>
								</li>
								<li>
									<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/post_content_image.png" width="70" height="50" alt="post_thumbnail" />
									<a href="#">sophie martin</a>
									<h6>recommended</h6>
									<p>Top Story Headline Will Go...</p>
								</li>
								<li>
									<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/post_content_image.png" width="70" height="50" alt="post_thumbnail" />
									<a href="#">sophie martin</a>
									<h6>recommended</h6>
									<p>Top Story Headline Will Go...</p>
								</li>
								<li>
									<img src="http://sandbox.speedhorse.com/extension/ezwebin/design/ezwebin/images/post_content_image.png" width="70" height="50" alt="post_thumbnail" />
									<a href="#">sophie martin</a>
									<h6>recommended</h6>
									<p>Top Story Headline Will Go...</p>
								</li>
							</ul>
							<div class="more_link_wrap"><a href="#" class="more_link">more&#xBB;</a></div>
						</div>
					</div>

					<!-- "Sales" -->
					<h3><a id="sales" href="#">sales</a></h3>
					<div id="sales_wrap">
						<div id="sales_tabs" class="tabs">
							<ul>
								<li><a href="#upcoming">upcoming</a></li>
								<li><a href="#sales_results">results</a></li>
							</ul>

							<div id="upcoming">
								<ul class="upcoming_pane">
									<li style="background-position:0px -82px;">
									Heritage Place - 15 Jan 2011 - $5,500,000<br />
									Top: Blues Ferrari $310,000<br />
									Sales Average: $15,000
									</li>
									<li style="background-position:0px -198px;">
									Heritage Place - 15 Jan 2011 - $5,500,000<br />
									Top: Blues Ferrari $310,000<br />
									Sales Average: $15,000
									</li>
									<li style="background-position:0px -314px;">
									Heritage Place - 15 Jan 2011 - $5,500,000<br />
									Top: Blues Ferrari $310,000<br />
									Sales Average: $15,000
									</li>
									<li style="background-position:0px -430px;">
									Heritage Place - 15 Jan 2011 - $5,500,000<br />
									Top: Blues Ferrari $310,000<br />
									Sales Average: $15,000
									</li>
								</ul>
								<div class="more_link_wrap"><a href="#" class="more_link">more &#xBB;</a></div>
							</div><!-- #upcoming -->

							<div id="sales_results">
								<ul class="upcoming_pane">
									<li style="background-position:0px -82px;">
									Heritage Place - 15 Jan 2011 - $5,500,000<br />
									Top: Blues Ferrari $310,000<br />
									Sales Average: $15,000
									</li>
									<li style="background-position:0px -198px;">
									Heritage Place - 15 Jan 2011 - $5,500,000<br />
									Top: Blues Ferrari $310,000<br />
									Sales Average: $15,000
									</li>
									<li style="background-position:0px -314px;">
									Heritage Place - 15 Jan 2011 - $5,500,000<br />
									Top: Blues Ferrari $310,000<br />
									Sales Average: $15,000
									</li>
									<li style="background-position:0px -430px;">
									Heritage Place - 15 Jan 2011 - $5,500,000<br />
									Top: Blues Ferrari $310,000<br />
									Sales Average: $15,000
									</li>
								</ul>
								<div class="more_link_wrap"><a href="#" class="more_link">more &#xBB;</a></div>
							</div><!-- #reslts -->

						</div>
					</div>

				</div><!-- #accordion -->
			</div><!-- #accordion_wrap -->

			<div id="upper_sidebar_ads">
				<div id="big_ad_1">
				{$myoutput5}
				</div>
				<div id="featured_partners_divider" style="height:18px; margin-bottom:5px; overflow:hidden;">
					<div style="width:28%; float:left; margin-top:3px;"><hr style="height:1px; border:none; background:#EBE4D4;" /></div>
					<div style="width:44%; float:left; font-family:Gotham-Medium; font-size:10px; color:#23436A; text-align:center;">FEATURED PARTNERS</div>
					<div style="width:28%; float:left; margin-top:3px;"><hr style="height:1px; border:none; background:#EBE4D4" /></div>
				</div>
				<div>
					{$myoutput7}
				</div>
				<div style="clear:both;"></div>
			</div>

			<div id="calendar">
				<h2 class="section_title">calendar</h2>
				<ul class="speedhorse_list">
					{$myoutput8}
				</ul>
			</div>
			<br />
			<br />
			<div id="big_ad_2">{$myoutput6}</div>
			<br />
			<br />
			<a id="sidebar_site_link_1" href="#"></a>
			<a id="sidebar_site_link_2" href="#"></a>
			
			<h2 class="section_title">special focus title</h2>
			<ul class="speedhorse_list">
				{$myoutput4}
			</ul>

		</div><!-- #sidebar -->
	</div>
		
		
</div>		