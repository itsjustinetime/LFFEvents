<div class="eventcontainer eventexpand" id="{venuetag}">
	<div class="eventmain">
		<div class="leftcol">
			<div class="e_image"><img class="eventimg" imgsrc="{eventimagesmall}" src=""></img></div>
		</div>
		<div class="rightcol" style="width:70%; padding-left:5px; position:relative;">
			<div class="venuename">{eventtitle}</div>
			<div class="infodesc" style="clear:both">{eventsubtitle}</div>		
			<div class="evbottom" style="position:absolute; bottom:0;">
				<div class="subtitle stleft" id="start{venuetag}"><div class="stbutton"><i class="fa-regular fa-clock"></i><div class="timetext"> {eventstart} - {eventend}</div></div></div>
				<div class="subtitle stright"><div class="stbutton"><i class="fa-solid fa-location-dot"></i> {eventvenue}</div></div>		
			</div>
		</div>
		
	</div>
	<div class="evfull" style="display:none;" id="full{venuetag}"><div class="fulltext" style="text-align:center;margin-top:1vh;">FULLY BOOKED!</div></div>
	<!-- <div class="tap4more" id="t4m_{venuetag}">Tap for more...</div> -->
			
	<div class="eventbanner" id="eb{venuetag}" style="display:none"></div>
	<div class="collapse" id="coll_{venuetag}">
		<div class="collapsecontainer">
			<div class="infodesc">{eventdescription}</div>
			<div id="cta{venuetag}" class=""><a class="socialbutton" href="{eventctaurl}"><i class="fa-brands fa-facebook-messenger"></i>   {eventctatext}</a></div>
			<div class="mapbutton">
				<a id="maplink" href='https://maps.google.com/?q={venuecoords}'>
					<div class="fsaddress">{venueaddress}<i class="fa-solid fa-location-dot"></i></div>
				</a>
			</div>
		</div>
	</div>
	 
</div>


		



		




