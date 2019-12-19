$(document).ready(function() {
    
    $('#formCodigoAcesso').submit(function(e) {
        e.preventDefault();

        response = true;
        $('#iconCodigoAcesso').removeClass();

        if(response) {
            $('#iconCodigoAcesso').addClass('fas fa-check');
            $('#iconCodigoAcesso').addClass('iconSuccess');
            $('#divResultadoUsuario')[0].innerHTML = "Usuário: Roberto Oswaldo Klann<br>CPF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 104.059.619-36";
            $('#proximoAcesso')[0].disabled = false;
        } else {
            $('#iconCodigoAcesso').addClass('fas fa-times');
            $('#iconCodigoAcesso').addClass('iconError');
            $('#divResultadoUsuario')[0].innerHTML = "O código informado é inválido!";
            $('#proximoAcesso')[0].disabled = true;
        }
    });
});

function onClickAvancar(remove, active) {
    $(active).addClass('active');
    $('[href="'+active+'"]').addClass('active');
    $(remove).removeClass('active');
    $('[href="'+remove+'"]').removeClass('active');
}

/**
 * Permite apenas caracteres númericos.
 */
function onlynumber(evt) {
    let theEvent = evt || window.event,
        key      = theEvent.keyCode || theEvent.which;

    key = String.fromCharCode(key);
    let regex = /^[0-9.]+$/;
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault)
            theEvent.preventDefault();
    }
}

/**
 * Esconde os campos CPF/CNPJ conforme seleção.
 */
function onChangeTipoPessoa() {
    let oTipoPessoa = $("#tipopessoa"),
        oDivCpf     = $("#divcpf"),
        oDivCnpj    = $("#divcnpj"),
        oInputCpf   = $("#cpf"),
        oInputCnpj  = $("#cnpj");

    if (oTipoPessoa.val() === "J") {
        oInputCpf.val("");
        oDivCpf.hide();
        oDivCnpj.show();
    } else {
        oInputCnpj.val("");
        oDivCnpj.hide();
        oDivCpf.show();
    }
}

$('.mascaracpf').mask('000.000.000-00', {placeholder: "___.___.___-__"});
$('.mascaracnpj').mask('00.000.000/0000-00', {placeholder: "__.___.___/____-__"});
$('.mascaracep').mask("00.000-000", {placeholder: "__.____-__"});