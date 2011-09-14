Created by Lukasz Klejnber 2009.12.11

Tested on eZ Publish 4.2.

Based on jQuery lib ( http://jquery.com/ ) and jQuerylightbox plugin ( http://leandrovieira.com/projects/jquery/lightbox/ ).

Quick Install:
1. Copy extension
2. Activate
3. Clear cache

Now, you can use views from the online editor: jquerylightbox-thumb and jquerylightbox-link (based on ezlightbox).
You can set default size in a file: settings/jquerylightbox.ini

You can use tag rel="lightbox" for example galleryline/image.tpl

You can change the background and opacity in file: design/standard/javascript/jquery.lightbox.function.js:

Default settings:
$(function() {
	$('a[rel*=lightbox]').lightBox({
		overlayBgColor: '#BBB',
		overlayOpacity: 0.6
	});
});

but you can add more value, look http://leandrovieira.com/projects/jquery/lightbox/ and click extend, you will see more information about configuration.

If you have any questions, ideas or suggestions, please write.

2009.12.11
- release of stable 1.0

2009.12.28
- release of stable 1.1
Change version jQuery to 1.3.2 and fix expression [@rel*=lightbox] to [rel*=lightbox]

2010.01.03
- release of stable 1.2
Fix the bug that was hiding elements of the flash when you click on the lightbox.
