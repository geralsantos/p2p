<template id="equipos-estadisticas">
    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>Estadísticas Historial</h3>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center formulario">
                                <label>Selecciona un equipo</label>
                                <select style="border-radius:15px;" v-model="equipo" class="form-control" @change="cargar_temporadas()">
                                    <option v-for="equipo in equipos" :value="equipo">{{equipo.nombre}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center formulario">
                                <label>Selecciona la temporada</label>
                                <select class="form-control" v-model="temporada" @change="cargar_equipo()">
                                    <option v-for="temporada in temporadas" :value="temporada"> Temporada {{temporada.numero}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>

                    <div class="row" v-if="ver_grafico_1">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="product-status-wrap tablas">
                                <div id="grafico-estadisticas-equipos" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="product-status-wrap tablas text-center">
                                <h4>{{submensaje}}</h4>
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
                    <h3>Estadísticas Historial</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>