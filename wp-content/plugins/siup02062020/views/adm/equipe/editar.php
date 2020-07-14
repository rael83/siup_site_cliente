<div id="HOSTmensagem"><span><i class="ion ion-checkmark"></i> </span>
	<a href="javascript:;" onclick="FecharMSN(this)"><i class="ion ion-close"></i></a>
</div>
<div class="col-lg-12">
	<div class="panel panel-blue">
		<div class="panel-heading"><?php echo __("Formulário de Equipe", SIUP_LANG); ?></div>
		<div class="panel-body pan">
			<form id="frmEquipe" action="#" method="POST" class="form-horizontal">
				<div class="form-body pal">
					<input id="action" name="action" type="hidden" value="equipe">
                    <input id="metodo" name="metodo" type="hidden" value="salvarequipe">
					
					<input id="idequipe" name="idequipe" type="hidden" placeholder="" value="<?php echo $obj->FormGet('idequipe'); ?>">
					<input id="iduser" name="iduser" type="hidden" placeholder="" value="<?php echo get_current_user_id(); ?>">
					<div class="form-group">
						<label for="Nome" class="col-md-3 control-label"><?php echo __("Nome", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="Nome" name="Nome" type="text" placeholder="" value="<?php echo $obj->FormGet('Nome'); ?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="cargo" class="col-md-3 control-label"><?php echo __("Cargo", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="cargo" name="cargo" type="text" placeholder="" value="<?php echo $obj->FormGet('cargo'); ?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group mbn">
						<label for="descricao" class="col-md-3 control-label"><?php echo __("Descricao", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<textarea id="descricao" name="descricao" rows="3" class="form-control"><?php echo $obj->FormGet('descricao'); ?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label for="Foto" class="col-md-3 control-label"><?php echo __("Foto", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-7">
							<div class="input-icon">
							<i class="fa fa-photo"></i>
							<input id="foto" name="foto" type="text" placeholder="" value="<?php echo $obj->FormGet('foto'); ?>" class="form-control">
							</div>
						</div>
						<div class="col-md-2">
							<button type="button" id="btn-imagem" class="btn btn-primary" ><i class="fa fa-upload"></i> <?php echo __("Upload", SIUP_LANG); ?></button>
						</div>
					</div>
					<div class="form-group">
						<label for="status" class="col-md-3 control-label"><?php echo __("Status", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<select id="status" name="status" class="form-control">
								<?php echo $obj->GerarOpcoesStatus($obj->FormGet('status')); ?>
							</select>
						</div>
					</div>
					<input id="ip" name="ip" type="hidden" placeholder="" value="<?php echo $obj->FormGet('ip'); ?>" class="form-control">
					<input id="cadastradoem" name="cadastradoem" type="hidden" placeholder="" value="<?php echo $obj->FormGet('cadastradoem'); ?>">
				</div>
				<div class="form-actions none-bg">
					<div class="col-md-offset-3 col-md-9">
						<button type="button" class="btn btn-primary" onclick="SalvarEquipe(this)"><i class="fa fa-save"></i> <?php echo __("Salvar", SIUP_LANG); ?></button>&nbsp;
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
    function SalvarEquipe(obj)
    {
        var $ = jQuery.noConflict();
        var aux = $("#Nome").val();
        if(Vazio(aux))
        {
            SetErro('<?php echo __("Equipe é de preenchimento obrigatório.", SIUP_LANG); ?>');
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
                    SetInfo(response, 'admin.php?page=listaequipeSIUP');
                }
                else
                {
                    SetErro(response.erros);
                }
            }
        });
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
                $('#foto').val(url);
            });

            //Open the uploader dialog
            arq_btn_imagem.open();
		});
    });
</script>