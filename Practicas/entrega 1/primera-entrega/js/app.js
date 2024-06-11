const menuCampo = document.getElementById('idItemMenu');
const mesaCampo = document.getElementById('nromesa');
const procesarBtn = document.getElementById('procesar');

procesarBtn.disabled = true;

menuCampo.addEventListener("change", validarCampos);
mesaCampo.addEventListener("change", validarCampos);

function validarCampos() {
   
    if ( menuCampo.value != "" && mesaCampo.value != "" ) {
        procesarBtn.disabled = false;
    }

}
