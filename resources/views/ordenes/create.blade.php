<x-layouts.app>
 
    <div class="mb-8 flex justify-between items-center">
        <flux:breadcrumbs class="mb-8">
            <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Creando Orden</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <flux:button href="{{ route('ordenes.confirmar') }}" icon="shopping-cart">Confirmar Orden
            <span id="cart-count">
                {{ count($carrito) }}
            </span>
        </flux:button>

    </div>
   
   

    <div class="container mx-auto">

        <!-- Input para búsqueda -->
        <div class="mb-4">
            <input type="text" id="search-product" placeholder="Buscar por código, nombre o categoría"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>


        <!-- Carrusel de categorías -->

        <div class="flex overflow-x-auto mb-6">
            <button type="button" id="btn-todos" onclick="filtrarProductos('todos')"
                class="selected mr-2 py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700">
                <span class="loading-icon hidden" id="loading-todos">
                    <!-- SVG del ícono de carga -->
                    <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0116 0h2a10 10 0 10-20 0h2z">
                        </path>
                    </svg>
                </span>
                <span class="btn-text">
                    Todos
                </span>
            </button>
            @foreach ($categorias as $categoria)
                <button type="button" id="btn-{{ $categoria->id }}" onclick="filtrarProductos('{{ $categoria->id }}')"
                    class="mr-2 py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700">
                    <span class="loading-icon hidden" id="loading-{{ $categoria->id }}">
                        <!-- SVG del ícono de carga -->
                        <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0116 0h2a10 10 0 10-20 0h2z">
                            </path>
                        </svg>
                    </span>
                    <span class="btn-text">
                        {{ $categoria->nombre }}
                    </span>
                </button>
            @endforeach
        </div>


        <!-- Grilla de productos
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="productos-grid">
            @foreach ($productos as $producto)
                <div class="producto-card w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 cursor-pointer"
                    data-categoria-id="{{ $producto->categoria_id }}" data-nombre="{{ $producto->nombre }}"
                    data-codigo="{{ $producto->codigo }}" data-categoria="{{ $producto->categoria->nombre }}"
                    onclick="abrirModal({{ $producto }})">
                    <div class="px-2 pb-2">
                        <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{ $producto->nombre }}</h5>
                        <span
                            class=" text-fuchsia-800 text-xs font-semibold px-2.5 py-0.5 rounded-sm dark:text-fuchsia-600 ms-3">{{ $producto->categoria->nombre }}</span>
                        {{-- <div class="flex items-center justify-between">
                            <span class="text-1xl font-bold text-gray-900 dark:text-white">Precio: ${{ $producto->precio }}</span>
                        </div> --}}
                    </div>
                </div>
            @endforeach
        </div>-->

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="productos-grid">
    @foreach ($productos as $producto)
        <div class="producto-card w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 cursor-pointer"
            data-categoria-id="{{ $producto->categoria_id }}" 
            data-nombre="{{ $producto->nombre }}"
            data-codigo="{{ $producto->codigo }}" 
            data-categoria="{{ $producto->categoria->nombre }}"
            onclick="abrirModal({{ $producto }})">

            <div class="px-2 pb-2">
                {{-- Mostrar imagen si existe --}}
                @php
                    $rutaImagen = public_path("images/productos/{$producto->codigo}.png");
                @endphp

                @if (file_exists($rutaImagen))
                    <img src="{{ asset("images/productos/{$producto->codigo}.png") }}" 
                         alt="{{ $producto->nombre }}" 
                         class="w-24 h-24 object-contain mx-auto mb-2">
                @endif

                <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white text-center">
                    {{ $producto->nombre }}
                </h5>
                <span
                    class="text-fuchsia-800 text-xs font-semibold px-2.5 py-0.5 rounded-sm dark:text-fuchsia-600 ms-3">
                    {{ $producto->categoria->nombre }}
                </span>
            </div>
        </div>
    @endforeach
