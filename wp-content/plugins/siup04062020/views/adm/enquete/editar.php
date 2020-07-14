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
					<input id="idenquete" name="idenquete" type="hidden" value="<?php echo $obj->FormGet('idenquete'); ?>">
					<input id="iduser" name="iduser" type="hidden" value="<?php echo get_current_user_id(); ?>">
					<input id="ip" name="ip" type="hidden" value="<?php echo $obj->FormGet('ip'); ?>">		
					<input id="cadastradoem" name="cadastradoem" type="hidden" value="<?php echo $obj->FormGet('cadastradoem'); ?>">
					<div class="form-group">
						<label for="pergunta" class="col-md-3 control-label"><?php echo __("Pergunta", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<textarea id="pergunta" name="pergunta" rows="8" class="form-control"><?php echo $obj->FormGet('pergunta'); ?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label for="imagem" class="col-md-3 control-label"><?php echo __("Imagem", SIUP_LANG); ?></label>
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
					<div class="form-group">
						<label for="status" class="col-md-3 control-label"><?php echo __("Status", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<select id="status" name="status" class="form-control">
								<?php echo $obj->GerarOpcoesStatus($obj->FormGet('status')); ?>
							</select>
						</div>
					</div>
                    <div class="col-lg-12" style="margin-bottom: 20px;">
                        <h4><?php echo __("Opções de Enquete"); ?></h4>
                        <div class="col-md-3">
							<button type="button" class="btn btn-blue" onclick="AdicionarOpcao()" style="margin-left: 10%;">
								<i class="fa fa-plus-square"></i> Adicionar opção
							</button>
                        </div>
                        <div class="col-md-9" id="AreaOpcao" style="padding: 0px;">							
                        </div>
                    </div>
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
					<?php
						if(!empty($obj->idenquete)):
					?>
					<div class="form-group">
						<label for="cadastradoemshow" class="col-md-3 control-label"><?php echo __("Cadastrado em", SIUP_LANG); ?></label>
						<div class="col-md-3">
							<div class="input-icon">
							<i class="fa fa-calendar"></i>
							<input id="cadastradoemshow" name="cadastradoemshow" type="text" readonly value="<?php echo $obj->FormGet('cadastradoem'); ?>" class="form-control">
							</div>
						</div>
						<label for="nome" class="col-md-3 control-label"><?php echo __("IP", SIUP_LANG); ?></label>
						<div class="col-md-3">
							<div class="input-icon">
							<i class="fa fa-map-marker"></i>
							<input id="ipshow" name="ipshow" type="text" readonly value="<?php echo $obj->FormGet('ip'); ?>"  class="form-control">
							</div>
						</div>
					</div>
					<?php
						endif;
					?>
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
<div id="ocultaropcao" style="display: none;">
    <!--
	<div class="col-md-12" id="Opcao{cont}" style="background-color: #f4ffff; margin: 0px 0px 15px; padding: 8px 0px;">
		<input type="hidden" id="idopcaoenquete{cont}" name="idopcaoenquete[]" value="{idopcaoenquete}">
		<label for="opcao{cont}" class="col-md-12 text-left"><?php echo __("Opção", SIUP_LANG); ?></label>
		<div class="col-md-12">
			<textarea id="opcao{cont}" name="opcao[]" rows="8" style="height: 150px;" class="form-control">{opcao}</textarea>
		</div>
		<label for="votos" class="col-md-2 control-label media"><?php echo __("Número de votos", SIUP_LANG); ?></label>
		<div class="col-md-3 media">
			<input type="number" class="form-control" id="votos{cont}" name="votos[]" value="{votos}">
		</div>
		<label for="posicao" class="col-md-2 control-label media"><?php echo __("Posição", SIUP_LANG); ?></label>
		<div class="col-md-3 media">
			<input type="number" class="form-control" id="posicao{cont}" name="posicao[]" value="{posicao}">
		</div>
		<div class="col-md-2 pull-right media">
			<a href="javascript:;" onclick="ExcluirOpcao(this,'{idopcaoenquete}');"><i class="fa fa-trash-o"></i> <?php echo __("Excluir Opção", SIUP_LANG); ?></a>
		</div>
	</div>
	-->
</div>
<script>
    var ajaxURL = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
	var $ = jQuery.noConflict();
	var templateopcao = null;
    function SalvarEnquete(obj)
    {
        var $ = jQuery.noConflict();
        var aux = $("#pergunta").val();
     
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
    function AdicionarOpcao(opcao = null)
    {
        var $ = jQuery.noConflict();
        var obj = $("#AreaOpcao div.col-md-12:last-of-type");
        var id = 0;
        if(Vazio(obj))
        {
            id = 0;
        }
        else
        {
            id = obj.attr('id');
            id = GetNumero(id);
            id++;
		}
		if(Vazio(opcao))
		{
			opcao = {
				"idopcaoenquete": 0,
				"idenquete": 0,
				"opcao": "",
				"posicao": 0,
				"votos": 0
			};
		}
		if(Vazio(templateopcao))
			templateopcao = $("#ocultaropcao").html();
        var caixa = templateopcao;
        caixa = caixa.replaceAll("{cont}", id);
        caixa = caixa.replaceAll("<!--", "");
        caixa = caixa.replaceAll("-->", "");
        caixa = caixa.replaceAll("{idopcaoenquete}", opcao.idopcaoenquete);
		caixa = caixa.replaceAll("{opcao}", opcao.opcao);
		caixa = caixa.replaceAll("{posicao}", opcao.posicao);
        caixa = caixa.replaceAll("{votos}", opcao.votos);
        $("#AreaOpcao").append(caixa);
	}
	function ExcluirOpcao(obj, id)
    {
        var $ = jQuery.noConflict();
        var pai = $(obj).parent().parent();
        if(Vazio(id))
        {
            pai.remove();
            return
        }

        if(confirm("<?php echo __("Tem certeza que deseja deletar definitivamente esta opção?", SIUP_LANG); ?>"))
        {
            var dados = { "action":"enquete","metodo":"excluiropcao", "id":id };
            $.ajax({
                url: ajaxURL,
                type: 'POST',
                data: dados,
                dataType: 'JSON',
                success: function (data) {
                    if(Vazio(data))
                    {
                        SetErro('<?php  echo __("Ocorreu um erro desconhecido", SIUP_LANG); ?>');
                        return;
                    }
                    if(data.sucesso)
                    {
                        SetInfo(data);
                        pai.remove();
                    }
                    else
                    {
                        SetErro( data.erros);
                    }
                    return;
                },
                fail: function (jqXHR, status, errorThrown) {
                    throw Error('JSONFixture could not be loaded: ' + url + ' (status: ' + status + ', message: ' + errorThrown.message + ')')
                }
            });
        }
	}
	function CarregarOpcoes()
    {
		var $ = jQuery.noConflict();
		var idenquete = $("#idenquete").val();
        if(Vazio(idenquete))
        {
			$("#AreaOpcao").html("");
			AdicionarOpcao();
            return;
        }
        dados = {"action":"enquete","metodo":"carregaropcoes","id":idenquete};
        jQuery.ajax({
            url: ajaxURL,
            type: 'POST',
            data: dados,
            dataType: 'JSON',
            success: function(response) {
                if (response.sucesso)
                {
                    var html = "";
                    if(!Vazio(response.dados.lista))
                    {
						for(let i = 0; i < response.dados.lista.length; i++)
						{
							AdicionarOpcao(response.dados.lista[i]);
						}
                    }
                }
                else
                {
                    console.log(response.erros);
                    //SetErro(response.erros, "#HOSTmensagem");
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
                $('#imagem').val(url);
            });

            //Open the uploader dialog
            arq_btn_imagem.open();
		});		
        wp.editor.initialize(
            'pergunta',
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
		CarregarOpcoes();
    });
</script>