<x-layouts.app>

    <flux:breadcrumbs class="mb-8">
        <flux:breadcrumbs.item href="{{ route('ordenes.create') }}">Creando Orden</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Confirmar Pedido</flux:breadcrumbs.item>
    </flux:breadcrumbs>


    <div class="container mx-auto">


        @if (!empty($carrito))


            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Producto
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Cantidad / Observación
                            </th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carrito as $id => $producto)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                <td class="px-6 py-4">
                                    {{ $producto['nombre'] }}
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('ordenes.actualizarCarrito', $id) }}" method="POST">
                                        @csrf

                                        <div class="relative flex items-center max-w-[18rem]">
                                            <!-- Botón Decrementar -->
                                            <button type="button" id="decrement-button-{{ $id }}"
                                                data-input-counter-decrement="cantidad-{{ $id }}"
                                                class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                                                <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 18 2">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                                </svg>
                                            </button>

                                            <!-- Campo de Cantidad -->
                                            <input type="text" id="cantidad-{{ $id }}" name="cantidad"
                                                data-input-counter data-input-counter-min="1"
                                                data-input-counter-max="1000" aria-describedby="helper-text-explanation"
                                                class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="999" value="{{ $producto['cantidad'] }}" required />

                                            <!-- Botón Incrementar -->
                                            <button type="button" id="increment-button-{{ $id }}"
                                                data-input-counter-increment="cantidad-{{ $id }}"
                                                class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                                                <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 18 18">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Campo de Observación -->
                                        <textarea name="observacion" style="resize: vertical; height: {{ !empty($producto['observacion']) ? '100px' : '50px' }};" placeholder="Observación"
                                            class="mt-2 bg-gray-50 border border-gray-300 rounded-lg p-2 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ $producto['observacion'] }}</textarea>

                                        <!-- Botón Actualizar -->
                                        <button type="submit"
                                            class="mt-2 bg-green-800 text-white px-4 py-2 rounded-lg hover:bg-green-600">Actualizar</button>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <!-- Botón Eliminar -->
                                    <form action="{{ route('ordenes.eliminarCarrito', $id) }}" method="POST">
                                        @csrf
                                        <flux:button type="submit" icon="trash" variant="danger"></flux:button>

                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>





            <div
                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 p-4 rounded-lg shadow-md mt-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Total Orden:
                    <span class="text-2xl font-bold text-fuchsia-800  dark:text-fuchsia-600">
                        ${{ array_sum(array_map(function ($producto) {return $producto['precio'] * $producto['cantidad'];}, $carrito)) }}
                    </span>
                </h3>
                <br>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Mesa:
                    <button id="btnMesas" onclick="abrirModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                        Seleccionar Mesa
                    </button>
                </h3>

                <!-- Formulario para guardar la orden -->
                <form id="frmConfirmarOrden" class="mt-4">
                    @csrf
                    <div class="form-group">
                        <input id="idMesa" type="hidden" name="mesa">
                    </div>

                    <button id="guardarOrden" type="button"
                        class="btn btn-fuchsia w-full font-semibold py-2 px-4 rounded-lg transition duration-200">
                        Guardar Orden
                    </button>
                </form>
            </div>
        @else
            <p class="text-gray-500 mt-4">No hay productos en el carrito.</p>
        @endif


        <div id="modalMesas" class="fixed z-50 inset-0 hidden overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-auto">
                <div class="modal-bg fixed inset-0 bg-[rgba(36,12,31,0.444)] transition-opacity"></div>

                <div
                    class="modal-content-mesas  bg-white border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700 rounded-lg shadow-xl overflow-hidden transform transition-all max-w-lg w-full">
                    <div class="modal-header flex justify-between p-4 border-b border-gray-300">
                        <h3 class="text-lg font-medium">Selecciona una Mesa</h3>
                        <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-900">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <!-- Aquí se generan las mesas con JavaScript -->
                    </div>

                    <div class="modal-footer p-4 border-t border-gray-300">
                        <button onclick="cerrarModal()"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>



    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar si hay una mesa seleccionada en localStorage
            const mesaSeleccionada = JSON.parse(localStorage.getItem('mesaSeleccionada'));

            if (mesaSeleccionada) {
                document.getElementById('btnMesas').innerHTML = ` ${mesaSeleccionada.nombre}`;
                document.getElementById('idMesa').value = mesaSeleccionada.id;

                // Cambiar el color del botón para reflejar la selección
                const botonMesa = document.getElementById('btnMesas');
                botonMesa.classList.remove('bg-gray-300', 'hover:bg-gray-400', 'text-gray-800');
                botonMesa.classList.add('bg-green-800', 'hover:bg-green-600', 'text-white');

            }
        });

        const mesas = @json($mesas); // Pasamos las mesas desde Laravel a JavaScript

        // Función para abrir el modal y cargar las mesas
        function abrirModal() {
            // Mostrar el modal
            document.getElementById('modalMesas').classList.remove('hidden');

            // Limpiar el contenido anterior
            const modalBody = document.querySelector('.modal-body');
            modalBody.innerHTML = '';

            // Generar las tarjetas de mesas
            mesas.forEach(mesa => {
                const mesaDiv = document.createElement('div');
                mesaDiv.classList.add('mesa-card', 'cursor-pointer', 'p-4', 'rounded-lg');
                getColorClaseEstado(mesa.estado).split(' ').forEach(clase => mesaDiv.classList.add(clase));
                mesaDiv.setAttribute('data-mesa-id', mesa.id);
                mesaDiv.innerHTML = `
            <h5 class="text-lg font-semibold">${mesa.nombre}</h5>
            <span class="block text-sm">Estado: ${mesa.estado}</span>
            ${mesa.estado !== 'libre' ? `<span class="text-sm">Tiempo: ${calcularTiempoEnEstado(mesa.hora_estado)}</span>` : ''}
        `;
                mesaDiv.onclick = () => seleccionarMesa(mesa);
                modalBody.appendChild(mesaDiv);
            });
        }

        // Función para cerrar el modal
        function cerrarModal() {
            document.getElementById('modalMesas').classList.add('hidden');
        }

        // Función para seleccionar una mesa y cambiar su estado
        function seleccionarMesa(mesa) {
            cerrarModal();

            // Guardar la mesa seleccionada en localStorage (puede cambiarse por sesión del servidor)
            localStorage.setItem('mesaSeleccionada', JSON.stringify({
                id: mesa.id,
                nombre: mesa.nombre
            }));

            console.log(`Mesa seleccionada: ${mesa.nombre}, Estado: ${mesa.estado}`);
            // Actualizar el texto del botón con el nombre de la mesa seleccionada
            document.getElementById('btnMesas').innerHTML = ` ${mesa.nombre}`;
            document.getElementById('idMesa').value = mesa.id;

            // Cambiar el color del botón para reflejar la selección
            const botonMesa = document.getElementById('btnMesas');
            botonMesa.classList.remove('bg-gray-300', 'hover:bg-gray-400', 'text-gray-800');
            botonMesa.classList.add('bg-green-800', 'hover:bg-green-600', 'text-white');


        }

        // Función para obtener la clase de color según el estado
        function getColorClaseEstado(estado) {
            switch (estado) {
                case 'libre':
                    return 'bg-green-800 text-white';
                case 'ocupada':
                    return 'bg-red-800 text-white';
                case 'reservada':
                    return 'bg-yellow-800 text-white';
                case 'limpieza':
                    return 'bg-gray-500 text-white';
                default:
                    return 'bg-gray-500 text-black';
            }
        }

        // Función para calcular el tiempo en estado
        function calcularTiempoEnEstado(horaEstado) {
            const horaInicio = new Date(horaEstado);
            const ahora = new Date();
            const diffMs = ahora - horaInicio;
            const diffHrs = Math.floor(diffMs / (1000 * 60 * 60));
            const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
            return `${diffHrs}h ${diffMins}min`;
        }


        document.querySelector('#guardarOrden').addEventListener('click', function(e) {
            event.preventDefault(); // Evita que la página se recargue

            // Selecciona el formulario correspondiente al producto
            let form = document.querySelector('#frmConfirmarOrden');

            // Obtén el token CSRF desde la metaetiqueta
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Crea un objeto FormData para manejar los datos del formulario
            let formData = new FormData(form);

            // Realiza la petición AJAX usando Fetch
            fetch('/admin/ordenes/confirmarGuardar', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        localStorage.removeItem('mesaSeleccionada'); // Borra un ítem específico

                        Swal.fire({
                            title: data.message,
                            icon: "success",
                            draggable: true
                        }).then((result) => {                                                       
                                // Redirigir a la página de creación de nueva orden
                                window.location.href = data.redirect;
                            
                        });

                        // Aquí puedes actualizar el carrito visualmente sin recargar la página                        
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: data.error,
                            footer: 'Nodo'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: 'Debe seleccionar una mesa',
                        footer: 'Nodo'
                    });
                });
        });



        document.querySelectorAll('button[data-input-counter-decrement]').forEach(button => {
            button.addEventListener('click', function() {
                let input = document.querySelector(
                    `input#${this.getAttribute('data-input-counter-decrement')}`);
                let currentValue = parseInt(input.value, 10);
                if (currentValue > 1) input.value = currentValue - 1;
            });
        });

        document.querySelectorAll('button[data-input-counter-increment]').forEach(button => {
            button.addEventListener('click', function() {
                let input = document.querySelector(
                    `input#${this.getAttribute('data-input-counter-increment')}`);
                let currentValue = parseInt(input.value, 10);
                if (currentValue < 1000) input.value = currentValue + 1;
            });
        });
    </script>




</x-layouts.app>
