<div class="col-lg-12">
    <div class="panel panel-blue">
        <div class="panel-heading">
        <?php echo __("Filtro de Enquete", SIUP_LANG) ?>
            <div class="tools">
                <i class="fa fa-chevron-down" onclick="ExibePainel(this,'#painelenquete')"></i>
            </div>
        </div>
        <div id="painelenquete" class="panel-body pan">
            <form id="frmenquete" action="<?php echo site_url('enquete/listar/'); ?>" class="horizontal-form" method="GET">
                <div class="form-body pal">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="buscar" class="control-label"><?php echo __("Buscar", SIUP_LANG) ?></label>
                                <div class="input-icon right">
                                    <input id="buscar" name="buscar" type="text" placeholder="Buscar" value="<?php echo Componente::Request("buscar"); ?>" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="iduser" class="control-label"><?php echo __("Usuário", SIUP_LANG) ?></label>
                                <select id="iduser" name="iduser" class="form-control">
                                    <?php echo $obj->GerarOpcoesUser($obj->Get('iduser'), __("--Todos--", SIUP_LANG)); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status" class="control-label"><?php echo __("Status", SIUP_LANG) ?></label>
                                <select id="status" name="status" class="form-control">
                                    <?php echo $obj->GerarOpcoesStatus($obj->Get('status'), __("--Todas--", SIUP_LANG)); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="datainicioinicio" class="control-label"><?php echo __("Data de Inicio entre", SIUP_LANG) ?></label>
                                <div class="input-group datetimepicker-default date">
                                    <input id="datainicioinicio" name="datainicioinicio" type="date" value="" class="form-control">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="datainiciofim" class="control-label"><?php echo __("a", SIUP_LANG) ?></label>
                                <div class="input-group datetimepicker-default date">
                                    <input id="datainiciofim" name="datainiciofim" type="date" value="" class="form-control">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="datafiminicio" class="control-label"><?php echo __("Data de término entre", SIUP_LANG) ?></label>
                                <div class="input-group datetimepicker-default date">
                                    <input id="datafiminicio" name="datafiminicio" type="date" value="" class="form-control">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="datafimfim" class="control-label"><?php echo __("a", SIUP_LANG) ?></label>
                                <div class="input-group datetimepicker-default date">
                                    <input id="datafimfim" name="datafimfim" type="date" value="" class="form-control">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cadastradoeminicio" class="control-label"><?php echo __("Cadastrado entre", SIUP_LANG) ?></label>
                                <div class="input-group datetimepicker-default date">
                                    <input id="cadastradoeminicio" name="cadastradoeminicio" type="date" value="" class="form-control">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cadastradoemfim" class="control-label"><?php echo __("a", SIUP_LANG) ?></label>
                                <div class="input-group datetimepicker-default date">
                                    <input id="cadastradoemfim" name="cadastradoemfim" type="date" value="" class="form-control">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="divImportar" style="display: none; background-color: #e0f3e3; padding: 20px 0px;">
                        <div class="form-group">
                            <label for="subarea" class="col-md-3 control-label text-right"><?php echo __("Importar arquivo xls de enquetes", SIUP_LANG); ?> </label>
                            <div class="col-md-5">
                                <div class="input-icon">
                                    <i class="fa fa-table"></i>
                                    <input id="fileenquete" name="fileenquete" type="file" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="button" id="btn-EnviarExcel" class="btn btn-primary" onclick="EnviarExcel()">
                                    <i class="fa fa-cloud-upload"></i> <?php echo __("Enviar Arquivo", SIUP_LANG) ?>
                                </button>
                                <a href="javascript:;" class="btn pull-right btn-exportar" style="color: #FF0000;" onclick="FecharImportarEnquete();">
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
                            <h5> <?php echo __("Importação de Enquete", SIUP_LANG); ?></h5>
                            <div class="progress progress-striped active">
                                <div id="processo" role="progressbar" aria-valuetransitiongoal="80" class="progress-bar progress-bar-warning" aria-valuenow="80" style="width: 80%;">80%</div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="divExportar" style="display: none;">
                        <div class="col-lg-12 mtm mbm">
                            <a href="javascript:;" class="btn pull-right btn-exportar" onclick="FecharExportarEnquete();">
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
                    <button type="button" class="btn btn-green" onclick="AdicionarNovo(GetURL('admin.php?page=editarenqueteSIUP&id=<?php echo $obj->Get('idenquete', 0); ?>'))"><i class="fa fa-plus"></i> <?php echo __("Adicionar Enquete", SIUP_LANG) ?></button> &nbsp;
                    <button type="button" class="btn btn-blue" onclick="AbrirImportarEnquete()"><i class="fa fa fa-sign-in"></i> <?php echo __("Importar Enquete", SIUP_LANG) ?></button> &nbsp;
                    <button type="button" class="btn btn-blue" onclick="ExportarEnquete(this);"><i class="fa fa-sign-out"></i> <?php echo __("Exportar Enquete", SIUP_LANG) ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="portlet box">
        <div class="portlet-header">
            <div class="caption"><?php echo __("Lista de Enquete") ?></div>
        </div>
        <div class="portlet-body">
            <div class="row mbm">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table id="enquete_tabela" class="table table-hover table-bordered table-advanced display">
                            <thead>
                            <tr>                                
								<th style="width: 8%;"><?php echo __("ID", SIUP_LANG); ?></th>
								<th style="width: 26%;"><?php echo __("Título", SIUP_LANG); ?></th>
								<th style="width: 20%;"><?php echo __("Imagem", SIUP_LANG); ?></th>
								<th style="width: 8%;"><?php echo __("Status", SIUP_LANG); ?></th>
								<th style="width: 10%;"><?php echo __("Data de Início", SIUP_LANG); ?></th>
								<th style="width: 10%;"><?php echo __("Data de término", SIUP_LANG); ?></th>
								<th style="width: 10%;"><?php echo __("Cadastrado em", SIUP_LANG); ?></th>
                                <th style="width: 8%;"><?php echo __("Ação") ?></th>
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
</div>
<script>
    var ajaxURL = '<?php echo admin_url('admin-ajax.php'); ?>';
    var $ = jQuery.noConflict();
    function Excluir(obj,id, nome)
    {
        var $ = jQuery.noConflict();
        var informe = "Deseja excluir o enquete "+nome+"?";
        if (confirm(informe))
        {
            dados = { "action":"enquete","metodo":"excluirenquete", "id":id };
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
    function Exportar(obj, file = "", posicao = 0, total = 0)
    {
        var $ = jQuery.noConflict();
        let dados = {
            "FILTRO": $(obj.form).serializeArray(),
            "action": "enquete",
            "metodo": "exportarenquete",
            "file": file,
            "posicao": posicao,
            "total": total
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
                    if(response.dados.finalizado)
                    {
                        $('#divloding').slideUp("slow");
                        $('#divBarra').slideUp("slow");
                        $('#divExportar').css('display','block');
                        $('#downExpotar').attr("href", response.dados.url);
                        window.open(response.dados.url, "_blank");
                    }
                    else
                    {
                        ExportarEnquete(response.dados.file, response.dados.posicao, response.dados.total);
                    }
                }
                else
                {
                    msn = response.dados.erro;
                    alert(msn);
                    $('#divloding').slideUp("slow");
                    $('#divBarra').slideUp("slow");
                }
            },
            error: function(XHR, textStatus, errorThrown){
                let msn = "<?php echo __("Falha na de verificação de enquete");?>";
                $('#divloding').slideUp("slow");
                $('#divBarra').slideUp("slow");
                alert(msn);
            }
        });
    }
    function FecharExportarEnquete()
    {
        var $ = jQuery.noConflict();
        $('#fileenquete').val("");
        $('#divExportar').slideUp("slow");
    }
    function FecharImportarEnquete()
    {
        var $ = jQuery.noConflict();        
        $('#divloding').css('display','none');
        $('#divBarra').delay(3000).fadeOut( "slow" );
        $('#divImportar').slideUp("slow");
        document.getElementById('fileenquete').files = null;
        $('fileenquete').val('');
    }
    function AbrirImportarEnquete()
    {
        var $ = jQuery.noConflict();
        $('#divImportar').slideDown("slow");
    }
    function EnviarExcel(){
        var $ = jQuery.noConflict();
        var property = document.getElementById('fileenquete').files[0];
        if(Vazio(property))
        {
            alert("<?php echo __("Você deve selecionar o arquivo com os dados de importação de Enquetes", SIUP_LANG);?>");
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
        form_data.append("action","enquete");
        form_data.append("metodo","enviarexcelenquete");
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
                    msn = "<?php echo __("Arquivo foi enviado com sucesso.<br/>Aguarde a finalização do processo de importação de Enquete.", SIUP_LANG);?>";
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
            "action":"enquete",
            "metodo":"importacaoexcelenquete",
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
                        FecharImportarEnquete();
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
        var el_datatable = $('#enquete_tabela').DataTable({
            "language":{ "url": "//cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json" },
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": ajaxURL,
                "data": function ( d ) {
                    var pequisa = {
                        "FILTRO": $('#frmenquete').serializeArray(),
                        "action":"enquete",
                        "metodo":"listadeenquete",
                    }
                    return $.extend( {}, d, pequisa );
                },
                "type": 'post',
                "dataType": 'json',
            },
            columns: [
                {"data": "idenquete", render: $.fn.dataTable.render.number(',', '.', '')},            
				{'data': 'titulo',
                    "render": function ( data, type, row, meta ) {
                        let link = data;
                        if(!Vazio(row['usuario']))
                            link += `<br/><span><b>Usuário:</b> ${row['usuario']}</span>`;
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
				{'data': 'status'},
				{'data': 'datainicio'},
				{'data': 'datafim'},
				{'data': 'cadastradoem'},
                {"data": "idenquete",
                    "render": function ( data, type, row, meta ) {
                        var url = GetURL('admin.php?page=editarenqueteSIUP&id=' + row['idenquete']);
                        var link = '<a href="'+url+'" class="btn-tab-editar" title="<?php echo __("Editar enquete", SIUP_LANG);?>"><i class="fa fa-edit"></i></a>';
                        url = "Excluir(this,'"+row['idenquete']+"','"+row['titulo']+"')";
                        link += '<a href="javascript:;" onclick="'+url+'" class="btn-tab-editar" title="<?php echo __("Excluir enquete", SIUP_LANG);?>"><i class="ion ion-ios7-trash"></i></a>';
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
            $('#enquete_tabela_filter').css('display','none');
        } );

    });
</script>