const naj = new Naj('Perfil', null);

//---------------------- Functions -----------------------//
$(document).ready(function() {
    onLoadPerfil();

    $('#submitEditarPerfil').on('click', function(e) {
        e.preventDefault();

        updateUsuario();
    });

    $('#submitUpdateSenha').on('click', function(e) {
        e.preventDefault();

        updatePasswordUsuario();
    });
});

async function onLoadPerfil() {
    response = await naj.getData(`${baseURL}usuarios/show/${btoa(JSON.stringify({id: idUsuarioLogado}))}`);
    naj.loadData('#form-usuario-perfil', response);
    $('.mascaracelular').val(response.mobile_recuperacao).trigger('input');
}

async function updateUsuario() {
    loadingStart('bloqueio-atualizar-dados');
    empresa = sessionStorage.getItem('@NAJ_CLIENTE/identificadorEmpresa');
    let dados = {
        'id'                 : $('input[name=id]').val(),
        'usuario_tipo_id'    : $('input[name=usuario_tipo_id]').val(),
        'login'              : $('input[name=login]').val(),
        'email_recuperacao'  : $('input[name=email_recuperacao]').val(),
        'mobile_recuperacao' : $('input[name=mobile_recuperacao]').val().replace(/\D+/g, ''),
        'nome'               : $('input[name=nome]').val(),
        'apelido'            : $('input[name=apelido]').val(),
        'cpf'                : $('input[name=cpf]').val(),
        'codigo_pessoa'      : empresa,
        'pessoa_codigo'      : empresa,
        "najWeb"             : 1,
        "items" : [
            {
                "pessoa_codigo": empresa,
                "usuario_id"   : 0
            }
        ]
    };

    if(!validaCampoEmail()) {
        loadingDestroy('bloqueio-atualizar-dados');
        NajAlert.toastWarning('O campo login deve ser igual ao campo CPF!');
        return;
    }

    let response = await naj.updateData(`${baseURL}usuarios/${btoa(JSON.stringify({id: $('input[name=id]').val()}))}`, dados);

    if(!response) {        
        loadingDestroy('bloqueio-atualizar-dados');
        NajAlert.toastWarning("Não foi possível atualizar os dados, tente novamente mais tarde!");
    } else {
        loadingDestroy('bloqueio-atualizar-dados');
        NajAlert.toastSuccess("Dados atualizados com sucesso!");
    }

}

async function updatePasswordUsuario() {
    let dados = {
        'senhaAntiga': $('[name=senhaAntiga]').val(),
        'novaSenha'  : $('[name=novaSenha]').val(),
        'najWeb'     : 1
    };

    loadingStart('bloqueio-atualizar-dados');

    //Validando o formulário
    if(!dados.senhaAntiga || !dados.novaSenha)  {
        NajAlert.toastWarning("É necessário informar as duas senhas para realizar alteração!");
        loadingDestroy('bloqueio-atualizar-dados');
        return;
    }

    //Validando se as senhas são diferentes
    if(dados.senhaAntiga == dados.novaSenha)  {
        NajAlert.toastWarning("A nova senha deve ser diferente da antiga!");
        loadingDestroy('bloqueio-atualizar-dados');
        return;
    }

    let r = /^(?=.*\d)(?=.*[a-z])(?:([0-9a-z$*&@#])(?!\1)){4,}$/;

    if(!r.test(dados.novaSenha)) {
        NajAlert.toastWarning("A nova senha deve conter nó minimo 4 digitos, sendo números e letras!");
        loadingDestroy('bloqueio-atualizar-dados');
        return;
    }

    await axios({
        method: 'put',
        url   : `${baseURL}usuarios/password/${btoa(JSON.stringify({id: $('input[name=id]').val()}))}`,
        data  : dados
    })
    .then(response => {
        NajAlert.toast(response.data.mensagem);
        loadingDestroy('bloqueio-atualizar-dados');
    }).catch(e => {
        NajAlert.toast("Erro ao alterar a senha, tente novamente mais tarde!");
        loadingDestroy('bloqueio-atualizar-dados');
    });
}

function onChangeCpf() {
    $('#login').val($('#cpf').val());
}

function validaCampoEmail() {
    let login = $('[name=login]').val(),
        cpf   = $('[name=cpf]').val();

        if(login == cpf) {
            return true;
        }

        return false;
}

//Mascaras
$('.mascaracelular').mask("(000) 0 0000-0000", {placeholder: "(000) 0 0000-0000"});
$('.mascaracpf').mask('000.000.000-00', {placeholder: "___.___.___-__"});