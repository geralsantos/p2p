<template id="liga-administrar">
    <div class="container-fluid">
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
                        <h3>Administrar Liga</h3>
                    </div>
                    <div class="row" v-if="loader">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="loader"></div>
                            <br>
                            <div class="text-loader"><span class="blanco">Procesando...</span></div>
                            <br>
                            <div class="text-loader"><span class="blanco"> Este proceso demorará un poco, por favor no recargar la página hasta que se termine el proceso.</span></div>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 formulario">
                            <form>

                                <div class="row">
                                    <div class="form-group col-lg-12 blanco">
                                        <label style="" class="">Nombre Liga</label>
                                        <input class="form-control" type="text" v-model="nombre_liga"  :disabled="readonly_on">
                                    </div>

                                    <div class="form-group col-lg-12 blanco">
                                        <label>Número de Participantes </label>
                                        <select style="border-radius:15px;" class="form-control" v-model="nro_participantes" :disabled="readonly_on">
                                            <option value="8">8</option>
                                            <option value="16">16</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-12 blanco">
                                        <label style="" class="">N° de Participantes Registrados</label>
                                        <input class="form-control" type="text" v-model="participantes_registrados" readonly>
                                    </div>
                                </div>
                                <br>

                                <div class="row" v-if="boton_actualizar">
                                    <div class="text-center">
                                        <button class="btn btn-success" v-on:click="actualizar_datos()">Actualizar Datos</button>

                                    </div>

                                    <div class="form-group col-lg-12 text-center blanco">
                                       <span>Solo puedes modificar tu liga antes del kickoff</span>
                                    </div>
                                </div>
                                </form>
                                <div class="row"  v-if="datos_liga.temporada_estado==2 ||  (datos_liga.temporada_estado==1 && datos_liga.temporada_partidos_generados==0)">
                                    <div class="text-center">
                                        <button class="btn btn-success" v-on:click="generar_partidos_liga()">Generar Partidos Liga</button>
                                        <br>
                                        <br>
                                        <span class="blanco">Este proceso demorará un poco, por favor no recargar la página hasta que se termine el proceso.</span>
                                    </div>
                                </div>

                                <div class="row"  v-if="datos_liga.temporada_estado==3">
                                    <div class="text-center custom-login blanco text-center">
                                    <button class="btn btn-success" v-on:click="iniciar_periodo_subastas()">Iniciar Período de Subastas</button>
                                    </div>
                                </div>
                                <div class="row"  v-if="datos_liga.temporada_estado==5">
                                    <div class="text-center custom-login blanco text-center">
                                    <button class="btn btn-success" v-on:click="finalizar_temporada()">Finalizar Temporada</button>
                                    </div>
                                </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</template>