<template id="copas-lista">


    <div class="container-fluid" v-if="mostrar" >

        <div class="row" v-if="mostrar_grafico">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>GRÁFICO DE COPA</h3>
                        <p  style="float:right" ><button class="btn btn-primary"  @click="regresar()" >Regresar</button></p>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grafico-copa">
                            <div class="text-center" ><h3 >{{copa_datos.nombre}}</h3>

                            </div>
                            <div id="people"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-else>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap tablas">
                    <div class="tabla-cabecera">
                        <h3>Listado de Copas</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Nombre</th>
                                <th>Temporada</th>
                                <th>Estado</th>
                                <th>Participantes</th>
                                <th>Fecha de Creación</th>
                                <th>Gráfico</th>
                            </tr>
                        </thead>
                        <tbody class="center">
                            <tr v-for="copa in copas">
                                <th><img src="../images/copa.png" alt=""></th>
                                <td>{{copa.nombre}}</td>
                                <td>{{copa.temporada}}</td>
                                <td>{{copa.estado}} </td>
                                <td>{{copa.nro_participantes}}</td>
                                <td>{{copa.fecha_creacion}}</td>
                                <td>
                                    <button @click="cargar_equipos(copa)" data-toggle="tooltip" title="Edit" class="pd-setting-ed">Ver</button>
                                </td>
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
                    <h3>Listado de Copas</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>