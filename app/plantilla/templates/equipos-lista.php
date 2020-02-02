<template id="equipos-lista">
    <div class="container-fluid" v-if="mostrar">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">

                    <div class="tabla-cabecera">
                        <h3>Mis Equipos</h3>
                    </div>
                    <table>
                        <thead>
                            <tr class="text-center">
                                <th>Avatar</th>
                                <th>Nombre</th>
                                <th>Liga</th>
                                <th>N° de Jugadores</th>
                                <th>Estado</th>
                                <th>Ver Plantilla</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="equipo in equipos">
                                <th><img :src="'../app/cargas/equipos_avatar/'+equipo.avatar"></th>
                                <td>{{equipo.nombre}}</td>
                                <td>{{equipo.nombre_liga}}</td>
                                <td>{{equipo.jugadores_num}} jugadores</td>
                                <td>{{equipo.estado_equipo}}</td>
                                <td ><button data-toggle="tooltip" title="Edit" class="pd-setting-ed" @click="cargar_equipo(equipo.id, 'plantilla')" v-if="equipo.estado!=2 && equipo.eliminado==0">Ver</button></td>
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
                        <h3>Mis Equipos</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>