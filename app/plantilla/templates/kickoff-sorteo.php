<template id="kickoff-sorteo">
    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                    <h3>Lista de turnos de kickoff</h3>
                    </div>
                    <table>
                        <thead>
                            <tr class="text-center">
                                <th>Orden</th>
                                <th>Equipo</th>
                                <th>Fecha Kickoff</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="equipo in equipos">
                                <td>{{equipo.turno_kickoff}}</td>
                                <td>{{equipo.nombre}}</td>
                                <td>{{equipo.fecha_kickoff}}</td>
                                <td v-if="equipo.registro_plantilla==0">Pendiente</td>
                                <td v-else-if="equipo.registro_plantilla==1">En Proceso</td>
                                <td v-else-if="equipo.registro_plantilla==2">Completado</td>
                            </tr>
                        </tbody>
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
                        <h3  v-if="!mostrar">PROCESO DE KICKOFF</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>