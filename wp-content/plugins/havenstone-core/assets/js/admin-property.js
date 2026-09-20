(function($){
  'use strict';
  $(function(){
    const $input=$('#havenstone_gallery');
    const $button=$('#havenstone_gallery_select');
    if(!$input.length||!$button.length||typeof wp==='undefined'||!wp.media)return;
    let frame;
    $button.on('click',function(e){
      e.preventDefault();
      if(frame){frame.open();return;}
      frame=wp.media({
        title:'Select Property Gallery Images',
        button:{text:'Use selected images'},
        multiple:true,
        library:{type:'image'}
      });
      frame.on('select',function(){
        const ids=frame.state().get('selection').map(function(att){return att.id;});
        $input.val(ids.join(','));
      });
      frame.open();
    });
  });
})(jQuery);
