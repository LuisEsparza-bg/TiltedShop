// JavaScript para mostrar la vista previa de las imágenes y el video

document.addEventListener("DOMContentLoaded", function () {


    $('#CrearChat').click(function () {
        let idProducto = document.getElementById('IDProduct').getAttribute('value');
        let idVendedor = document.getElementById('idVendedor').getAttribute('value');


        $.ajax({
            url: '../../HTML/Controllers/SP_Messages.php',
            method: 'POST',
            data: {
                // OPCION 1 YA QUE ES UNA ALTA
                Opcion: 1,
                idProducto: idProducto,
                idVendedor: idVendedor,
            },
            success: function (response) {
                if (response.includes("El chat")) {
                    alert("Ya existe un chat con esta cotizacion, visita tus mensajes para verlo")
                }
                else {
                    alert("Se ha creado exitosamente el chat, visita tus mensajes para verlo.")

                }
            },
            error: function (xhr, status, error) {
                // Manejar el error de la llamada AJAX
                console.log(error);
            }
        });
    });


    $('#EnviarMensaje').click(function () {
        var idChat = $(this).data('idchat');
        var idVendedor = $(this).data('idvendedor');
        var productName = $(this).data('productname');
        var idProduct = $(this).data('idproduct');
        var mensaje = $('#mensajeInput').val().trim();
        let activeId = document.getElementById('ActiveID').innerHTML;

        if (mensaje === '') {
            alert('Por favor, ingresa un mensaje.');
            return;
        }

        $.ajax({
            url: '../../../HTML/Controllers/SP_Messages.php',
            method: 'POST',
            data: {
                // OPCION 3 YA QUE ES UN MENSAJE
                Opcion: 4,
                idVendedor: idVendedor,
                idChat: idChat,
                mensaje: mensaje
            },
            success: function (response) {
                response = response.trim();
                if (response) {
                    alert("El mensaje no ha podido ser enviado, favor de contactar a los administradores.")
                }
                else {

                    $('#mensajeInput').val('');

                    $.ajax({
                        url: '../../../HTML/Controllers/SP_Messages.php',
                        method: 'POST',
                        data: {
                            Opcion: 2,
                            idChat: idChat,
                            idVendedor: idVendedor
                        },
                        success: function (response) {
                            if (response) {
                                // Convertir la respuesta JSON a objeto JavaScript
                                var messages = JSON.parse(response);
                                var chatContainer = $('.MyChats_ChatContainer').addClass('flex-row');
                                chatContainer.empty();

                                for (var i = 0; i < messages.length; i++) {
                                    var message = messages[i];
                                    var mensajeID = message.ID_Historial_Mensajes;
                                    var chatID = message.ID_Chats;
                                    var emisorID = message.ID_Usuario_Emisor;
                                    var receptorID = message.ID_Usuario_Receptor;
                                    var mensaje = message.Mensaje;
                                    var fechaMensaje = message.Fecha_Mensaje;

                                    if (mensaje.includes('ThisIsAButton')) {
                                        // Eliminar el prefijo "ThisIsAButton="
                                        var mensajeSinPrefijo = mensaje.substring(14);

                                        // Dividir el mensaje en pares clave-valor
                                        var pares = mensajeSinPrefijo.split(' data-');

                                        // Crear el código HTML del botón
                                        var botonHTML = '<button ';

                                        var regexPrecio = /data-precio=(.*?) /;
                                        var regexDescripcion = /data-descripcion=(.*) data-idChat=/;

                                        var matchPrecio = mensaje.match(regexPrecio);
                                        var matchDescripcion = mensaje.match(regexDescripcion);

                                        var botonHTML = `
                                        <div class="container justify-content-center d-flex align-items-center text-center">
                                        <div class="MyChats_ContainerProduct justify-content-center align-content-center">
                                          <small><b>Se ha mandado una oferta:</b></small>
                                          <br>
                                          <small>Precio: <b>${matchPrecio[1]}$</b></small>
                                          <br>
                                          <button id="AddItem" class="btn MyChats_BuyProduct BuyItem" data-idProducto="${idProduct}" data-idChat="${idChat}" data-idDescripcion="${matchDescripcion[1]}" data-productname="${productName}">Añadir al carrito</button>
                                          <br>
                                          <small>Producto: <b>${productName}</b></small>
                                          <br>
                                          <small>Descripción: ${matchDescripcion[1]}</small>
                                        </div>
                                        </div>
                                      `;

                                        chatContainer.append(botonHTML);
                                    } else {
                                        var messageDiv;
                                        var messageTextP;
                                        var messageDiv2;

                                        if (activeId == emisorID) {
                                            messageDiv = $('<div></div>').addClass('container justify-content-end d-flex');
                                            messageDiv2 = $('<div></div>').addClass('MyChats_ContainerTextSender');
                                            messageTextP = $('<p></p>').addClass('MyChats_TextSender').text(mensaje).attr('title', fechaMensaje).tooltip();
                                        } else {
                                            messageDiv = $('<div></div>').addClass('container');
                                            messageDiv2 = $('<div></div>').addClass('MyChats_ContainerTextReceiver');
                                            messageTextP = $('<p></p>').addClass('MyChats_TextSender').text(mensaje).attr('title', fechaMensaje).tooltip();
                                        }

                                        messageDiv2.append(messageTextP);
                                        messageDiv.append(messageDiv2);
                                        chatContainer.append(messageDiv);
                                    }
                                }
                            }
                            else {
                                var chatContainer = $('.MyChats_ChatContainer').addClass('flex-row');

                                // Limpia el contenido existente del contenedor
                                chatContainer.empty();
                            }
                        },
                        error: function (xhr, status, error) {
                            // Manejar el error de la llamada AJAX
                            console.log(error);
                        }
                    });

                }
            }
        });
    });

    // Dentro del evento click
    $('.MyChats_SelectChatButtons').click(function () {
        var idChat = $(this).data('idchat');
        var idProducto = $(this).data('idproduct');
        var idVendedor = $(this).data('sellerid');
        var sellerName = $(this).data('sellername');
        var productName = $(this).data('productname');
        let activeId = document.getElementById('ActiveID').innerHTML;

        $('#EnviarMensaje').data('idchat', idChat);
        $('#EnviarMensaje').data('idvendedor', idVendedor);
        $('#EnviarMensaje').prop('disabled', false);
        $('#EnviarMensaje').data('productname', productName);
        $('#EnviarMensaje').data('idproduct', idProducto);

        $('#EnviarProducto').data('idchat', idChat);
        $('#EnviarProducto').data('idvendedor', idVendedor);
        $('#EnviarProducto').data('productname', productName);
        $('#EnviarProducto').data('idproduct', idProducto);

        $('#EnviarProducto').prop('disabled', false);

        document.getElementById("ActiveItem").textContent = "Cotizacion para: " + productName;
        document.getElementById("ActiveItem2").textContent = "Cotizacion para: " + productName;
        document.getElementById("ActiveNameChat").textContent = "Chat con: " + sellerName;

        // Realiza una llamada AJAX al archivo PHP para obtener los mensajes del chat
        $.ajax({
            url: '../../../HTML/Controllers/SP_Messages.php',
            method: 'POST',
            data: {
                Opcion: 2,
                idChat: idChat,
                idVendedor: idVendedor
            },
            success: function (response) {
                if (response) {
                    // Convertir la respuesta JSON a objeto JavaScript
                    var messages = JSON.parse(response);
                    var chatContainer = $('.MyChats_ChatContainer').addClass('flex-row');
                    chatContainer.empty();

                    for (var i = 0; i < messages.length; i++) {
                        var message = messages[i];
                        var mensajeID = message.ID_Historial_Mensajes;
                        var chatID = message.ID_Chats;
                        var emisorID = message.ID_Usuario_Emisor;
                        var receptorID = message.ID_Usuario_Receptor;
                        var mensaje = message.Mensaje;
                        var fechaMensaje = message.Fecha_Mensaje;

                        if (mensaje.includes('ThisIsAButton')) {
                            // Eliminar el prefijo "ThisIsAButton="
                            var mensajeSinPrefijo = mensaje.substring(14);

                            // Dividir el mensaje en pares clave-valor
                            var pares = mensajeSinPrefijo.split(' data-');

                            // Crear el código HTML del botón
                            var botonHTML = '<button ';

                            var regexPrecio = /data-precio=(.*?) /;
                            var regexDescripcion = /data-descripcion=(.*) data-idChat=/;

                            var matchPrecio = mensaje.match(regexPrecio);
                            var matchDescripcion = mensaje.match(regexDescripcion);

                            var botonHTML = `
                            <div class="container justify-content-center d-flex align-items-center text-center">
                            <div class="MyChats_ContainerProduct justify-content-center align-content-center">
                              <small><b>Se ha mandado una oferta:</b></small>
                              <br>
                              <small>Precio: <b>${matchPrecio[1]}$</b></small>
                              <br>
                              <button id="AddItem" class="btn MyChats_BuyProduct" data-idProducto="${idProducto}" data-idChat="${idChat}" data-idDescripcion="${matchDescripcion[1]}" data-productname="${productName}">Añadir al carrito</button>
                              <br>
                              <small>Producto: <b>${productName}</b></small>
                              <br>
                              <small>Descripción: ${matchDescripcion[1]}</small>
                            </div>
                            </div>
                          `;

                            chatContainer.append(botonHTML);
                        } else {
                            var messageDiv;
                            var messageTextP;
                            var messageDiv2;

                            if (activeId == emisorID) {
                                messageDiv = $('<div></div>').addClass('container justify-content-end d-flex');
                                messageDiv2 = $('<div></div>').addClass('MyChats_ContainerTextSender');
                                messageTextP = $('<p></p>').addClass('MyChats_TextSender').text(mensaje).attr('title', fechaMensaje).tooltip();
                            } else {
                                messageDiv = $('<div></div>').addClass('container');
                                messageDiv2 = $('<div></div>').addClass('MyChats_ContainerTextReceiver');
                                messageTextP = $('<p></p>').addClass('MyChats_TextSender').text(mensaje).attr('title', fechaMensaje).tooltip();
                            }

                            messageDiv2.append(messageTextP);
                            messageDiv.append(messageDiv2);
                            chatContainer.append(messageDiv);
                        }
                    }
                }
                else {
                    var chatContainer = $('.MyChats_ChatContainer').addClass('flex-row');

                    // Limpia el contenido existente del contenedor
                    chatContainer.empty();
                }
            },
            error: function (xhr, status, error) {
                // Manejar el error de la llamada AJAX
                console.log(error);
            }
        });
    });



    $('#EnviarProducto').click(function () {
        var idChat = $(this).data('idchat');
        var idProducto = $(this).data('idproduct');
        var productName = $(this).data('productname');
        var idVendedor = $(this).data('idvendedor');
        var idChat = $(this).data('idchat');
        let activeId = document.getElementById('ActiveID').innerHTML;

        var precio = $('#precio').val();
        var descripcion = $('#descripcion').val().trim();

        var ManageMessage = "ThisIsAButton=";

        if (precio <= 0 || precio == "") {
            alert('El precio debe ser mayor a 0 o no debe estar vacío');
            return;
        }

        if (descripcion == "") {
            alert('La descripcion no debe de estar vacia');
            return;
        }

        var mensaje = ManageMessage + " data-precio=" + precio + " data-descripcion=" + descripcion + " data-idChat=" + idChat + " data-idProduct=" + idProducto;

        $.ajax({
            url: '../../../HTML/Controllers/SP_Messages.php',
            method: 'POST',
            data: {
                // OPCION 3 YA QUE ES UN MENSAJE
                Opcion: 4,
                idVendedor: idVendedor,
                idChat: idChat,
                mensaje: mensaje
            },
            success: function (response) {
                response = response.trim();
                if (response) {
                    alert("El producto no ha podido ser enviado, favor de contactar a los administradores.")
                }
                else {


                    $.ajax({
                        url: '../../../HTML/Controllers/SP_Messages.php',
                        method: 'POST',
                        data: {
                            Opcion: 2,
                            idChat: idChat,
                            idVendedor: idVendedor
                        },
                        success: function (response) {
                            if (response) {
                                // Convertir la respuesta JSON a objeto JavaScript
                                var messages = JSON.parse(response);
                                var chatContainer = $('.MyChats_ChatContainer').addClass('flex-row');
                                chatContainer.empty();
            
                                for (var i = 0; i < messages.length; i++) {
                                    var message = messages[i];
                                    var mensajeID = message.ID_Historial_Mensajes;
                                    var chatID = message.ID_Chats;
                                    var emisorID = message.ID_Usuario_Emisor;
                                    var receptorID = message.ID_Usuario_Receptor;
                                    var mensaje = message.Mensaje;
                                    var fechaMensaje = message.Fecha_Mensaje;
            
                                    if (mensaje.includes('ThisIsAButton')) {
                                        // Eliminar el prefijo "ThisIsAButton="
                                        var mensajeSinPrefijo = mensaje.substring(14);
            
                                        // Dividir el mensaje en pares clave-valor
                                        var pares = mensajeSinPrefijo.split(' data-');
            
                                        // Crear el código HTML del botón
                                        var botonHTML = '<button ';
            
                                        var regexPrecio = /data-precio=(.*?) /;
                                        var regexDescripcion = /data-descripcion=(.*) data-idChat=/;
            
                                        var matchPrecio = mensaje.match(regexPrecio);
                                        var matchDescripcion = mensaje.match(regexDescripcion);
            
                                        var botonHTML = `
                                        <div class="container justify-content-center d-flex align-items-center text-center">
                                        <div class="MyChats_ContainerProduct justify-content-center align-content-center">
                                          <small><b>Se ha mandado una oferta:</b></small>
                                          <br>
                                          <small>Precio: <b>${matchPrecio[1]}$</b></small>
                                          <br>
                                          <button id="AddItem" class="btn MyChats_BuyProduct" data-idProducto="${idProducto}" data-idChat="${idChat}" data-idDescripcion="${matchDescripcion[1]}" data-productname="${productName}">Añadir al carrito</button>
                                          <br>
                                          <small>Producto: <b>${productName}</b></small>
                                          <br>
                                          <small>Descripción: ${matchDescripcion[1]}</small>
                                        </div>
                                        </div>
                                      `;
            
                                        chatContainer.append(botonHTML);
                                    } else {
                                        var messageDiv;
                                        var messageTextP;
                                        var messageDiv2;
            
                                        if (activeId == emisorID) {
                                            messageDiv = $('<div></div>').addClass('container justify-content-end d-flex');
                                            messageDiv2 = $('<div></div>').addClass('MyChats_ContainerTextSender');
                                            messageTextP = $('<p></p>').addClass('MyChats_TextSender').text(mensaje).attr('title', fechaMensaje).tooltip();
                                        } else {
                                            messageDiv = $('<div></div>').addClass('container');
                                            messageDiv2 = $('<div></div>').addClass('MyChats_ContainerTextReceiver');
                                            messageTextP = $('<p></p>').addClass('MyChats_TextSender').text(mensaje).attr('title', fechaMensaje).tooltip();
                                        }
            
                                        messageDiv2.append(messageTextP);
                                        messageDiv.append(messageDiv2);
                                        chatContainer.append(messageDiv);
                                    }
                                }
                            }
                            else {
                                var chatContainer = $('.MyChats_ChatContainer').addClass('flex-row');
            
                                // Limpia el contenido existente del contenedor
                                chatContainer.empty();
                            }
                        },
                        error: function (xhr, status, error) {
                            // Manejar el error de la llamada AJAX
                            console.log(error);
                        }
                    });

                }
            }
        });

    });


});