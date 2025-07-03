@extends('layout')

@section('contenido')
<div class="container py-5">
  @if ($exito)
    <div class="alert alert-success text-center">
      <h4>¡Pago confirmado con éxito!</h4>
      <p>Gracias por tu compra. Pronto recibirás tu pedido.</p>
    </div>
  @else
    <div class="alert alert-danger text-center">
      <h4>Error en el pago</h4>
      <p>Por favor, revisa los datos introducidos.</p>
    </div>
  @endif

  <div class="text-center mt-4">
    <a href="index.php?accion=inicio" class="btn btn-outline-primary">Volver al inicio</a>
  </div>
</div>
@endsection

