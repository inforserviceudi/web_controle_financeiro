/* Add here all your JS customizations */

$(document).ready(function(){
    $(".mask-datas").mask('00/00/0000');
    $(".mask-cep").mask('00000-000');
    $(".mask-ddd").mask('00');
    $('.mask-valor').mask('#.##0,00', {reverse: true});
    $(".mask-telefone").mask('0000-0000');
    $(".mask-ddd_telefone").mask('(00) 0000-0000');
    $(".mask-celular").mask('00000-0000');
    $(".mask-ddd_celular").mask('(00) 00000-0000');
    $(".mask-hora").mask('00:00');
    $(".mask-cpf").mask('000.000.000-00');
    $(".mask-cnpj").mask('00.000.000/0000-00');
    $('.mask-porcentagem').mask('000.00', { reverse: true });

    $("#form-nova-empresa #tp_doc").on("change", function(){
        const tp_doc = $(this).children(':selected').val();

        if( tp_doc === "cnpj"){
            $("#form-nova-empresa #ds_cpf_cnpj")
                .val("")
                .removeClass('mask-cpf')
                .addClass('mask-cnpj')
                .attr('placeholder', '00.000.000/0000-00');
        }else if( tp_doc === "cpf"){
            $("#form-nova-empresa #ds_cpf_cnpj")
                .val("")
                .removeClass('mask-cnpj')
                .addClass('mask-cpf')
                .attr('placeholder', '000.000.000-00');
        }
    });

    $('.modal-basic').magnificPopup({
        type: 'inline',
        preloader: true,
        modal: true
    });

    $("#section_permissoes #usuario_id").on("change", function(){
        var usuario_id = $(this).children(':selected').val();
        var base_url = window.location.origin;
        var route = base_url + "/usuarios/"+usuario_id+"/permissoes";

        if( usuario_id > 0){
            $("#section_permissoes #form-trocar-usuario").attr('action', route).submit();
        }
    });
});

/* Modal Dismiss */
$(document).on('click', '.modal-dismiss', function (e) {
    e.preventDefault();
    $.magnificPopup.close();
});

function submitForm(id_form) {
    var _token = $("meta[name='csrf-token']").attr("content");
    var url = $("#" + id_form).attr('action');
    var method = $("#" + id_form).attr('method');
    var form = $("#"+id_form).get(0);
    var formData = new FormData(form);

    $('#' + id_form + ' .btn-spin')
    .html('Salvando <i class="fa fa-spin fa-spinner fa-fw"></i>')
    .prop('disabled', true);

    $('#' + id_form + ' .btn-spin-check', '#' + id_form + ' .btn-spin-pencil', '#' + id_form + ' .btn-spin-trash-o')
    .html('<i class="fa fa-spin fa-spinner fa-fw"></i>')
    .prop('disabled', true);

    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : _token }
    });
    $.ajax({
        url: url,
        method: method,
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(result) {
            $('#' + id_form + ' .btn-spin').html('<i class="fa fa-check fa-fw"></i> Salvar').prop('disabled', false);
            $('#' + id_form + ' .btn-spin-check').html('<i class="fa fa-check fa-fw"></i>').prop('disabled', false);
            $('#' + id_form + ' .btn-spin-pencil').html('<i class="fa fa-pencil fa-fw"></i>').prop('disabled', false);
            $('#' + id_form + ' .btn-spin-trash-o').html('<i class="fa fa-trash-o fa-fw"></i>').prop('disabled', false);

            $('#' + id_form + ' .btn-atualiza-empresa').html('Atualizar').prop('disabled', false);

            if (result['erro'] == 'erro') {
                if (result.errors) {
                    $.each(result.errors, function(key, value) {
                        getMessage(result['tipo'], result['titulo'], value);
                    });
                    $("#" + id_form + " .validar").css("border", "1px solid red");
                } else {
                    getMessage(result['tipo'], result['titulo'], result['message']);
                    $("#" + id_form + " .validar").css("border", "1px solid red");
                }
            } else {
                if(result['message']){
                    getMessage(result['tipo'], result['titulo'], result['message']);
                }

                if(result['tabela']){
                    $("#tbody_novo_registro, #tabela_relatorio").html(result['tabela']);
                }

                if(result['href']){
                    setInterval(() => {
                        window.location.href = result['href'];
                    }, 2000);
                }
            }
        }
    });
}

function selecionaCategoria(categoria_id){
    $("#form-categoria #categoria_id").val(categoria_id);
    $("#form-categoria").submit();
}

function ajaxTransacao(route, nr_parcelas, frequencia, valor, tbody_id, nm_modal, transacao_id = null){
    var _token = $("meta[name='csrf-token']").attr("content");

    $.ajax({
        url: route,
        method: 'post',
        data: {
            nr_parcelas:nr_parcelas,
            frequencia:frequencia,
            valor:valor,
            nm_modal:nm_modal,
            transacao_id:transacao_id,
            _token:_token
        },
        dataType: 'json',
        success: function(result) {
            $("#"+tbody_id).html(result['tabela']);

            if( result['erro'] ){
                getMessage(result['tipo'], result['titulo'], result['message']);
            }
        }
    });
}

function informarPagamento(route, tbody_id, parcela_id){
    var _token = $("meta[name='csrf-token']").attr("content");

    $.ajax({
        url: route,
        method: 'post',
        data: {
            parcela_id:parcela_id,
            _token:_token
        },
        dataType: 'json',
        success: function(result) {
            $("#"+tbody_id).html(result['tabela']);

            if( result['erro'] ){
                getMessage(result['tipo'], result['titulo'], result['message']);
            }
        }
    });
}

function removeParcela(parcela_id, route, tbody_id){
    var valor_total = $("#form-transacao #vr_total").val();
    var _token = $("meta[name='csrf-token']").attr("content");

    $.ajax({
        url: route,
        method: 'post',
        data: { parcela_id:parcela_id, valor_total:valor_total, _token:_token },
        dataType: 'json',
        success: function(result) {
            $("#"+tbody_id).html(result['tabela']);

            if( result['erro'] ){
                getMessage(result['tipo'], result['titulo'], result['message']);
            }
        }
    });
}
