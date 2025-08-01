@extends('layout')

@section('contenido')
    <section class="py-5">
        <div class="container w-50">
            <h2 class="text-center text-blue mb-4">Finalizar Compra</h2>

            <form action="index.php?accion=procesarPedido" method="POST">

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" required>
                </div>

                <div class="mb-3">
                    <label for="numero_tarjeta" class="form-label">Número de tarjeta</label>
                    <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" required>
                </div>

                <div class="mb-3">
                    <label for="caducidad" class="form-label">Fecha de caducidad (MM/AA)</label>
                    <input type="text" class="form-control" id="caducidad" name="caducidad" placeholder="12/25" required>
                </div>

                <div class="mb-3">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" class="form-control" id="cvv" name="cvv" required>
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección de envío</label>
                    <input type="text" class="form-control" name="direccion" id="direccion" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Confirmar pago
                </button>
            </form>
        </div>
    </section>

   <script>
    document.addEventListener('DOMContentLoaded', function () {
        new Cleave('#numero_tarjeta', {
            blocks: [4, 4, 4, 4],
            numericOnly: true,
            delimiter: ' '
        });

        new Cleave('#caducidad', {
            date: true,
            datePattern: ['m', 'y']
        });

        new Cleave('#cvv', {
            blocks: [3],
            numericOnly: true
        });

        document.querySelector('form').addEventListener('submit', function (e) {
            const nombre = document.getElementById('nombre').value.trim();
            const numeroTarjeta = document.getElementById('numero_tarjeta').value.replace(/\s+/g, '');
            const caducidad = document.getElementById('caducidad').value;
            const cvv = document.getElementById('cvv').value;
            const direccion = document.getElementById('direccion').value.trim();

            if (!/^[a-zA-ZÁÉÍÓÚÜÑáéíóúüñ\s]{3,}$/.test(nombre)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Nombre inválido',
                    text: 'Introduce un nombre válido en la tarjeta.',
                    confirmButtonColor: '#0085B4'
                });
                return;
            }

            if (!/^\d{16}$/.test(numeroTarjeta)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Número inválido',
                    text: 'El número de tarjeta debe tener 16 dígitos.',
                    confirmButtonColor: '#0085B4'
                });
                return;
            }

            const [mes, anio] = caducidad.split('/');
            const hoy = new Date();
            const caducidadFecha = new Date(`20${anio}`, mes - 1);

            if (!mes || !anio || caducidadFecha < hoy || mes < 1 || mes > 12) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Fecha de caducidad inválida',
                    text: 'Introduce una fecha válida y no caducada.',
                    confirmButtonColor: '#0085B4'
                });
                return;
            }

            if (!/^\d{3}$/.test(cvv)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'CVV inválido',
                    text: 'El CVV debe tener 3 cifras numéricas.',
                    confirmButtonColor: '#0085B4'
                });
                return;
            }

            if (direccion.length < 5) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Dirección inválida',
                    text: 'Introduce una dirección de envío válida.',
                    confirmButtonColor: '#0085B4'
                });
                return;
            }
        });
    });
</script>


    {{-- SweetAlert para error de pago --}}
    @if (isset($_GET['pedido']) && $_GET['pedido'] === 'error')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al procesar el pedido',
                    text: 'Por favor, revisa tus datos e inténtalo de nuevo.',
                    confirmButtonColor: '#0085B4'
                });
            });
        </script>
    @endif
@endsection

