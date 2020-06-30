<div id="HOSTmensagem"><span><i class="ion ion-checkmark"></i> </span>
	<a href="javascript:;" onclick="FecharMSN(this)"><i class="ion ion-close"></i></a>
</div>
<div class="col-lg-12">
	<div class="panel panel-blue">
		<div class="panel-heading"><?php echo __("Formulário de Área", SIUP_LANG); ?></div>
		<div class="panel-body pan">
			<form id="frmArea" action="#" method="POST" class="form-horizontal">
				<div class="form-body pal">
					<input id="action" name="action" type="hidden" value="area">
                    <input id="metodo" name="metodo" type="hidden" value="salvararea">					
					<input id="idarea" name="idarea" type="hidden" placeholder="" value="<?php echo $obj->FormGet('idarea'); ?>">
					<div class="form-group">
						<label for="idpai" class="col-md-3 control-label"><?php echo __("Área pai", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<select id="idpai" name="idpai" class="form-control">
								<?php echo $obj->GerarOpcoesIdpai($obj->FormGet('idpai')); ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="nome" class="col-md-3 control-label"><?php echo __("Nome", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="nome" name="nome" type="text" placeholder="" value="<?php echo $obj->FormGet('nome'); ?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="icone" class="col-md-3 control-label"><?php echo __("Ícone", SIUP_LANG); ?></label>
						<div class="col-md-7">
							<div class="input-icon">
							<i id="iconeveiws" class="<?php echo $iconeveiws; ?>"></i>
							<input id="icone" name="icone" type="text" value="<?php echo $obj->FormGet('icone'); ?>" class="form-control">
							</div>
							<?php echo $listadeicone; ?>
						</div>
						<div class="col-md-2">
							<button type="button" id="btn-icone" class="btn btn-primary" ><i class="ion ion-happy"></i> <?php echo __("Selecionar ícone", SIUP_LANG); ?></button>
						</div>
					</div>
					<div class="form-group">
						<label for="imagem" class="col-md-3 control-label"><?php echo __("Imagem", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-7">
							<div class="input-icon">
							<i class="fa fa-photo"></i>
							<input id="imagem" name="imagem" type="text" placeholder="" value="<?php echo $obj->FormGet('imagem'); ?>" class="form-control">
							</div>
						</div>
						<div class="col-md-2">
							<button type="button" id="btn-imagem" class="btn btn-primary" ><i class="fa fa-upload"></i> <?php echo __("Upload", SIUP_LANG); ?></button>
						</div>
					</div>
				</div>
				<div class="form-actions none-bg">
					<div class="col-md-offset-3 col-md-9">
						<button type="button" class="btn btn-primary" onclick="SalvarArea(this)"><i class="fa fa-save"></i> <?php echo __("Salvar", SIUP_LANG); ?></button>&nbsp;
						<button type="button" class="btn btn-green" onclick="window.history.back();"><i class="fa fa-times"></i> <?php echo __("Cancelar", SIUP_LANG); ?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
    var ajaxURL = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    var $ = jQuery.noConflict();
    function SalvarArea(obj)
    {
        var $ = jQuery.noConflict();
        var aux = $("#nome").val();
        if(Vazio(aux))
        {
            SetErro('<?php echo __("Area é de preenchimento obrigatório.", SIUP_LANG); ?>');
            return;
        }
        dados = $(obj.form).serialize();
        jQuery.ajax({
            url: ajaxURL,
            type: 'POST',
            data: dados,
            dataType: 'JSON',
            success: function(response) {
                if (response.sucesso)
                {
                    SetInfo(response, 'admin.php?page=listaareaSIUP');
                }
                else
                {
                    SetErro(response.erros);
                }
            }
        });
    }
	function SetIcone(obj, icone)
    {
        var $ = jQuery.noConflict();
		$("#iconeveiws").removeClass();
        $(".listaicones").css('display',"none");
		$("#icone").val(icone);
		$("#iconeveiws").addClass(icone);
		$("#icone").get(0).focus();
    }
    $(function() {
		var arq_btn_imagem;
		var $ = jQuery.noConflict();
        $('#btn-imagem').click(function(e) {
            e.preventDefault();

            //If the uploader object has already been created, reopen the dialog
            if (arq_btn_imagem) {
                arq_btn_imagem.open();
                return;
            }

            //Extend the wp.media object
            arq_btn_imagem = wp.media.frames.file_frame = wp.media({
                title: '<?php echo __("Upload arquivo", SIUP_LANG); ?>',
                button: {
                    text: '<?php echo __("Selecione uma imagem para área", SIUP_LANG); ?>'
                },
                multiple: false
            });

            //When a file is selected, grab the URL and set it as the text field's value
            arq_btn_imagem.on('select', function() {
                var $ = jQuery.noConflict();
                attachment = arq_btn_imagem.state().get('selection').first().toJSON();
                var url = '';
                url = attachment.url;
                $('#imagem').val(url);
            });

            //Open the uploader dialog
            arq_btn_imagem.open();
		});
        $('#btn-icone').click(function(e) {
			var $ = jQuery.noConflict();
            e.preventDefault();
			let estado = $(".listaicones").css('display');
			if(estado == "none")
				$(".listaicones").css('display',"block");
			else
				$(".listaicones").css('display',"none");            
		});
    });
</script>