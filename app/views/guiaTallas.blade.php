@extends('layout')

@section('titulo', 'Guía de Tallas')

@section('contenido')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <h2 class="text-center mb-4 text-blue">Guía de Tallas</h2>

            <div class="accordion" id="accordionTallas">
                {{-- Camisetas y Sudaderas --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingCamisetas">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCamisetas" aria-expanded="true" aria-controls="collapseCamisetas">
                            Camisetas y Sudaderas
                        </button>
                    </h2>
                    <div id="collapseCamisetas" class="accordion-collapse collapse show" aria-labelledby="headingCamisetas">
                        <div class="accordion-body">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Talla</th>
                                        <th>Pecho (cm)</th>
                                        <th>Largo (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>S</td><td>90-95</td><td>65</td></tr>
                                    <tr><td>M</td><td>96-100</td><td>68</td></tr>
                                    <tr><td>L</td><td>101-106</td><td>71</td></tr>
                                    <tr><td>XL</td><td>107-112</td><td>74</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Pantalones --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingPantalones">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePantalones" aria-expanded="false" aria-controls="collapsePantalones">
                            Pantalones
                        </button>
                    </h2>
                    <div id="collapsePantalones" class="accordion-collapse collapse" aria-labelledby="headingPantalones">
                        <div class="accordion-body">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Talla</th>
                                        <th>Cintura (cm)</th>
                                        <th>Largo (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>S</td><td>70-75</td><td>98</td></tr>
                                    <tr><td>M</td><td>76-82</td><td>100</td></tr>
                                    <tr><td>L</td><td>83-88</td><td>102</td></tr>
                                    <tr><td>XL</td><td>89-95</td><td>104</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Calcetines --}}
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingCalcetines">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCalcetines" aria-expanded="false" aria-controls="collapseCalcetines">
                            Calcetines
                        </button>
                    </h2>
                    <div id="collapseCalcetines" class="accordion-collapse collapse" aria-labelledby="headingCalcetines">
                        <div class="accordion-body">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Talla</th>
                                        <th>Equivalencia (EU)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>S</td><td>36-38</td></tr>
                                    <tr><td>M</td><td>39-41</td></tr>
                                    <tr><td>L</td><td>42-44</td></tr>
                                    <tr><td>XL</td><td>45-47</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
