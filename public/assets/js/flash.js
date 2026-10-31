// Fecha a mensagem de sucesso sozinha depois de 5 segundos
function setupFlash() {
    const alert = document.querySelector('.alert-success');

    if (!alert) {
        return;
    }

    setTimeout(function () {
        alert.remove();
    }, 5000);
}

setupFlash();