</div>



    </div>

    <!-- Modal para agregar producto -->
    <div id="modalProducto" class="modal fixed inset-0 flex justify-center items-center hidden">
        <div
            class="bg-white border border-gray-200 shadow-sm dark:bg-gray-800 dark:border-gray-700 p-6 rounded-lg w-full max-w-md relative">
            <span class="absolute top-2 right-4 text-2xl cursor-pointer" onclick="cerrarModal()">&times;</span>

            <h2 id="nombreProducto" class="text-xl font-semibold mb-2"></h2>
            <p id="codigoProducto" class="text-gray-500 mb-4"></p>

            <!-- Formulario -->

            <form id="formAgregarCarrito" onsubmit="agregarAlCarrito(event)">
                @csrf
                <input type="hidden" id="productoIdInput" name="productoId">
                <div class="mb-4">
                    <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad:</label>

                    <div class="relative flex items-center max-w-[18rem]">
                        <button type="button" id="decrement-button" data-input-counter-decrement="cantidad"
                            class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                            <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M1 1h16" />
                            </svg>
                        </button>
                        <input type="text" id="cantidad" name="cantidad" data-input-counter data-input-counter-min="1"
                            data-input-counter-max="1000" aria-describedby="helper-text-explanation"
                            class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="999" value="1" required />
                        <button type="button" id="increment-button" data-input-counter-increment="cantidad"
                            class="bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:border-gray-600 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 dark:focus:ring-gray-700 focus:ring-2 focus:outline-none">
                            <svg class="w-3 h-3 text-gray-900 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M9 1v16M1 9h16" />
                            </svg>
                        </button>
                    </div>

                </div>

                <div class="mb-4">
                    <label for="observacion" class="block text-sm font-medium text-gray-700">Observación:</label>
                    <textarea id="observacion" name="observacion" rows="2"
                        class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm"></textarea>
                </div>

                <div class="flex">
                    <button type="submit"
                        class="btn btn-fuchsia w-full">
                        Aceptar</button>
                </div>
            </form>
        </div>
    </div>



    {{-- <!-- Mostrar el carrito -->
    <h2>Carrito</h2>
    @if (!empty($carrito))
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Observación</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carrito as $id => $producto)
                    <tr>
                        <td>{{ $producto['nombre'] }}</td>
                        <td>
                            <form action="{{ route('ordenes.actualizarCarrito', $id) }}" method="POST">
                                @csrf
                                <input type="number" name="cantidad" value="{{ $producto['cantidad'] }}"
                                    min="1">
                                <input type="text" name="observacion" value="{{ $producto['observacion'] }}"
                                    placeholder="Observación">
                                <button type="submit" class="btn btn-success">Actualizar</button>
                            </form>
                        </td>
                        <td>{{ $producto['precio'] }}</td>
                        <td>{{ $producto['precio'] * $producto['cantidad'] }}</td>
                        <td>
                            <form action="{{ route('ordenes.eliminarCarrito', $id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Total:
            ${{ array_sum(array_map(function ($producto) {return $producto['precio'] * $producto['cantidad'];}, $carrito)) }}
        </h3>
        <!-- Formulario para guardar la orden -->
        <form action="{{ route('ordenes.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="mesa">Número de Mesa</label>
                <input type="text" name="mesa" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Orden</button>
        </form>
    @else
        <p>No hay productos en el carrito.</p>
    @endif --}}
    </div>



    <script>
        let productoSeleccionado = null;

        // Abrir modal con los datos del producto
        function abrirModal(producto) {
            productoSeleccionado = producto;
            document.getElementById('productoIdInput').value = producto.id;
            document.getElementById('nombreProducto').innerText = producto.nombre;
            document.getElementById('codigoProducto').innerText = 'Código: ' + producto.codigo;
            document.getElementById('modalProducto').style.display = 'flex';
        }

        // Cerrar modal
        function cerrarModal() {            
            document.getElementById('observacion').value = '';
            document.getElementById('cantidad').value = 1;
            document.getElementById('productoIdInput').value = 0;
            document.getElementById('nombreProducto').innerText = '';
            document.getElementById('codigoProducto').innerText = '';
            document.getElementById('modalProducto').style.display = 'none';
        }

        // Agregar producto seleccionado al pedido
        function agregarProducto() {
            const cantidad = document.getElementById('cantidad').value;
            const observacion = document.getElementById('observacion').value;

            // Aquí puedes almacenar el producto en una lista de memoria (en un array)
            const productoEnMemoria = {
                id: productoSeleccionado.id,
                nombre: productoSeleccionado.nombre,
                codigo: productoSeleccionado.codigo,
                categoria_id: productoSeleccionado.categoria_id,
                cantidad: cantidad,
                precio: productoSeleccionado.precio,
                observacion: observacion
            };

            console.log('Producto agregado:', productoEnMemoria);

            cerrarModal();
        }


        function agregarAlCarrito(event) {
            event.preventDefault(); // Evita que la página se recargue

            // Selecciona el formulario correspondiente al producto
            let form = document.querySelector('#formAgregarCarrito');

            // Obtén el token CSRF desde la metaetiqueta
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Crea un objeto FormData para manejar los datos del formulario
            let formData = new FormData(form);

            // Realiza la petición AJAX usando Fetch
            fetch('/admin/ordenes/agregar', {
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
                        cerrarModal();
                        var nPedido = data.carrito;
                        document.getElementById('cart-count').innerHTML = Object.keys(nPedido).length;
                        
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1000
                        });
                        // Aquí puedes actualizar el carrito visualmente sin recargar la página                        
                    } else {
                        alert(data.message || 'Error al agregar el producto');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un problema al agregar el producto al carrito');
                });
        }



        function filtrarProductos(categoriaId) {
            // Si categoriaId es una cadena vacía o no está definido, mostramos todos los productos
            if (categoriaId == 'todos') {
                // Lógica para mostrar todos los productos
                mostrarTodosLosProductos(categoriaId);
            } else {
                // Lógica para filtrar por categoría específica
                filtrarPorCategoria(categoriaId);
            }
        }

        function mostrarTodosLosProductos(categoriaId) {
            const botones = document.querySelectorAll('button');

            // Mostrar ícono de carga en el botón seleccionado
            const loadingIcon = document.getElementById(`loading-${categoriaId}`);
            const buttonText = document.querySelector(`#btn-${categoriaId} .btn-text`);

            // Desmarcar todos los botones y ocultar el ícono de carga
            botones.forEach(boton => {
                boton.classList.remove('selected');
                const icon = boton.querySelector('.loading-icon');
                if (icon) {
                    icon.classList.add('hidden');
                }
            });

            // Mostrar el ícono de carga en el botón actual
            loadingIcon.classList.remove('hidden');

            setTimeout(() => {
                // Filtrar productos por categoría
                const productos = document.querySelectorAll('.producto-card');
                productos.forEach(producto => {
                    producto.style.display = 'block'; // Mostrar todos los productos
                });

                // Ocultar el ícono de carga y marcar el botón seleccionado
                loadingIcon.classList.add('hidden');
                document.getElementById(`btn-${categoriaId}`).classList.add('selected');
            }, 500); // Simular 1 segundo de carga (ajústalo según tu necesidad)
        }


        function filtrarPorCategoria(categoriaId) {
            // Obtener todos los botones y productos
            const productos = document.querySelectorAll('.producto-card');
            const botones = document.querySelectorAll('button');

            // Mostrar ícono de carga en el botón seleccionado
            const loadingIcon = document.getElementById(`loading-${categoriaId}`);
            const buttonText = document.querySelector(`#btn-${categoriaId} .btn-text`);

            // Desmarcar todos los botones y ocultar el ícono de carga
            botones.forEach(boton => {
                boton.classList.remove('selected');
                const icon = boton.querySelector('.loading-icon');
                if (icon) {
                    icon.classList.add('hidden');
                }
            });

            // Mostrar el ícono de carga en el botón actual
            loadingIcon.classList.remove('hidden');

            // Simular un tiempo de carga (puedes quitarlo cuando uses datos reales)
            setTimeout(() => {
                // Filtrar productos por categoría
                productos.forEach(producto => {
                    if (producto.getAttribute('data-categoria-id') === categoriaId) {
                        producto.style.display = 'block';
                    } else {
                        producto.style.display = 'none';
                    }
                });

                // Ocultar el ícono de carga y marcar el botón seleccionado
                loadingIcon.classList.add('hidden');
                document.getElementById(`btn-${categoriaId}`).classList.add('selected');
            }, 500); // Simular 1 segundo de carga (ajústalo según tu necesidad)
        }




        // Función para filtrar productos
        const searchInput = document.getElementById('search-product');
        searchInput.addEventListener('input', function() {
            const filter = searchInput.value.toLowerCase();
            const products = document.querySelectorAll('.producto-card');

            products.forEach(product => {
                const nombre = product.getAttribute('data-nombre').toLowerCase();
                const codigo = product.getAttribute('data-codigo').toLowerCase();
                const categoria = product.getAttribute('data-categoria').toLowerCase();

                // Verifica si el filtro coincide con el nombre, código o categoría
                if (nombre.includes(filter) || codigo.includes(filter) || categoria.includes(filter)) {
                    product.style.display = 'block'; // Muestra el producto
                } else {
                    product.style.display = 'none'; // Oculta el producto
                }
            });
        });



        document.getElementById('increment-button').addEventListener('click', function() {
            let input = document.getElementById('cantidad');
            let max = parseInt(input.getAttribute('data-input-counter-max'), 10);
            let currentValue = parseInt(input.value, 10) || 0;

            if (currentValue < max) {
                input.value = currentValue + 1;
            }
        });

        document.getElementById('decrement-button').addEventListener('click', function() {
            let input = document.getElementById('cantidad');
            let min = parseInt(input.getAttribute('data-input-counter-min'), 10);
            let currentValue = parseInt(input.value, 10) || 0;

            if (currentValue > min) {
                input.value = currentValue - 1;
            }
        });
    </script>

</x-layouts.app>
