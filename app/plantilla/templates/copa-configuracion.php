<template id="copa-configuracion">
    <div class="container-fluid" v-if="mostrar">
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
                        <h3>Configuración Copa</h3>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 formulario">
                            <form v-on:submit.prevent="guardar">
                                <div class="row">
                                    <div class="form-group col-lg-12 blanco text-center">
                                        <label>Nombre Copa</label>
                                        <input class="form-control" type="text" v-model="nombre_copa" placeholder="Ingrese nombre de la copa">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-12 text-center">
                                        <button class="btn btn-success" type="submit">Actualizar</button>
                                    </div>
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
                        <h3>Configuración Copa</h3>
                        <p class="blanco">{{mensaje}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>