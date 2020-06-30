<div id="HOSTmensagem"><span><i class="ion ion-checkmark"></i> </span>
	<a href="javascript:;" onclick="FecharMSN(this)"><i class="ion ion-close"></i></a>
</div>
<div class="col-lg-12">
	<div class="panel panel-blue">
		<div class="panel-heading"><?php echo __("Formulário de Enquete", SIUP_LANG); ?></div>
		<div class="panel-body pan">
			<form id="frmEnquete" action="#" method="POST" class="form-horizontal">
				<div class="form-body pal">
					<input id="action" name="action" type="hidden" value="enquete">
                    <input id="metodo" name="metodo" type="hidden" value="salvarenquete">
					
					<input id="idenquete" name="idenquete" type="hidden" placeholder="" value="<?php echo $obj->FormGet('idenquete'); ?>">
					<input id="iduser" name="iduser" type="hidden" placeholder="" value="<?php echo get_current_user_id(); ?>">
					<div class="form-group mbn">
						<label for="pergunta" class="col-md-3 control-label"><?php echo __("Pergunta", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<textarea id="pergunta" name="pergunta" rows="3" class="form-control"><?php echo $obj->FormGet('pergunta'); ?></textarea>
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
						<label for="status" class="col-md-3 control-label"><?php echo __("Status", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<select id="status" name="status" class="form-control">
								<?php echo $obj->GerarOpcoesStatus($obj->FormGet('status')); ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="datainicio" class="col-md-3 control-label"><?php echo __("Data inicio", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-group datetimepicker-default date">
								<input type="datetime" id="datainicio" name="datainicio" value="<?php echo $obj->FormGet('datainicio'); ?>" class="form-control">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
						</div>
					</div>
					<div class="form-group">
						<label for="datafim" class="col-md-3 control-label"><?php echo __("Data fim", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-group datetimepicker-default date">
								<input type="datetime" id="datafim" name="datafim" value="<?php echo $obj->FormGet('datafim'); ?>" class="form-control">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
						</div>
					</div>
					<input id="ip" name="ip" type="hidden" placeholder="" value="<?php echo $obj->FormGet('ip'); ?>" class="form-control">
					<input id="cadastradoem" name="cadastradoem" type="datetime" placeholder="" value="<?php echo date ("d-m-Y H:i:s"); ?>">
				</div>
	
				<div class="form-actions none-bg">
					<div class="col-md-offset-3 col-md-9">
						<button type="button" class="btn btn-primary" onclick="SalvarEnquete(this)"><i class="fa fa-save"></i> <?php echo __("Salvar", SIUP_LANG); ?></button>&nbsp;
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
    function SalvarEnquete(obj)
    {
        var $ = jQuery.noConflict();
		var aux = $("#pergunta").val();
        if(Vazio(aux))
        {
            SetErro('<?php echo __("Enquete é de preenchimento obrigatório.", SIUP_LANG); ?>');
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
                    SetInfo(response, 'admin.php?page=listaenqueteSIUP');
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