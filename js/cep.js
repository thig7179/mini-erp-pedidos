function aplicarMascaraCep() {
    const cepInput = document.getElementById('cep');

    if (!cepInput) return;

    cepInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 5) {
            value = value.slice(0, 5) + '-' + value.slice(5, 8);
        }
        e.target.value = value;
    });

    cepInput.addEventListener('blur', function () {
        const cep = cepInput.value.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(res => res.json())
                .then(data => {
                    const infoDiv = document.getElementById('cep-info');
                    if (!infoDiv) return;
                    if (data.erro) {
                        infoDiv.innerHTML = '<span class="text-danger">CEP não encontrado.</span>';
                    } else {
                        infoDiv.innerHTML = `<b>Endereço:</b> ${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                    }
                });
        }
    });
}

document.addEventListener('DOMContentLoaded', aplicarMascaraCep);
