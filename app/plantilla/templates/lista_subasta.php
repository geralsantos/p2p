<template  id="lista-subasta">
  <div class="container-fluid" v-if="mostrar">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="product-status-wrap tablas">
                <div class="tabla-cabecera">
                    <h3>Jugadores en Subasta</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Posición</th>
                            <th>Altura</th>
                            <th>Overall</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <tr v-for="(row,index) in lista_jugadores">
                            <td>{{row.nombre}}</td>
                            <td>{{row.posicion}}</td>
                            <td>{{row.altura}}</td>
                            <td>{{row.overall}}</td>
                            <td><a @click="unirse_subasta(row)"><button v-bind:title="row.estado_subasta" v-bind:style="{'background-color': row.color_estado_subasta,'width':'100%','color':'white'}" class="pd-setting-ed"  >{{row.estado_subasta}}</button></a></td>
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
                        <h3>Jugadores en subastas</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>