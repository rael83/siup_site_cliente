<div id="HOSTmensagem"><span><i class="ion ion-checkmark"></i> </span><a href="javascript:;" onclick="FecharMSN(this)"><i class="ion ion-close"></i></a></div>
<div class="col-lg-12">
    <div class="panel panel-blue">
        <div class="panel-heading"><?php echo __("ShortCode de pagina", SIUP_LANG); ?></div>
        <div class="panel-body">
            <h3>Exibe pagina</h3>
            <div class="col-lg-3">
                <h4 style="font-size: medium;">[exibepagina id="1" tela="full"]</h4>
                <p><?php echo __("Exibe uma pagina em uma sessão do site", SIUP_LANG); ?></p>
                <p>
                    <b><?php echo __("id:", SIUP_LANG); ?></b> <?php echo __("ID do post que deve ser exibida.", SIUP_LANG); ?><br/>
                    <b><?php echo __("tela:", SIUP_LANG); ?></b> <?php echo __("Modo de exibição da pagina no paralax.", SIUP_LANG); ?><br/>
                    <b>&blacktriangleright;&nbsp;&nbsp;&nbsp;<?php echo __("Modo full", SIUP_LANG); ?></b> <?php echo __("Área completa do site.", SIUP_LANG); ?><br/>
                    <b>&blacktriangleright;&nbsp;&nbsp;&nbsp;<?php echo __("Modo content", SIUP_LANG); ?></b> <?php echo __("Área de contener do site.", SIUP_LANG); ?><br/>
                </p>
            </div>
            <div class="col-lg-9">
                <div class="col-md-9" style="padding-left: 0px;">
                    <div class="form-group">
                        <label><?php echo __("ID post", SIUP_LANG); ?></label>
                        <select id="idpost" class="form-control" onchange="SetExibePagina()">
                            <?php echo $opcaoidpagina; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php echo __("Tela", SIUP_LANG); ?></label>
                        <select id="tela" class="form-control" onchange="SetExibePagina()">
                            <?php echo $opcaotela; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="InputEmail"><?php echo __("Shortcode", SIUP_LANG); ?></label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                        <input type="text" id="exibepagina" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var $ = jQuery.noConflict();
    function SetExibePagina()
    {
        var $ = jQuery.noConflict();
        var id = $("#idpost").val();
        var tela = $("#tela").val();
        var texto = "[exibepagina";
        if(!Vazio(id))
            texto += " id=\""+ id +"\"";
        else
        {
            alert("Selecione o id o post");
            return;
        }
        if(!Vazio(tela))
            texto += " tela=\""+ tela +"\"";
        texto += "]";
        $("#exibepagina").val(texto);
    }
    $(window).load(function () {
        var $ = jQuery.noConflict();
        $("#exibepagina").val("[exibepagina id=\"1\" tela=\"full\"]");
    });
</script>