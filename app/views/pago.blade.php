@extends('layout')

@section('titulo', 'Finalizar Compra')

@section('contenido')
<div class="container py-5">
    <h2 class="text-center mb-4 text-blue"><i class="fa fa-credit-card me-2"></i>Finalizar compra</h2>

    <form method="POST" onsubmit="event.preventDefault(); mostrarConfirmacion();">
        <div class="mb-3">
            <label class="form-label">Nombre completo</label>
            <input type="text" class="form-control" placeholder="Tu nombre y apellidos" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección de envío</label>
            <input type="text" class="form-control" placeholder="Calle, número, piso, ciudad, código postal..." required>
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono de contacto</label>
            <input type="tel" class="form-control" placeholder="Ej: 600123456" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" value="{{ $_SESSION['usuario']['email'] }}" required>
        </div>

        <hr class="my-4">

        <div class="mb-3">
            <label class="form-label">Número de tarjeta</label>
            <input type="text" class="form-control" id="tarjeta" placeholder="•••• •••• •••• ••••" maxlength="19" required>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Fecha caducidad</label>
                <input type="text" class="form-control" id="caducidad" placeholder="MM/AA" maxlength="5" required>
            </div>
            <div class="col">
                <label class="form-label">CVV</label>
                <input type="text" class="form-control" id="cvv" placeholder="123" maxlength="3" required>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-4">Confirmar pedido</button>
    </form>
</div>

<script>
    function mostrarConfirmacion() {
        Swal.fire({
            title: '¡Pedido realizado!',
            text: 'Tu compra ha sido procesada con éxito.',
            icon: 'success',
            confirmButtonColor: '#0085B4'
        }).then(() => {
            window.location.href = 'index.php?accion=confirmarPedido';
        });
    }
</script>
<script>
    document.getElementById('tarjeta').addEventListener('input', function () {
        let valor = this.value.replace(/\D/g, ''); 
        valor = valor.substring(0, 16); 
        this.value = valor.replace(/(.{4})/g, '$1 ').trim(); 
    });

    document.getElementById('caducidad').addEventListener('input', function () {
        let valor = this.value.replace(/\D/g, ''); 
        if (valor.length > 4)
            valor = valor.substring(0, 4);
        if (valor.length > 2) {
            valor = valor.substring(0, 2) + '/' + valor.substring(2);
        }
        this.value = valor;
    });

    document.getElementById('cvv').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').substring(0, 3);
    });
</script>
@endsection
