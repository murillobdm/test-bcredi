jQuery(document).ready(function(){

    var base_url = window.location.origin;

    jQuery('body').on('click', 'a.repo-link', function () {

        jQuery('.clicked').css('border', '0px solid red').removeClass('clicked');
        jQuery(this).find('.repoblock-box').css('border', '1px solid red').addClass('clicked');

        $path = jQuery(this).attr("data-path");

        jQuery.ajax({
            url : base_url+"/maketreehtml/" + jQuery(this).attr("data-ref") + "/" +  $path,
            type : 'get',
            beforeSend : function(){
                //
            }
        })
        .done(function(msg){

            if($path == "" || $path == null )
            {
                jQuery("#thepath").html("Caminho: /");
            }
            else
            {
                jQuery("#thepath").html("Caminho: /" + $path);
            }
            jQuery("#thecode").html(msg);
        })
        .fail(function(jqXHR, textStatus, msg){
            alert(msg);
        });
    });


    jQuery("#updateRepo").click(function (event) {
        event.preventDefault();

        jQuery('#all').css('display','block');

        jQuery('html, body').css({
            overflow: 'hidden',
            height: '100%'
        });


        jQuery.ajax({
            url : base_url + "/repo/update/",
            type : 'get',
            async: false,
            beforeSend : function(){
                jQuery("#warning-element").html("[1/4] Atualizando a lista de repositórios...");
            }
        })
        .done(function(msg)
        {
            jQuery("#warning-element").html("[2/4] Atualizada a lista de repositórios!");
        })
        .fail(function(jqXHR, textStatus, msg){
            alert(msg);
            console.debug(textStatus);
            console.debug(jqXHR);
        });

        //

        jQuery.ajax({
            url : base_url + "/repo/download/",
            type : 'get',
            async: false,
            beforeSend : function(){
                jQuery("#warning-element").html("[3/4] Atualizando os arquivos de repositórios...");
            }
        })
        .done(function(msg)
        {
            jQuery("#warning-element").html("[4/4] Atualizado os arquivos de repositórios!");
        })
        .fail(function(jqXHR, textStatus, msg){
            alert(msg);
            console.debug(textStatus);
            console.debug(jqXHR);
        });

        //

        jQuery("#warning-element").html("Atualizando a página");
        location.reload();

    })


});