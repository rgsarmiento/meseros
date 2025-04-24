<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Mesa;
use App\Models\Orden;
use App\Models\OrdenDetalle;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrdenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::with('categoria')->get(); // Cargar todos los productos disponibles
        $carrito = session()->get('carrito', []); // Obtener el carrito de la sesión
        $categorias = Categoria::all(); // Cargar las categorías

        return view('ordenes.create', compact('productos', 'categorias', 'carrito'));
    }


    public function confirmar()
    {
        //$productos = Producto::with('categoria')->get(); // Cargar todos los productos disponibles
        $carrito = session()->get('carrito', []); // Obtener el carrito de la sesión
        $mesas = Mesa::all(); // Cargar las mesas

        return view('ordenes.confirmar', compact('carrito', 'mesas'));
    }


    /**
     * Agrega un producto al carrito.
     */
    public function agregarAlCarrito(Request $request)
    {
        // Buscar el producto por su ID
        $producto = Producto::find($request->productoId);

        // Validar los datos del formulario
        $request->validate([
            'cantidad' => 'required|integer|min:1',
            'observacion' => 'nullable|string',
        ]);

        // Obtener el carrito de la sesión, o un array vacío si no existe
        $carrito = session()->get('carrito', []);

        // Crear una bandera para saber si hemos encontrado un producto igual (sin observación)
        $productoAgregado = false;

        // Si no hay observación, buscamos si ya existe el mismo producto en el carrito
        if (empty($observacion)) {
            foreach ($carrito as $indice => $item) {
                if ($item['producto_id'] === $request->productoId && empty($item['observacion'])) {
                    // Si encontramos el mismo producto sin observación, aumentamos la cantidad
                    $carrito[$indice]['cantidad'] += $request->cantidad;
                    $productoAgregado = true;
                    break;
                }
            }
        }


        // Si no se ha encontrado un producto igual o tiene observación, lo añadimos como una nueva entrada
        if (!$productoAgregado) {
            $nuevoIndice = uniqid(); // Crear un índice único para el nuevo producto

            $carrito[$nuevoIndice] = [
                'producto_id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $request->cantidad,
                'observacion' => $request->observacion, // Guardar la observación, puede estar vacía
            ];
        }

        // Guardar el carrito actualizado en la sesión
        session()->put('carrito', $carrito);

        // Enviar una respuesta JSON en lugar de redirigir
        return response()->json([
            'success' => true,
            'message' => 'Producto agregado',
            'carrito' => $carrito
        ]);
    }

    /**
     * Elimina un producto del carrito.
     */
    public function eliminarDelCarrito($indice)
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$indice])) {
            unset($carrito[$indice]);
        }

        session()->put('carrito', $carrito);

        return redirect()->back()->with('success', 'Producto eliminado del carrito');
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function actualizarCarrito(Request $request, $indice)
    {
        // Obtener el carrito de la sesión
        $carrito = session()->get('carrito', []);

        // Verificar si el producto con ese índice existe en el carrito
        if (isset($carrito[$indice])) {
            // Validar los datos recibidos
            $request->validate([
                'cantidad' => 'required|integer|min:1', // Asegurarse de que la cantidad sea válida
                'observacion' => 'nullable|string|max:255', // La observación puede ser nula o una cadena de texto
            ]);

            // Actualizar los detalles del producto en el carrito
            $carrito[$indice]['cantidad'] = $request->input('cantidad');
            $carrito[$indice]['observacion'] = $request->input('observacion', ''); // Si no hay observación, dejarla vacía

            // Guardar el carrito actualizado en la sesión
            session()->put('carrito', $carrito);

            // Redirigir de vuelta con un mensaje de éxito
            return redirect()->back()->with('success', 'Producto modificado correctamente en el carrito');
        } else {
            // Si el producto no existe, redirigir con un mensaje de error
            return redirect()->back()->with('error', 'Producto no encontrado en el carrito');
        }
    }




    /**
     * Store a newly created resource in storage.
     */
   
public function store(Request $request)
{
    try {
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return response()->json(['error' => 'El carrito está vacío'], 400);
        }

        // Validar los datos de la orden
        $request->validate([
            'mesa' => 'required|string',
        ]);

        // Crear la orden
        $orden = Orden::create([
            'mesa_id' => $request->input('mesa'),
            'usuario_id' => auth()->user()->id,
            'total' => array_sum(array_map(function ($producto) {
                return $producto['precio'] * $producto['cantidad'];
            }, $carrito)),
        ]);

        // Crear los detalles de la orden
        foreach ($carrito as $key => $producto) {
            // Usar el producto_id desde los datos del carrito
            $productoId = $producto['producto_id'];

            // Verificar que el producto existe en la base de datos antes de intentar acceder a la categoría
            $productoDB = Producto::find($productoId);

            if ($productoDB) {
                OrdenDetalle::create([
                    'orden_id' => $orden->id,
                    'codigo_producto' => $productoDB->codigo, // Usar el producto_id correcto
                    'nombre_producto' => $producto['nombre'],
                    'categoria_id' => $productoDB->categoria_id, // Acceder a la categoría desde la base de datos
                    'precio' => $producto['precio'],
                    'cantidad' => $producto['cantidad'],
                    'observacion' => $producto['observacion'],
                ]);
            } else {
                // Log para productos no encontrados
                Log::warning("Producto no encontrado: {$productoId}");
            }
        }

        $this->actualizarEstadoMesa($request->input('mesa'), 'ocupada');

        // Vaciar el carrito
        session()->forget('carrito');

        return response()->json([
            'success' => true,
            'message' => 'Orden guardada con éxito',
            'orden_id' => $orden->id,
            'redirect' => route('ordenes.create') // Enviar la URL de redirección
        ], 200);
    } catch (\Exception $e) {
        // Log del error detallado
        Log::error('Error en el proceso de la orden: ' . $e->getMessage(), ['exception' => $e]);

        return response()->json(['error' => 'Error al validar los datos de la orden: ' . $e->getMessage()], 400);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(Orden $orden)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orden $orden)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orden $orden)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orden $orden)
    {
        //
    }


    public function actualizarEstadoMesa($mesaId, $nuevoEstado)
    {
        // Buscar la mesa por su ID
        $mesa = Mesa::find($mesaId);

        if ($mesa) {
            // Actualizar el estado de la mesa
            $mesa->estado = $nuevoEstado;

            // Actualizar la hora en que se cambió el estado
            $mesa->hora_estado = now(); // Establecer la hora actual

            // Guardar los cambios
            $mesa->save();
        }
    }

    public function obtenerOrdenesPendientes()
    {
        // Obtener las órdenes con estado 'pendiente' o 'actualizar'
        $ordenes = Orden::whereIn('estado', ['pendiente', 'actualizar'])
            ->with('detalles') // Relación con los detalles
            ->get();
        
        // Retornar las órdenes como JSON
        return response()->json($ordenes);
    }


    public function actualizarEstadoPorLlave($llave, Request $request)
    {
        // Validamos que el estado sea válido
        $request->validate([
            'estado' => 'required|in:pendiente,actualizar,preparando,terminado,procesado',
        ]);

        // Buscar la orden por la llave
        $orden = Orden::where('llave', $llave)->first();

        if (!$orden) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }

        // Actualizar el estado de la orden
        $orden->estado = $request->estado;
        $orden->save();

        return response()->json(['message' => 'Estado actualizado correctamente', 'orden' => $orden]);
    }


}
