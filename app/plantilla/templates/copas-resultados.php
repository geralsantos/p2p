<template id="copa-resultados">
    <div class="container-fluid" v-if="mostrar">
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
                        <h3> REGISTRO DE RESULTADOS DE PARTIDOS DE COPA</h3>
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
                        <form v-on:submit.prevent="guardar">
                            <div class="row">
                                <div class="form-group col-lg-12 text-center">
                                    <h3>Liga: {{datos_liga.nombre_liga}}</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-12 text-center">
                                    <label>Selecciona el partido de copa</label>
                                   <select class="form-control" v-model="partido">
                                        <option v-for="partido in partidos" :value="partido" >{{partido.nombre_local}} - {{partido.nombre_visitante}}   ( {{partido.etapa}} ) </option>
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-9 text-center">
                                    <label>Ingresa el número de goles del equipo local</label>
                                </div>
                                <div class="form-group col-lg-3 text-center">
                                    <input class="form-control" v-model="goles_local" type="number" min="0" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-9 text-center">
                                <label>Ingresa el número de goles del equipo visitante</label>

                                </div>
                                <div class="form-group col-lg-3 text-center">
                                <input class="form-control" v-model="goles_visitante" type="number" min="0" required>
                                </div>
                            </div>


                            <div class="text-center">
                                <button class="btn btn-success btn-md" type="submit">Guardar</button>
                            </div>
                        </form>
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
                        <h3>REGISTRO DE RESULTADOS DE PARTIDOS DE COPA</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>