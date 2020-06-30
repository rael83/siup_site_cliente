<div id="HOSTmensagem"><span><i class="ion ion-checkmark"></i> </span>
	<a href="javascript:;" onclick="FecharMSN(this)"><i class="ion ion-close"></i></a>
</div>
<div class="col-lg-12">
	<div class="panel panel-blue">
		<div class="panel-heading"><?php echo __("Formulário de Evento", SIUP_LANG); ?></div>
		<div class="panel-body pan">
			<form id="frmEvento" action="#" method="POST" class="form-horizontal">
				<div class="form-body pal">
					<input id="action" name="action" type="hidden" value="evento">
                    <input id="metodo" name="metodo" type="hidden" value="salvarevento">
					
					<input id="idevento" name="idevento" type="hidden" placeholder="" value="<?php echo $obj->FormGet('idevento'); ?>">
					<input id="iduser" name="iduser" type="hidden" value="<?php echo $obj->FormGet('iduser'); ?>">
					<input id="ip" name="ip" type="hidden" value="<?php echo $obj->FormGet('ip'); ?>">		
					<input id="cadastradoem" name="cadastradoem" type="hidden" value="<?php echo $obj->FormGet('cadastradoem'); ?>">
					<div class="form-group">
						<label for="titulo" class="col-md-3 control-label"><?php echo __("Titulo", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<div class="input-icon">
							<i class="fa fa-user"></i>
							<input id="titulo" name="titulo" type="text" placeholder="" value="<?php echo $obj->FormGet('titulo'); ?>" class="form-control">
							</div>
						</div>
					</div>
					<div class="form-group mbn">
						<label for="resumo" class="col-md-3 control-label"><?php echo __("Resumo", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<textarea id="resumo" name="resumo" rows="3" class="form-control"><?php echo $obj->FormGet('resumo'); ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="descricao" class="col-md-3 control-label"><?php echo __("Descricao", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<textarea id="descricao" name="descricao" rows="8" class="form-control"><?php echo $obj->FormGet('descricao'); ?></textarea>
						</div>
					</div>
					<input id="datainicio" name="datainicio" type="hidden" placeholder="" value="<?php echo $obj->FormGet('datainicio'); ?>">
					<input id="datafim" name="datafim" type="hidden" placeholder="" value="<?php echo $obj->FormGet('datafim'); ?>">
					<div class="form-group">
						<label for="datainicio" class="col-md-3 control-label"><?php echo __("Data de início", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-3">
							<div class="input-group datetimepicker-default date">
								<input type="datetime-local" id="datainicio" name="datainicio" value="<?php echo $obj->FormGet('datainicio'); ?>" class="form-control">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
						</div>
						<label for="datafim" class="col-md-3 control-label"><?php echo __("Data de término", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-3">
							<div class="input-group datetimepicker-default date">
								<input type="datetime-local" id="datafim" name="datafim" value="<?php echo $obj->FormGet('datafim'); ?>" class="form-control">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
						</div>
					</div>
					<div class="form-group">
						<label for="imagem" class="col-md-3 control-label"><?php echo __("Imagem", SIUP_LANG); ?></label>
						<div class="col-md-7">
							<div class="input-icon">
							<i class="fa fa-photo"></i>
							<input id="imagem" name="imagem" type="text" value="<?php echo $obj->FormGet('imagem'); ?>" class="form-control">
							</div>
						</div>
						<div class="col-md-2">
							<button type="button" id="btn-imagem" class="btn btn-primary" ><i class="fa fa-upload"></i> <?php echo __("Upload", SIUP_LANG); ?></button>
						</div>
					</div>
					<div class="form-group">
						<label for="thumbnail" class="col-md-3 control-label"><?php echo __("Thumbnail", SIUP_LANG); ?></label>
						<div class="col-md-7">
							<div class="input-icon">
							<i class="fa fa-photo"></i>
							<input id="thumbnail" name="thumbnail" type="text" value="<?php echo $obj->FormGet('thumbnail'); ?>" class="form-control">
							</div>
						</div>
						<div class="col-md-2">
							<button type="button" id="btn-thumbnail" class="btn btn-primary" ><i class="fa fa-upload"></i> <?php echo __("Upload", SIUP_LANG); ?></button>
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
				</div>
				<div class="form-actions none-bg">
					<div class="col-md-offset-3 col-md-9">
						<button type="button" class="btn btn-primary" onclick="SalvarEvento(this)"><i class="fa fa-save"></i> <?php echo __("Salvar", SIUP_LANG); ?></button>&nbsp;
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
    function SalvarEvento(obj)
    {
        var $ = jQuery.noConflict();
        var aux = $("#titulo").val();

		var aux = tinyMCE.get('descricao').getContent();
		var aux1 = tinyMCE.get('resumo').getContent();

		alert(aux);

        if(Vazio(aux))
        {
            SetErro('<?php echo __("Evento é de preenchimento obrigatório.", SIUP_LANG); ?>');
            return;
        }

		$("#descricao").val(aux);
		$("#resumo").val(aux1);
        dados = $(obj.form).serialize();
        jQuery.ajax({
            url: ajaxURL,
            type: 'POST',
            data: dados,
            dataType: 'JSON',
            success: function(response) {
                if (response.sucesso)
                {
                    SetInfo(response, 'admin.php?page=listaeventoSIUP');
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
                    text: '<?php echo __("Selecione uma imagem para o Evento", SIUP_LANG); ?>'
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


		var arq_btn_thumbnail;
        $('#btn-thumbnail').click(function(e) {
            e.preventDefault();

            //If the uploader object has already been created, reopen the dialog
            if (arq_btn_thumbnail) {
                arq_btn_thumbnail.open();
                return;
            }

            //Extend the wp.media object
            arq_btn_thumbnail= wp.media.frames.file_frame = wp.media({
                title: '<?php echo __("Upload arquivo", SIUP_LANG); ?>',
                button: {
                    text: '<?php echo __("Selecione um thumbnail para o Evento", SIUP_LANG); ?>'
                },
                multiple: false
            });

            //When a file is selected, grab the URL and set it as the text field's value
            arq_btn_thumbnail.on('select', function() {
                var $ = jQuery.noConflict();
                attachment = arq_btn_thumbnail.state().get('selection').first().toJSON();
                var url = '';
                url = attachment.url;
                $('#thumbnail').val(url);
            });

            //Open the uploader dialog
            arq_btn_thumbnail.open();
		});	
		
		wp.editor.initialize(
            'resumo',
            {
                tinymce: {
                    wpautop:true,
                    plugins:"charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview",
                    toolbar1:"formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,dfw,wp_adv",
                    toolbar2:"strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",
                    toolbar3:"",
                    toolbar4:"",
                    wp_autoresize_on:true,
                    add_unload_trigger:true
                },
                quicktags: true
            }

			
		);

		wp.editor.initialize(
            'descricao',
            {
                tinymce: {
                    wpautop:true,
                    plugins:"charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview",
                    toolbar1:"formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,wp_more,spellchecker,dfw,wp_adv",
                    toolbar2:"strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",
                    toolbar3:"",
                    toolbar4:"",
                    wp_autoresize_on:true,
                    add_unload_trigger:true
                },
                quicktags: true
            }

			
		);
    });
</script>