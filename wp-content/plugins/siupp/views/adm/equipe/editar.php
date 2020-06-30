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
						<label for="foto" class="col-md-3 control-label"><?php echo __("Foto", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="foto" name="foto" type="text" placeholder="" value="<?php echo $obj->FormGet('foto'); ?>" class="form-control">
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
        var aux = $("#nome").val();
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
    });
</script>