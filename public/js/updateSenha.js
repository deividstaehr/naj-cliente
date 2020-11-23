$(document).ready(function() {
    if(!$('#cpf').val()) {
        $('#cpf')[0].disabled = false;
    }
});

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