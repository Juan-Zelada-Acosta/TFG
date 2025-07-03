@extends('layout')

@section('contenido')
    <section class="py-5">
        <div class="container">
            <h2 class="mb-4 text-center text-blue">Editar Producto</h2>

            <form action="index.php?accion=guardarEdicion&id={{ $producto['id'] }}" method="POST" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $producto['nombre'] }}" required>
                </div>

                <div class="col-md-6">
                    <label for="precio" class="form-label">Precio</label>
                    <input type="number" name="precio" id="precio" class="form-control" step="0.01" value="{{ $producto['precio'] }}" required>
                </div>

                <div class="col-md-6">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select name="categoria" id="categoria" class="form-select" required>
                        <option value="camisetas" {{ $producto['categoria'] == 'camisetas' ? 'selected' : '' }}>Camisetas</option>
                        <option value="sudaderas" {{ $producto['categoria'] == 'sudaderas' ? 'selected' : '' }}>Sudaderas</option>
                        <option value="pantalones" {{ $producto['categoria'] == 'pantalones' ? 'selected' : '' }}>Pantalones</option>
                        <option value="calcetines" {{ $producto['categoria'] == 'calcetines' ? 'selected' : '' }}>Calcetines</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="coleccion" class="form-label">Colección</label>
                    <select name="coleccion" id="coleccion" class="form-select" required>
                        <option value="nueva" {{ $producto['coleccion'] == 'nueva' ? 'selected' : '' }}>Nueva</option>
                        <option value="rebajas" {{ $producto['coleccion'] == 'rebajas' ? 'selected' : '' }}>Rebajas</option>
                        <option value="estandar" {{ $producto['coleccion'] == 'estandar' ? 'selected' : '' }}>Estándar</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required>{{ $producto['descripcion'] }}</textarea>
                </div>

                <div class="col-md-6">
                    <label for="imagen" class="form-label">Imagen nueva (opcional)</label>
                    <input type="file" name="imagen" id="imagen" class="form-control" accept="image/*">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Imagen actual</label><br>
                    <img src="{{ $producto['imagen'] }}" alt="Imagen actual" class="img-fluid" style="max-height: 120px;">
                </div>

                {{-- Stock por talla --}}
                @foreach (['S', 'M', 'L', 'XL'] as $talla)
                    <div class="col-md-3">
                        <label for="stock_{{ strtolower($talla) }}" class="form-label">Stock {{ $talla }}</label>
                        <input type="number" name="stock_{{ strtolower($talla) }}" class="form-control"
                            value="{{ $producto['stock_' . strtolower($talla)] ?? 0 }}" min="0">
                    </div>
                @endforeach

                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5">Guardar cambios</button>
                </div>
            </form>

            @if (isset($_GET['success']) && $_GET['success'] === '1')
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Producto actualizado',
                        text: 'Los cambios se han guardado correctamente.',
                        confirmButtonColor: '#0085B4'
                    });
                </script>
            @endif

            @if (isset($_GET['error']))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        let msg = '';
                        switch ("{{ $_GET['error'] }}") {
                            case 'campos':
                                msg = 'Debes completar todos los campos obligatorios.';
                                break;
                            case 'tipo':
                                msg = 'El tipo de imagen no es válido.';
                                break;
                            case 'tamano':
                                msg = 'La imagen no puede superar los 2MB.';
                                break;
                            case 'subida':
                                msg = 'Error al subir la imagen.';
                                break;
                            default:
                                msg = 'Ha ocurrido un error inesperado.';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error al guardar',
                            text: msg,
                            confirmButtonColor: '#0085B4'
                        });
                    });
                </script>
            @endif
        </div>
    </section>
@endsection

