<template id="liga-partidos">
    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>Lista de partidos y resultados</h3>
                        <h5>Liga: {{datos_liga.nombre_liga}}</h5>
                        <h5>Mi equipo: {{datos_liga.nombre}}</h5>
                    </div>
                    <table>
                        <tr>
                            <th>#</th>
                            <th>Equipo Local</th>
                            <th>Goles</th>
                            <th>Equipo Visitante</th>
                            <th>Goles</th>
                            <th>Estado</th>
                        </tr>
                        <tr v-for="partido, index in partidos">
                            <td>{{index+1}}</td>
                            <td>{{partido.nombre_local}}</td>
                            <td>{{(partido.goles_casa==undefined)?'0':partido.goles_casa}}</td>
                            <td>{{partido.nombre_visitante}}</td>
                            <td>{{(partido.goles_visitante==undefined)?'0':partido.goles_visitante}}</td>
                            <td :class="(partido.registrado==1)?'verde':'rojo'"> {{(partido.registrado==1)?'Jugado':'Pendiente'}}</td>
                        </tr>
                    </table>

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
                        <h3>Lista de partidos y resultados</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>