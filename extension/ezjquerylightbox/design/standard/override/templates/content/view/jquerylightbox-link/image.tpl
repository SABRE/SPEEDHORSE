{def $image_size=ezini( 'jQueryLightboxSettings' , 'ImageSize' , 'jquerylightbox.ini' )}
<a href={$object.data_map.image.content[$image_size].full_path|ezroot( 'double' , 'full' )} rel="lightbox" title="{$object.data_map.caption.content.output.output_text|strip_tags|trim}">{$object.name}</a>
{undef $image_size}