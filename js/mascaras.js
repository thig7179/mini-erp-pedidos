function aplicarMascaraPreco() {
    const camposPreco = [document.getElementById('preco'), document.getElementById('preco-simulado')];

    camposPreco.forEach(input => {
        if (!input) return;

        input.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            value = (value / 100).toFixed(2);
            value = value.replace('.', ',');
            e.target.value = 'R$ ' + value;
        });
    });

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function () {
            camposPreco.forEach(input => {
                if (input && input.value.includes('R$')) {
                    let raw = input.value.replace('R$', '').replace(',', '.').replace(/\s/g, '');
                    input.value = parseFloat(raw);
                }
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', aplicarMascaraPreco);
