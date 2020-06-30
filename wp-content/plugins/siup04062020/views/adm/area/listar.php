<div class="col-lg-12">
    <div class="panel panel-blue">
        <div class="panel-heading">
        <?php echo __("Filtro de Área", SIUP_LANG) ?>
            <div class="tools">
                <i class="fa fa-chevron-down" onclick="ExibePainel(this,'#painelarea')"></i>
            </div>
        </div>
        <div id="painelarea" class="panel-body pan">
            <form id="frmarea" action="<?php echo site_url('area/listar/'); ?>" class="horizontal-form" method="GET">
                <div class="form-body pal">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="buscar" class="control-label"><?php echo __("Buscar", SIUP_LANG) ?></label>
                                <div class="input-icon right">
                                    <input id="buscar" name="buscar" type="text" placeholder="Buscar" value="<?php echo Componente::Request("buscar"); ?>" class="form-control">
                                </div>
                            </div>
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="idpai" class="control-label"><?php echo __("Área Pai", SIUP_LANG) ?></label>
                                <select id="idpai" name="idpai" class="form-control">
                                    <?php echo $obj->GerarOpcoesIdpai($obj->Get('idpai'), __("--Todas--", SIUP_LANG)); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="divImportar" style="display: none; background-color: #e0f3e3; padding: 20px 0px;">
                        <div class="form-group">
                            <label for="subarea" class="col-md-3 control-label text-right"><?php echo __("Importar arquivo xls de áreas", SIUP_LANG); ?> </label>
                            <div class="col-md-5">
                                <div class="input-icon">
                                    <i class="fa fa-table"></i>
                                    <input id="filearea" name="filearea" type="file" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="button" id="btn-EnviarExcel" class="btn btn-primary" onclick="EnviarExcel()">
                                    <i class="fa fa-cloud-upload"></i> <?php echo __("Enviar Arquivo", SIUP_LANG) ?>
                                </button>
                                <a href="javascript:;" class="btn pull-right btn-exportar" style="color: #FF0000;" onclick="FecharImportarArea();">
                                    <i class="fa fa-close"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="divloding" style="display: none; margin-bottom: 15px;">
                        <div class="col-lg-12 mtm">
                            <div id="pageloader4">
                                <div class="spinner">
                                    <div class="spinftw"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="divBarra" style="display: none;">
                        <div class="col-lg-12 mtm">
                            <h5> <?php echo __("Importação de Área", SIUP_LANG); ?></h5>
                            <div class="progress progress-striped active">
                                <div id="processo" role="progressbar" aria-valuetransitiongoal="80" class="progress-bar progress-bar-warning" aria-valuenow="80" style="width: 80%;">80%</div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="divExportar" style="display: none;">
                        <div class="col-lg-12 mtm mbm">
                            <a href="javascript:;" class="btn pull-right btn-exportar" onclick="FecharExportarArea();">
                                <i class="fa fa-close"></i>
                            </a>
                            <a id="downExpotar" href="#" target="_blank" class="btn btn-green pull-right">
                                <i class="fa fa-cloud-download"></i> <?php echo __("Download da exportação", SIUP_LANG) ?>
                            </a>
                            <span> <?php echo __("Caso o documento não seja baixado automaticamente, clique aqui", SIUP_LANG); ?></span>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right pal">
                    <button id="btn-pesquisa" type="button" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo __("Pesquisa", SIUP_LANG) ?></button> &nbsp;
                    <button type="button" class="btn btn-green" onclick="AdicionarNovo(GetURL('admin.php?page=editarareaSIUP&id=<?php echo $obj->Get('idarea', 0); ?>'))"><i class="fa fa-plus"></i> <?php echo __("Adicionar Área", SIUP_LANG) ?></button> &nbsp;
                    <button type="button" class="btn btn-blue" onclick="AbrirImportarArea()"><i class="fa fa fa-sign-in"></i> <?php echo __("Importar Área", SIUP_LANG) ?></button> &nbsp;
                    <button type="button" class="btn btn-blue" onclick="ExportarÁrea();"><i class="fa fa-sign-out"></i> <?php echo __("Exportar Área", SIUP_LANG) ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="portlet box">
        <div class="portlet-header">
            <div class="caption"><?php echo __("Lista de Área") ?></div>
        </div>
        <div class="portlet-body">
            <div class="row mbm">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table id="area_tabela" class="table table-hover table-bordered table-advanced display">
                            <thead>
                            <tr>
                                
								<th style="width: 10%;"><?php echo __("ID", SIUP_LANG); ?></th>
								<th style="width: 25%;"><?php echo __("Área", SIUP_LANG); ?></th>
                                <th style="width: 25%;"><?php echo __("Área Pai", SIUP_LANG); ?></th>
								<th style="width: 15%;"><?php echo __("Icone", SIUP_LANG); ?></th>
								<th style="width: 15%;"><?php echo __("Imagem", SIUP_LANG); ?></th>
                                <th style="width: 10%;"><?php echo __("Ação") ?></th>
                            </tr>
                            <tbody>
                            </tbody>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var ajaxURL = '<?php echo admin_url('admin-ajax.php'); ?>';
    var $ = jQuery.noConflict();
    function Excluir(obj,id, nome)
    {
        var $ = jQuery.noConflict();
        var informe = "Deseja excluir a área "+nome+"?";
        if (confirm(informe))
        {
            dados = { "action":"area","metodo":"excluirarea", "id":id };
            $.ajax({
                url: ajaxURL,
                type: 'POST',
                data: dados,
                dataType: 'JSON',
                success: function(response) {
                    if (response.sucesso)
                    {
                        SetInfo(response);
                        $(obj).parent().parent().remove();
                    }
                    else
                    {
                        SetErro(response.erros);
                    }
                }
            });
        }
    }    
    function Exportar(obj, posicao,total)
    {
        var $ = jQuery.noConflict();
        dados = {
            "FILTRO": $(obj.form).serializeArray(),
            "action":"area",
            "metodo":"exportararea"
        };
        $.ajax({
            url: ajaxURL,
            type: 'POST',
            data: dados,
            dataType: 'JSON',
            success: function(response) {
                if(response.sucesso)
                {
                    titulo = response.dados.titulo;
                    msn = response.dados.mensagem;
                    MsnSucesso(titulo, msn);
                    $('#divloding').slideUp("slow");
                    $('#divExportar').css('display','block');
                    $('#downExpotar').attr("href", response.dados.url);
                    window.open(response.dados.url, "_blank");
                }
                else
                {
                    msn = response.dados.erro;
                    alert(msn);
                }
            }
        });
    }
    function FecharExportarArea()
    {
        var $ = jQuery.noConflict();
        $('#filearea').val("");
        $('#divExportar').slideUp("slow");
    }
    function FecharImportarArea()
    {
        var $ = jQuery.noConflict();        
        $('#divloding').css('display','none');
        $('#divBarra').delay(3000).fadeOut( "slow" );
        $('#divImportar').slideUp("slow");
        document.getElementById('filearea').files = null;
        $('filearea').val('');
    }
    function AbrirImportarArea()
    {
        var $ = jQuery.noConflict();
        $('#divImportar').slideDown("slow");
    }
    function EnviarExcel(){
        var $ = jQuery.noConflict();
        var property = document.getElementById('filearea').files[0];
        if(Vazio(property))
        {
            alert("<?php echo __("Você deve selecionar o arquivo com os dados de importação de Áreas", SIUP_LANG);?>");
            return;
        }
        var file_name = property.name;
        var file_extension = file_name.split('.').pop().toLowerCase();
        var urlfile = ajaxURL;
        var file_size = property.size;
        var max_size = parseInt("<?php echo $tamanhomax; ?>") * Math.pow(1024, 2);

        if(jQuery.inArray(file_extension,['csv','xls','xlsx','']) == -1){
            alert("<?php echo __("Extensão de arquivo inválida<br/>extensões permitidas são csv, xls e xlsx", SIUP_LANG); ?>");
            return;
        }
        if(file_size > max_size){
            alert("<?php echo sprintf(__("O arquivo não pode ser enviado porque excede o tamanho maxímo de %s", SIUP_LANG), $tamanhomax); ?>");
            return;
        }
        var form_data = new FormData();
        form_data.append("file",property);
        form_data.append("action","area");
        form_data.append("metodo","enviarexcelarea");
        $.ajax({
            url: urlfile,
            method:'POST',
            data:form_data,
            contentType: false,
            cache:false,
            processData:false,
            beforeSend:function(xhr){
                $('#divloding').css('display','block');
            },
            success:function(data){
                if(data.sucesso)
                {
                    $('#divloding').css('display','none');
                    titulo = "<?php echo __("Importação");?>";
                    msn = "<?php echo __("Arquivo foi enviado com sucesso.<br/>Aguarde a finalização do processo de importação de Área.", SIUP_LANG);?>";
                    MsnSucesso(titulo, msn);
                    ImportacaoExcel(data.dados.file,0, data.dados.total);
                }
                else
                {
                    $('#divloding').css('display','none');
                    msn = data.dados.erro;
                    alert(msn);
                }
            },
            error: function(XHR, textStatus, errorThrown){
                $('#divloding').css('display','none');
                msn = "<?php echo __("Falha ao enviar o arquivo de importação.", SIUP_LANG);?>";
                alert(msn);
            }
        });
    }
    function ImportacaoExcel(file, posicao, total){
        var $ = jQuery.noConflict();
        var data = {
            "file":file,
            "posicao": posicao,
            "total": total,
            "action":"area",
            "metodo":"importacaoexcelarea",
        };

        $.ajax({
            url: ajaxURL,
            method:'POST',
            data:data,
            beforeSend:function(xhr){
                var aux = total;
                var porcente = 0;
                var texto = posicao+' / '+total;
                if(Vazio(aux))
                    aux = 1;
                porcente = Math.ceil((posicao/aux) * 100);
                $('#divBarra .progress .progress-bar').css('width',porcente+'%').html(texto);
                $('#divBarra').css('display','block');
                $('#divloding').css('display','block');
            },
            success:function(data){
                if(response.sucesso)
                {
                    if(response.dados.status == "Finalizado")
                    {
                        titulo = response.dados.titulo;
                        msn = response.dados.mensagem;
                        MsnSucesso(titulo, msn);
                        FecharImportarArea();
                        $("#btn-pesquisa").trigger("click");
                    }
                    else
                    {
                        ImportacaoExcel(response.dados.file, response.dados.posicao, response.dados.total);
                    }
                }
                else
                {
                    msn = response.dados.erro;
                    alert(msn);
                }
            },
            error: function(XHR, textStatus, errorThrown){
                $('#divloding').css('display','none');
                $('#divBarra').css('display','none');
                msn = "<?php echo __("Falha na importação dos dados.", SIUP_LANG);?>";
                alert(msn);
            }
        });
    }
    
    $(document).ready(function() {
        var $ = jQuery.noConflict();
        var el_datatable = $('#area_tabela').DataTable({
            "language":{ "url": "//cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json" },
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": ajaxURL,
                "data": function ( d ) {
                    var pequisa = {
                        "FILTRO": $('#frmarea').serializeArray(),
                        "action":"area",
                        "metodo":"listadearea",
                    }
                    return $.extend( {}, d, pequisa );
                },
                "type": 'post',
                "dataType": 'json',
            },
            columns: [
                {"data": "idarea", render: $.fn.dataTable.render.number(',', '.', '')},
				{'data': 'nome'},
				{'data': 'areapai'},
				{'data': 'icone',
                    "render": function ( data, type, row, meta ) {
                        let link = '';
                        if(Vazio(data))
                            link = 'Sem ícone';
                        else
                            link = `<i class="${data} icone"></i>`;
                        return link;
                    }
                },
				{'data': 'imagem',
                    "render": function ( data, type, row, meta ) {
                        let link = '';
                        if(Vazio(data))
                            link = 'Sem imagem';
                        else
                            link = `<img src="${data}" alt='${data}'>`;
                        return link;
                    }
                },
                {"data": "idarea",
                    "render": function ( data, type, row, meta ) {
                        var url = GetURL('admin.php?page=editarareaSIUP&id=' + row['idarea']);
                        var link = '<a href="'+url+'" class="btn-tab-editar" title="<?php echo __("Editar área", SIUP_LANG);?>"><i class="fa fa-edit"></i></a>';
                        url = "Excluir(this,'"+row['idarea']+"','"+row['nomedoarea']+"')";
                        link += '<a href="javascript:;" onclick="'+url+'" class="btn-tab-editar" title="<?php echo __("Excluir área", SIUP_LANG);?>"><i class="ion ion-ios7-trash"></i></a>';
                        return link;
                    }
                }
            ],
        });

        $("#btn-pesquisa").click(function(){
            var $ = jQuery.noConflict();
            el_datatable.ajax.reload();
            return false;
        });
        el_datatable.on( 'draw', function () {
            var $ = jQuery.noConflict();
            $('#area_tabela_filter').css('display','none');
        } );

    });
</script>