<template id="kickoff-registro">
    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>Generar Sorteo Kickoff</h3>
                    </div>
                    <div class="row" v-if="loader">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="loader"></div>
                            <br>
                            <div class="text-loader"><span class="blanco">Procesando...</span></div>
                            <br>
                            <div class="text-loader"><span class="blanco"> Por favor, no recargar la página hasta que se termine el proceso.</span></div>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 formulario">
                            <div class="row">
                                <div class="form-group col-lg-12 blanco">
                                    <label style="" class="">Fecha de inicio de kick-off</label>
                                    <input class="form-control" type="date" placeholder="DD-MM-AAAA" v-model="kickoff_fecha" required>

                                </div>
                                <div class="form-group col-lg-12 blanco">
                                    <label style="" class="">Hora de inicio de kick-off</label>
                                    <input class="form-control" type="time"  v-model="kickoff_hora" required>
                                </div>
                                <div class="form-group col-lg-12 text-center">
                                    <button class="btn btn-success" v-on:click="generar_kickoff">Generar kickoff</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-12 text-center blanco">
                                    <span>El sorteo determinará el orden en que los equipos de tu liga registrarán sus plantilla.</span>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid"  v-else>
        <div class="row" v-if="mascara">
            <div class="product-status-wrap tablas">
                <div class="tabla-cabecera">
                    <div class="text-loader"><span class="blanco">CARGANDO...</span></div>
                    <div class="loader"></div>
                </div>
            </div>
        </div>
        <div class="row" v-else>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>GENERAR SORTEO DE KICKOFF</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>