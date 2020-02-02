<template id="liga-plantilla-otros-equipos">
    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>Plantilla Otros equipos</h3>

                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <div class="product-status-wrap col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center formulario">
                                <label >Selecciona equipo</label>
                                <select v-model="equipo" class="form-control" @change="cargar_equipo()">
                                    <option v-for="equipo in equipos" :value="equipo">{{equipo.nombre}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="product-status-wrap tablas">
                                <h4>Listado de Jugadores </h4>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Nombre</th>
                                            <th>Posición</th>
                                            <th>Altura</th>
                                            <th>Overall</th>
                                            <th>PES DB 2019</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <tr v-for="jugador, index in plantilla">
                                            <td>{{index+1}}</td>
                                            <td>{{jugador.nombre}}</td>
                                            <td>{{jugador.posicion}}</td>
                                            <td>{{jugador.altura}}</td>
                                            <td>{{jugador.overall}}</td>
                                            <td><a href="http://pesdb.net/pes2019/" target="_blank" class="pd-setting-ed">Ver</a></td>
                                        </tr>

                                    </tbody>
                                </table>
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
                        <h3>Plantilla Otros equipos</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>