<template id="liga-tabla-clasificacion">
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
                <div class="product-status-wrap listado-equipos">
                    <h4>Clasificación : {{liga.nombre}}</h4>
                    <table>
                        <tr>
                            <th>#</th>
                            <th>EQUIPOS</th>
                            <th>PTOS</th>
                            <th>PJ</th>
                            <th>PG</th>
                            <th>PE</th>
                            <th>PP</th>
                            <th>GF</th>
                            <th>GC</th>
                        </tr>
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
</template>