<template id="liga-historial" >
    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3> Historial de Ligas</h3>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center formulario">
                                <label >Selecciona la liga</label>
                                <select style="border-radius:15px;" v-model="liga" class="form-control" @change="cargar_temporadas()">
                                    <option v-for="liga in ligas" :value="liga.id">{{liga.nombre}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center formulario">
                                <label>Selecciona la temporada</label>
                                <select style="border-radius:15px;" class="form-control" v-model="temporada" @change="cargar_tabla()">
                                    <option v-for="temporada in temporadas" :value="temporada"  >Temporada {{temporada.numero}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row" v-if="ver_tabla">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="product-status-wrap tablas">
                                <table>
                                    <thead>
                                        <th>#</th>
                                        <th>EQUIPOS</th>
                                        <th>PTOS</th>
                                        <th>PJ</th>
                                        <th>PG</th>
                                        <th>PE</th>
                                        <th>PP</th>
                                        <th>GF</th>
                                        <th>GC</th>
                                    </thead>
                                    <tr v-for="equipo, index in equipos">
                                        <td>{{index+1}}</td>
                                        <td>{{equipo.nombre}}</td>
                                        <td>{{equipo.puntos}}</td>
                                        <td>{{equipo.partidos_jugados}}</td>
                                        <td>{{equipo.partidos_ganados}}</td>
                                        <td>{{equipo.partidos_empatados}}</td>
                                        <td>{{equipo.partidos_perdidos}}</td>
                                        <td>{{(equipo.goles_anotados==undefined)?0:equipo.goles_anotados}}</td>
                                        <td>{{(equipo.goles_recibidos==undefined)?0:equipo.goles_recibidos}}</td>
                                    </tr>
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
                        <h3> Historial de Ligas</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>