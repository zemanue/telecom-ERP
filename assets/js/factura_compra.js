document.addEventListener('DOMContentLoaded', function() {
    const productoContainer = document.getElementById('producto-container');
    const precioTotalElement = document.getElementById('precio-total');

    function calcularPrecioLinea(item) {
        const selectElement = item.querySelector('.producto-select');
        const cantidadInputElement = item.querySelector('.cantidad-input');
        const precioLineaElement = item.querySelector('.precio-linea');

        if (selectElement && cantidadInputElement && precioLineaElement) {
            const precioUnitario = parseFloat(selectElement.options[selectElement.selectedIndex].getAttribute('data-precio-compra')) || 0;
            const cantidad = parseInt(cantidadInputElement.value) || 0;
            const precioLinea = precioUnitario * cantidad;
            precioLineaElement.textContent = `${precioLinea.toFixed(2)} €`;
            return precioLinea;
        }
        return 0;
    }

    function calcularPrecioTotal() {
        let precioTotal = 0;
        const productoItems = productoContainer.querySelectorAll('.producto-item');
        productoItems.forEach(item => {
            precioTotal += calcularPrecioLinea(item);
        });
        precioTotalElement.textContent = precioTotal.toFixed(2) + ' €';
    }

    // Evento al cambiar el producto
    productoContainer.addEventListener('change', function(event) {
        if (event.target.classList.contains('producto-select')) {
            const item = event.target.closest('.producto-item');
            calcularPrecioLinea(item);
            calcularPrecioTotal();
        }
    });

    // Evento al cambiar la cantidad
    productoContainer.addEventListener('input', function(event) {
        if (event.target.classList.contains('cantidad-input')) {
            const item = event.target.closest('.producto-item');
            calcularPrecioLinea(item);
            calcularPrecioTotal();
        }
    });

    // Evento al añadir un nuevo producto
    document.getElementById('add-producto').addEventListener('click', function () {
        const item = document.querySelector('.producto-item');
        const clone = item.cloneNode(true);

        // Limpiar valores del nuevo clon
        const select = clone.querySelector('select');
        const cantidadInput = clone.querySelector('input[type="number"]');
        const precioLineaSpan = clone.querySelector('.precio-linea');

        if (select) select.selectedIndex = 0;
        if (cantidadInput) cantidadInput.value = 1;
        if (precioLineaSpan) precioLineaSpan.textContent = ' 0.00 €';

        productoContainer.appendChild(clone);
        calcularPrecioTotal(); // Recalcular el total al añadir un nuevo item
    });

    // Manejar botón "Quitar"
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('btn-remove')) {
            const item = e.target.closest('.producto-item');
            const allItems = document.querySelectorAll('.producto-item');
            if (allItems.length > 1) {
                item.remove();
                calcularPrecioTotal(); // Recalcular el total al quitar un item
            }
        }
    });

    // Calcular el precio total inicial
    calcularPrecioTotal();
});