<template id="subasta">
<?php $precio = "100"; ?>
    <div class="container-fluid subasta_form" style="display:none;">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-status-wrap ">
                    <div class="tabla-cabecera">
                    <h3>Subasta</h3>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12 formulario">
                                <div class="row">
                                    <div class="form-group col-lg-12 blanco">
                                        <label style="" class="">Nombre del Jugador</label>
                                        <input type="text"  class="form-control" v-model="nombre_jugador" value="Cristiano Ronaldo"  disabled>
                                    </div>
                                    <div class="form-group col-lg-12 blanco">
                                        <label style="" class="">Overall</label>
                                        <input type="text"  class="form-control" v-model="overall_jugador" value="82"  disabled>
                                    </div>
                                    <div class="form-group col-lg-12 blanco">
                                        <label style="" class="">Precio de Jugador</label>
                                        <input type="text" v-model="total_monto" v-model="total_monto" class="form-control" value="<?php echo $precio;  ?>" id="total-acumulado" disabled>
                                    </div> 
                                    <div class="form-group col-lg-12 blanco">
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-12">
                                                <label style="" class="">Monto</label>
                                                <div class="form-control monto-subasta">{{monto}}</div>
                                               
                                            </div>
                                            <div class="col-lg-6 col-sm-12">
                                                <label style="" class="">&nbsp;</label>
                                                <div style="display:flex;">
                                                    <button type="button" style="width:50%;background-color:#007bff;border:none;" class="form-control btn btn-primary" @click="subasta_pujar()">Ofertar +5</button>
                                                    <!-- &nbsp;<button type="button" style="width:50%;background-color:#dc3545;border:none;" class="form-control btn btn-danger" @click="modificarMonto('bajar')">Bajar</button>-->
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                  
                                    <div class="form-group col-lg-12 blanco">
                                        <label style="" class="">Tiempo restante</label>
                                        <input type="text" class="form-control" v-model="tiempo_restante" disabled>
                                    </div>
                                  
                                    <!--<div class="form-group col-lg-12 blanco text-center">
                                        <button class="btn btn-success" @click="subasta_pujar()">Ofertar</button>
                                        <br>  <span class="blanco">El monto irá aumentando de 5 en 5</span>
                                    </div> --> 
                                    <div class="form-group col-lg-12 blanco text-center" v-if="ganando_subasta!=usuario && ganando_subasta!=null">
                                        <button class="btn btn-danger" @click="rendirse_subasta()">Rendirse</button>
                                       
                                    </div>
                                </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="container">
                        <br>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 formulario">
                                    <label style="" class="">Ganando la Subasta</label>
                                    <input type="text"  class="form-control" v-model="ganando_subasta"  disabled>
                                </div>
                            </div>
                <h3 class="text-center" style="color:white;">Chat</h3>
                <div class="messaging">
                    <div class="inbox_msg">
                            <div class="mesgs">
                                <div class="msg_history" id="msg_history">
                                </div>
                                <div class="type_msg">
                                    <div class="input_msg_write" style="padding-top:7px;">
                                        <input type="hidden" id="idusuario" value="<?php echo $_SESSION["usuario"][0]["id"]; ?>">
                                        <input type="hidden" id="usuario" value="<?php echo empty($_SESSION["usuario"][0]["id_fb"]) ? $_SESSION["usuario"][0]["usuario"] : $_SESSION["usuario"][0]["nombre"]; ?>">
                                        <div style="padding-right:70px;" class="chat__input md-input" id="chat__input" contenteditable="true" @keyup.enter="enviarmensaje"  placeholder="Escribe un mensaje...">
                                        </div>
                                        <div @click="openEmojis= (openEmojis?false:true)" v-bind:class="{'btn-send-emoji':'btn-send-emoji',open:openEmojis}">
                                            <button style="right:40px;" class="msg_send_btn" type="button">
                                                <i class="fa fa-smile-o" aria-hidden="true"></i>
                                            </button>
                                            <div class="emoji-popup">
                                                <div class="emoji-wrapper" @click="mostrar_emoticon"></div>
                                            </div>
                                        </div>
                                        <button class="msg_send_btn" style="right:5px;" type="button" @click="enviarmensaje">
                                            <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>