<template id="seleccionar-plantilla">

    <div class="container-fluid" v-if="formulario_kickoff==1">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>PROCESO DE KICKOFF</h3>
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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <label style="color:white;" class="">Selecciona tu equipo base</label>
                            <select style="border-radius:15px;" class="form-control" v-model="team" @click="cargar_equipo()" >
                                <option v-for="team in teams" :value="team.id">{{team.nombre}}</option>
                            </select>
                        </div>
                 
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <form class="form-horizóntal" v-on:submit.prevent="guardar">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="product-status-wrap listado-equipos">
                                        <h4>Listado de Jugadores</h4>
                                        <p class="blanco">Selecciona 15 Jugadores</p>
                                        <div class="add-product">

                                        </div>

                                        <table style="border:">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Posición</th>
                                                <th>Altura</th>
                                                <th>Overall</th>
                                                <th>PES DB 2019</th>
                                                <th>Seleccionar</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <tr v-for="jugador in jugadores">

                                                <td>{{jugador.nombre}}</td>
                                                <td>{{jugador.posicion}}</td>
                                                <td>{{jugador.altura}}</td>
                                                <td>{{jugador.overall}}</td>
                                                <td><a href="http://pesdb.net/pes2019/" target="_blank" class="pd-setting-ed">Ver</a></td>
                                                <td>
                                                    <div class="group">
                                                        <input class="toggle toggle-switch" :id="jugador.id"  :value="jugador.id" type="checkbox" @change="verificar(jugador.id)" v-model="jugadores_array" />
                                                        <label class="toggle-btn" :for="jugador.id"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">

                                    <button type="submit" class="btn btn-success">Guardar Cambios</button>

                                </div>
                            </div>
                        </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid"  v-else-if="formulario_kickoff==2">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>PROCESO DE KICKOFF</h3>
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
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 formulario">
                            <form class="" v-on:submit.prevent="guardar2">
                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label>Selecciona tu jugador estrella</label>
                                        <select class="form-control" v-model="jugador_estrella" v-if="estrellas!=''" required>
                                        <option v-for="estrella in estrellas" :value="estrella.id">{{estrella.nombre}}</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label>Selecciona tu primer Refuerzo</label>
                                        <select class="form-control" v-model="refuerzo_1" v-if="refuerzos!=''" required>
                                        <option v-for="refuerzo1 in refuerzos" :value="refuerzo1.id">{{refuerzo1.nombre}}</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label>Selecciona tu segundo Refuerzo</label>
                                        <select class="form-control" v-model="refuerzo_2" v-if="refuerzos!=''" required>
                                        <option v-for="refuerzo2 in refuerzos" :value="refuerzo2.id">{{refuerzo2.nombre}}</option>

                                        </select>
                                    </div>

                                </div>
                                <div class="text-center">
                                    <button class="btn btn-success " type="submit">Guardar</button>
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
                        <h3>PROCESO DE KICKOFF</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>