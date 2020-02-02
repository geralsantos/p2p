<template id="liga-resultados">
    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3> Registro de Resultados de partidos de liga</h3>
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
                                <div class="form-group col-lg-9 text-center">
                                    <label>Selecciona Equipo Local</label>
                                    <select class="form-control" v-model="equipo_casa" @change="cargar_equipos_visitante()" required>
                                    <option v-for="casa in casas" :value="casa.id">{{casa.nombre}}</option>

                                    </select>
                                </div>
                                <div class="form-group col-lg-3 text-center">
                                    <label>Goles</label>
                                    <input class="form-control" type="number"  min="0" v-model="goles_casa">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-9 text-center">
                                    <label>Selecciona Equipo Visitante</label>
                                    <select class="form-control" v-model="partido_liga" required>
                                    <option v-for="visitante in visitantes" :value="visitante">{{visitante.nombre_visitante}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 text-center">
                                    <label>Goles</label>
                                    <input class="form-control" type="number"  min="0" v-model="goles_visitante">
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
                        <h3>Registro de Resultados de partidos de liga</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>