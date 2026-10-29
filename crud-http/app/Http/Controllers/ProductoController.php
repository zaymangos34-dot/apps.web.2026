<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoController extends Controller
{
   
    private function semilla(): array
    {
        return [
            1 => ['id' => 1, 'nombre' => 'Laptop', 'categoria' => 'Tecnología', 'precio' => 15999.00],
            2 => ['id' => 2, 'nombre' => 'Mouse', 'categoria' => 'Tecnología', 'precio' => 299.00],
            3 => ['id' => 3, 'nombre' => 'Escritorio', 'categoria' => 'Muebles', 'precio' => 2499.00],
            4 => ['id' => 4, 'nombre' => 'Silla', 'categoria' => 'Muebles', 'precio' => 1899.00],
        ];
    }

    private function todos(Request $request): array
    {
        return $request->session()->get('productos', $this->semilla());
    }

    private function guardar(Request $request, array $productos): void
    {
        $request->session()->put('productos', $productos);
    }

   
    public function index(Request $request)
    {
        $productos = collect($this->todos($request));

        $buscar = $request->query('buscar');
        $categoria = $request->query('categoria');

        if ($buscar) {
            $productos = $productos->filter(
                fn ($p) => str_contains(mb_strtolower($p['nombre']), mb_strtolower($buscar))
            );
        }

        if ($categoria) {
            $productos = $productos->where('categoria', $categoria);
        }

        return view('productos.index', [
            'productos' => $productos,
            'categorias' => collect($this->todos($request))->pluck('categoria')->unique(),
            'buscar' => $buscar,
            'categoria' => $categoria,
        ]);
    }

    public function create()
    {
        return view('productos.create');
    }

   
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:100'],
            'categoria' => ['required', 'string', 'max:50'],
            'precio' => ['required', 'numeric', 'min:0'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'precio.numeric' => 'El precio debe ser un número.',
        ]);

        $productos = $this->todos($request);
        $id = (empty($productos) ? 0 : max(array_keys($productos))) + 1;
        $productos[$id] = ['id' => $id] + $datos;

        $this->guardar($request, $productos);

        return redirect()->route('productos.index')
            ->with('ok', 'Producto creado correctamente.');
    }

    public function edit(Request $request, int $id)
    {
        $productos = $this->todos($request);
        abort_unless(isset($productos[$id]), 404);

        return view('productos.edit', ['producto' => $productos[$id]]);
    }

    public function update(Request $request, int $id)
    {
        $productos = $this->todos($request);
        abort_unless(isset($productos[$id]), 404);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:100'],
            'categoria' => ['required', 'string', 'max:50'],
            'precio' => ['required', 'numeric', 'min:0'],
        ]);

        $productos[$id] = ['id' => $id] + $datos;
        $this->guardar($request, $productos);

        return redirect()->route('productos.index')
            ->with('ok', 'Producto actualizado correctamente.');
    }

    
    public function destroy(Request $request, int $id)
    {
        $productos = $this->todos($request);
        abort_unless(isset($productos[$id]), 404);

        unset($productos[$id]);
        $this->guardar($request, $productos);

        return redirect()->route('productos.index')
            ->with('ok', 'Producto eliminado.');
    }
}
