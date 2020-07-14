<div id="HOSTmensagem"><span><i class="ion ion-checkmark"></i> </span>
	<a href="javascript:;" onclick="FecharMSN(this)"><i class="ion ion-close"></i></a>
</div>
<div class="col-lg-12">
	<div class="panel panel-blue">
		<div class="panel-heading"><?php echo __("Formulário de Cliente", SIUP_LANG); ?></div>
		<div class="panel-body pan">
			<form id="frmCliente" action="#" method="POST" class="form-horizontal">
				<div class="form-body pal">
					<input id="action" name="action" type="hidden" value="cliente">
                    <input id="metodo" name="metodo" type="hidden" value="salvarcliente">
					
					<input id="idcliente" name="idcliente" type="hidden" placeholder="" value="<?php echo $obj->FormGet('idcliente'); ?>">
					<div class="form-group">
						<label for="idsiup" class="col-md-3 control-label"><?php echo __("Idsiup", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<select id="idsiup" name="idsiup" class="form-control">
								<?php echo $obj->GerarOpcoesIdsiup($obj->FormGet('idsiup')); ?>
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
						<label for="mae" class="col-md-3 control-label"><?php echo __("Mae", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="mae" name="mae" type="text" placeholder="" value="<?php echo $obj->FormGet('mae'); ?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="email" class="col-md-3 control-label"><?php echo __("Email", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="email" name="email" type="text" placeholder="" value="<?php echo $obj->FormGet('email'); ?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="senha" class="col-md-3 control-label"><?php echo __("Senha", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="senha" name="senha" type="password" placeholder="" value="<?php echo $obj->FormGet('senha'); ?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="datanascimento" class="col-md-3 control-label"><?php echo __("Data de Nascimento", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-group datetimepicker-default date">
								<input type="date" id="datanascimento" name="datanascimento" value="<?php echo $obj->FormGet('datanascimento'); ?>" class="form-control">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
						</div>
						</div>
					<div class="form-group">
						<label for="telefone" class="col-md-3 control-label"><?php echo __("Telefone", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="telefone" name="telefone" type="text" placeholder="" value="<?php echo $obj->FormGet('telefone'); ?>" class="form-control">
							</div>
						</div>
					</div>
				</div>
				<div class="form-actions none-bg">
					<div class="col-md-offset-3 col-md-9">
						<button type="button" class="btn btn-primary" onclick="SalvarCliente(this)"><i class="fa fa-save"></i> <?php echo __("Salvar", SIUP_LANG); ?></button>&nbsp;
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
    function SalvarCliente(obj)
    {
        var $ = jQuery.noConflict();
        var aux = $("#nome").val();
        if(Vazio(aux))
        {
            SetErro('<?php echo __("Cliente é de preenchimento obrigatório.", SIUP_LANG); ?>');
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
                    SetInfo(response, 'admin.php?page=listaclienteSIUP');
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