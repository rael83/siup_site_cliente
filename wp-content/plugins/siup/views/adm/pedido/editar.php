	<div id="HOSTmensagem"><span><i class="ion ion-checkmark"></i> </span>
	<a href="javascript:;" onclick="FecharMSN(this)"><i class="ion ion-close"></i></a>
</div>
<div class="col-lg-12">
	<div class="panel panel-blue">
		<div class="panel-heading"><?php echo __("Formulário de Pedido", SIUP_LANG); ?></div>
		<div class="panel-body pan">
			<form id="frmPedido" action="#" method="POST" class="form-horizontal">
				<div class="form-body pal">
					<input id="action" name="action" type="hidden" value="pedido">
                    <input id="metodo" name="metodo" type="hidden" value="salvarpedido">
					
					<input id="idpedido" name="idpedido" type="hidden" placeholder="" value="<?php echo $obj->FormGet('idpedido'); ?>">
					<input id="ip" name="ip" type="hidden" value="<?php echo $obj->FormGet('ip'); ?>">	
					<div class="form-group">
						<label for="idtarefa" class="col-md-3 control-label"><?php echo __("Tarefa", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<select id="idtarefa" name="idtarefa" class="form-control">
								<?php echo $obj->GerarOpcoesIdtarefa($obj->FormGet('idtarefa')); ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="idcliente" class="col-md-3 control-label"><?php echo __("Cliente", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<select id="idcliente" name="idcliente" class="form-control">
								<?php echo $obj->GerarOpcoesIdcliente($obj->FormGet('idcliente')); ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="idarea" class="col-md-3 control-label"><?php echo __("Área", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<select id="idarea" name="idarea" class="form-control">
								<?php echo $obj->GerarOpcoesIdarea($obj->FormGet('idarea')); ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="pergunta" class="col-md-3 control-label"><?php echo __("Descrição", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<textarea id="descricao" name="descricao" rows="5" class="form-control"><?php echo $obj->FormGet('descricao'); ?></textarea>
						</div>
					</div>
					
                    <!--  INICIO   -->
                    <div class="col-lg-12" style="margin-bottom: 20px;">
                        <h4><?php echo __("Documentos do pedido"); ?></h4>
                        <div class="col-md-3">
                            <span class="btn btn-success fileinput-button" style="width: 250px; display: inline-block">
								<i class="fa fa-plus-square"></i>
								<span><?php echo __("Selecionar documento do pedido..."); ?></span>
								<input id="fileenviar" type="file" name="files[]" accept="image/png,image/gig,image/jpg,image/jpeg" multiple>
							</span>
                            <img id="loadimagemdocumento" src="<?php echo Componente::UrlVendors("pageloader/images/loader1.GIF"); ?>">
                            <div class="inforForm">
                                Adicione o documento ao pedido do clientes.<br/>
                               </div>
                        </div>
                        <div class="col-md-9" id="areafotos">
                            <?php
                            if($obj->idpedido != 0):
	                            $documentos = $obj->GetDocumentos();
	                            if($fotos)
	                            {
		                            foreach ($documentos as $key => $documento)
		                            {
			                ?>
                                        <div class="col-sm-6 col-md-3" id="documento<?php echo $documento->iddocumento; ?>">
                                            <a href="<?php echo $foto->GetDocumento(); ?>" class="thumbnail" data-lightbox="fotos">
                                                <img alt="documento" src="<?php echo $foto->GetDocumento(true); ?>" style="width: 300px; height: auto;">
                                            </a>
                                            <a class="btn-imagem-marcado" title="Marcar como principal" href="javacript:;" onclick="MarcarComoPrincipal(this, <?php echo $foto->idfoto; ?>)"><i class="<?php echo $foto->GetIconePrincipal(); ?>"></i></a>
                                            <a class="btn-imagem-deletar" title="Excluir foto" href="javacript:;" onclick="DeleteFoto(<?php echo $foto->idfoto; ?>)"><i class="fa fa-close"></i></a>
                                        </div>
			                <?php
		                            }
	                            }
                                else
                                {
                            ?>
                                    <div class="alert alert-warning">
                                        <?php echo __("<strong>Atenção!</strong> Nenhuma foto foi cadastrada na galeria até o momento.");?>
                                    </div>
	                        <?php
                                }
                            endif;
                            ?>
                        </div>
                    </div>
                    <!--  FIM   -->
					<div class="form-group">
						<label for="status" class="col-md-3 control-label"><?php echo __("Status", SIUP_LANG); ?>
						<span class="require">*</span></label>
						<div class="col-md-9">
							<select id="status" name="status" class="form-control">
								<?php echo $obj->GerarOpcoesStatus($obj->FormGet('status')); ?>
							</select>
						</div>
					</div>
					<input id="datapedido" name="datapedido" type="hidden" placeholder="" value="<?php echo $obj->FormGet('datapedido'); ?>">
				</div>
				<div class="form-actions none-bg">
					<div class="col-md-offset-3 col-md-9">
						<button type="button" class="btn btn-primary" onclick="SalvarPedido(this)"><i class="fa fa-save"></i> <?php echo __("Salvar", SIUP_LANG); ?></button>&nbsp;
						<button type="button" class="btn btn-green" onclick="window.history.back();"><i class="fa fa-times"></i> <?php echo __("Cancelar", SIUP_LANG); ?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="ocultardocumento" style="display: none;">
    <!--
	<div class="col-md-12" id="Documento{cont}" style="background-color: #f4ffff; margin: 0px 0px 15px; padding: 8px 0px;">
		<input type="hidden" id="iddocumento" name="iddocumento[]" value="{iddocumento}">
		<label for="documento{cont}" class="col-md-12 text-left"><?php echo __("Documento", SIUP_LANG); ?></label>
		<div class="col-md-12">
			<input type="file" class="form-control" id="documento{cont}" name="documento[]" value="{documento}">
			<input id="cadastradoem" name="cadastradoem" type="hidden" value="<?php echo $obj->FormGet('cadastradoem'); ?>">
		</div>
		<div class="col-md-2 pull-right media">
			<a href="javascript:;" onclick="ExcluirDocumento(this,'{iddocumento}');"><i class="fa fa-trash-o"></i> <?php echo __("Excluir Documento", SIUP_LANG); ?></a>
		</div>
	</div>
	-->
</div>

<script>

    var ajaxURL = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
	var $ = jQuery.noConflict();
	var templatedocumento = null;
    function SalvarPedido(obj)
    {
        var $ = jQuery.noConflict();
        var aux = tinyMCE.get('descricao').getContent();
        if(Vazio(aux))
        {
            SetErro('<?php echo __("Pedido é de preenchimento obrigatório.", SIUP_LANG); ?>');
            return;
		}
		$("#descricao").val(aux);
        dados = $(obj.form).serialize();
        jQuery.ajax({
            url: ajaxURL,
            type: 'POST',
            data: dados,
            dataType: 'JSON',
            success: function(response) {
                if (response.sucesso)
                {
                    SetInfo(response, 'admin.php?page=listapedidoSIUP');
                }
                else
                {
                    SetErro(response.erros);
                }
            }
        });
    }

    var ajaxURL = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    var jqXHRXDocumento = null;
    var opcoesDocumento =
        {
            url: ajaxURL,
            dataType: 'json',
            done: function (e, data) {
                console.log("sucesso", e, data);
                if(data.jqXHR.responseJSON.sucesso)
                {
                    var msn = '<?php echo __("O documento foi salvo com sucesso."); ?>';
                    SetMessagem(msn, "#HOSTmensagem");
                }
            },
            progressall: function (e, data) {
                var $ = jQuery.noConflict();
                console.log("progressall", e, data);
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#loadimagemdocumento').css('display', 'block');
                if(data.loaded == data.total)
                {
                    $('#loadimagemdocumento').delay(20000).css('display', 'none');
                }
            },
            add: function (e, data) {
                data.form[0][0].value = "pedido";
                data.form[0][1].value = "salvardocumento";
                var jqXHR = data.submit()
                    .success(function (result, textStatus, jqXHR) {console.log(".success",result, textStatus, jqXHR);
                        if(!Vazio(result.sucesso))
                            addDocumento(result, data);                       
                    })
                    .error(function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR, textStatus, errorThrown);
                    })
                    .complete(function (result, textStatus, jqXHR) {console.log("complete", result, textStatus, jqXHR);
                        if(!Vazio(result.responseJSON.sucesso))
                        {
                            var msn = '<?php echo __("As imagens do Documentos foram salvos com sucesso."); ?>';
                            SetMessagem(msn, "#HOSTmensagem");
                        }
                    })
                    .always(function (e, data) {
                        console.log("always", e, data);
                        if(!Vazio(e.sucesso))
                            SetMessagem(e.dados.mensagem, "#frm_Documento #HOSTmensagem");
                        else
                        {
                            SetErro(e.erros, "#HOSTmensagem");
                        }
                    });
            }
        };
	function AdicionarDocumento(documento = null)
    {
        var $ = jQuery.noConflict();
        var obj = $("#AreaDocumento div.col-md-12:last-of-type");
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
		if(Vazio(documento))
		{
			documento = {
				"iddocumento": 0,
				"idpedido": 0,
				"documento": "",
				"data": 0
			};
		}
		if(Vazio(templatedocumento))
			templatedocumento = $("#ocultardocumento").html();
        var caixa = templatedocumento;
        caixa = caixa.replaceAll("{cont}", id);
        caixa = caixa.replaceAll("<!--", "");
        caixa = caixa.replaceAll("-->", "");
        caixa = caixa.replaceAll("{iddocumento}", documento.iddocumento);
		caixa = caixa.replaceAll("{documento}", documento.documento);
		caixa = caixa.replaceAll("{data}", documento.data);
        $("#AreaDocumento").append(caixa);
	}
	function ExcluirDocumento(obj, id)
    {
        var $ = jQuery.noConflict();
        var pai = $(obj).parent().parent();
        if(Vazio(id))
        {
            pai.remove();
            return
        }

        if(confirm("<?php echo __("Tem certeza que deseja deletar definitivamente este documento?", SIUP_LANG); ?>"))
        {
            var dados = { "action":"pedido","metodo":"excluirdocumento", "id":id };
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
	function CarregarDocumento()
    {
		var $ = jQuery.noConflict();
		var idpedido = $("#idpedido").val();
        if(Vazio(idpedido))
        {
			$("#AreaDocumento").html("");
			AdicionarDocumento();
            return;
        }
        dados = {"action":"pedido","metodo":"carregardocumentos","id":idpedido};
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
							AdicionarDocumento(response.dados.lista[i]);
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
        //region Foto
    function addDocumento(obj, data)
    {
        var $ = jQuery.noConflict();
        var area = $("#areadocumentos div.alert-warning");
        if(!Vazio(area))
            area.remove();
        var caixa = $("#ocultodocumento").html();
        if(Vazio(caixa))
            return caixa;
        caixa = caixa.replaceAll("<!--", "");
        caixa = caixa.replaceAll("-->", "");
        caixa = ObjetoReplace(obj, caixa);
        $("#areadocumentos").append(caixa);
    }
    function DeleteDocumentos(id)
    {
        var $ = jQuery.noConflict();
        if(Vazio(id))
        {
            return
        }
        var informe = "Deseja excluir o documento do pedido?";
        if (!confirm(informe))
        {
            return
        }
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: ajaxURL,
            data:{"action":"excluirdocumento","id":id},
            success: function (data) {
                if(Vazio(data))
                {
                    SetErro("<?php echo __("Não foi possível excluir este documento do pedido."); ?>");
                    return;
                }
                if(data.sucesso)
                {
                    SetInfo(data);
                    $("#Documento" + id).remove();
                }
                else
                {
                    SetErro(data.erro);
                }
                return;
            },
            fail: function (jqXHR, status, errorThrown) {
                throw Error('JSONFixture could not be loaded: ' + url + ' (status: ' + status + ', message: ' + errorThrown.message + ')')
            }
        });
    }
 
    //endregion
    $(function() {
        var $ = jQuery.noConflict();
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
        jqXHRXDocumento = $('#fileenviar').fileupload(opcoesDocumento);
    });
</script>