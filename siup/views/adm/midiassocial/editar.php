<div id="HOSTmensagem"><span><i class="ion ion-checkmark"></i> </span>
	<a href="javascript:;" onclick="FecharMSN(this)"><i class="ion ion-close"></i></a>
</div>
<div class="col-lg-12">
	<div class="panel panel-blue">
		<div class="panel-heading"><?php echo __("Formulário de Midiassocial", SIUP_LANG); ?></div>
		<div class="panel-body pan">
			<form id="frmMidiassocial" action="#" method="POST" class="form-horizontal">
				<div class="form-body pal">
					<input id="action" name="action" type="hidden" value="midiassocial">
                    <input id="metodo" name="metodo" type="hidden" value="salvarmidiassocial">
					
					<input id="idmidiassocial" name="idmidiassocial" type="hidden" placeholder="" value="<?php echo $obj->FormGet('idmidiassocial'); ?>">
					<div class="form-group">
						<label for="iduser" class="col-md-3 control-label"><?php echo __("Iduser", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<select id="iduser" name="iduser" class="form-control">
								<?php echo $obj->GerarOpcoesIduser($obj->FormGet('iduser')); ?>
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
						<label for="imagem" class="col-md-3 control-label"><?php echo __("Imagem", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="imagem" name="imagem" type="text" placeholder="" value="<?php echo $obj->FormGet('imagem'); ?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="icone" class="col-md-3 control-label"><?php echo __("Icone", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="icone" name="icone" type="text" placeholder="" value="<?php echo $obj->FormGet('icone'); ?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="link" class="col-md-3 control-label"><?php echo __("Link", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="link" name="link" type="text" placeholder="" value="<?php echo $obj->FormGet('link'); ?>" class="form-control">
							</div>
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
					<div class="form-group">
						<label for="ip" class="col-md-3 control-label"><?php echo __("Ip", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="ip" name="ip" type="text" placeholder="" value="<?php echo $obj->FormGet('ip'); ?>" class="form-control">
							</div>
						</div>
					</div>
					<input id="cadastradoem" name="cadastradoem" type="hidden" placeholder="" value="<?php echo $obj->FormGet('cadastradoem'); ?>">
				</div>
				<div class="form-actions none-bg">
					<div class="col-md-offset-3 col-md-9">
						<button type="button" class="btn btn-primary" onclick="SalvarMidiassocial(this)"><i class="fa fa-save"></i> <?php echo __("Salvar", SIUP_LANG); ?></button>&nbsp;
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
    function SalvarMidiassocial(obj)
    {
        var $ = jQuery.noConflict();
        var aux = $("#nome").val();
        if(Vazio(aux))
        {
            SetErro('<?php echo __("Midiassocial é de preenchimento obrigatório.", SIUP_LANG); ?>');
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
                    SetInfo(response, 'admin.php?page=listamidiassocialSIUP');
                }
                else
                {
                    SetErro(response.erros);
                }
            }
        });
    }
    $(function() {
    });
</script>