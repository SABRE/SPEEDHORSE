{* Make sure to normalize floats from db  *}
{def $latitude  = $attribute.content.latitude|explode(',')|implode('.')
     $longitude = $attribute.content.longitude|explode(',')|implode('.')}
{run-once}
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={ezini('SiteSettings','GMapsKey')}" type="text/javascript">
</script>
<script type="text/javascript">
 addEventListener = (function() {ldelim}
    if(document.addEventListener) {ldelim}
        return function(element, event, handler) {ldelim}
            element.addEventListener(event, handler, false);
        {rdelim};
    {rdelim}
    else {ldelim}
        return function(element, event, handler) {ldelim}
            element.attachEvent('on' + event, handler);
        {rdelim};
    {rdelim}
{rdelim}());

</script>
{/run-once}

{if $attribute.has_content}

<script type="text/javascript">
<!--
var fid="";
//fid="eZGmapLocation_MapView_"+{$attribute.id};
//alert(fid);
function eZGmapLocation_MapView_{$attribute.id}( attributeId, latitude, longitude )
{ldelim}
    if (GBrowserIsCompatible()) 
    {ldelim}
        if( latitude && longitude )
            var startPoint = new GLatLng( latitude, longitude ), zoom = 8;
        else
            var startPoint = new GLatLng( 0, 0 ), zoom = 0;

        var map_{$attribute.id} = new GMap2( document.getElementById( 'ezgml-map-' + attributeId ) );
        map_{$attribute.id}.addControl( new GSmallMapControl() );
        map_{$attribute.id}.setCenter( startPoint, zoom );
        map_{$attribute.id}.addOverlay( new GMarker(startPoint) );
    {rdelim}
{rdelim}


addEventListener(window, 'load',eZGmapLocation_MapView_{$attribute.id}( {$attribute.id}, {first_set( $latitude, '0.0')}, {first_set( $longitude, '0.0')} ));


-->
</script>

<div class="block">

</div>
<div id="ezgml-map-{$attribute.id}" style="width: 350px; height: 280px;"></div>
{/if}