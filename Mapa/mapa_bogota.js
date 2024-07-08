// Crear el mapa
var map = L.map('map').setView([4.710989, -74.072092], 12);

// Agregar capa de OpenStreetMap al mapa
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Definir el icono personalizado para los marcadores
var pinIcon = L.icon({
    iconUrl: 'pinarbolv2.png',
    iconSize: [25, 41]
});


// Función para agregar un marcador al mapa
function addMarker(pin) {
    var marker = L.marker([parseFloat(pin.latitud), parseFloat(pin.longitud)], { icon: pinIcon })
        .addTo(map)
        .bindPopup(`    
        
    <div class="custom-popup-image">
    <img src="${pin.direccion}" alt="${pin.id}" class="popup-image">
    </div>
    <div class="custom-popup">
        <h3 class="nom">${pin.nombre}</h3>
        <h3 class="ubi">Ubicación: ${pin.Direccion}</h3>
    </div>
    
`, { autoPan: true , closeOnClick: false}).on('click', function() {
    abrirVentana(pin);
}).on('mouseover', function(e) {
    this.openPopup();
}).on('mouseout', function(e) {
    this.closePopup();
});
}

// Función para cargar los datos de los pines desde el servidor
function loadPinsData() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'obtener_pines.php', true);
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            var pinesData = JSON.parse(xhr.responseText);
            pinesData.forEach(function (pin) {
                addMarker(pin);
                //console.log(pin);
            });
        } else {
            console.error('Error al obtener los datos de los pines.');
        }
    };
    xhr.send();
}

// Cargar los datos de los pines al iniciar la página
loadPinsData();

// Evento de clic en el mapa para cerrar la ventana
map.on('click', function () {
    cerrarVentana();
});

// Función para abrir la ventana al hacer clic en un pin
function abrirVentana(pin) {
    var cuadro = document.getElementById("cuadro");
    var cuadroPos = cuadro.getBoundingClientRect(); // Obtenemos la posición actual del cuadro
    var cuadroWidth = cuadroPos.width; // Ancho del cuadro
    cuadro.style.left = "0"; // Mostrar el cuadro
    cargarInformacionTaxonomia(pin);
}


// Función para cerrar la ventana
function cerrarVentana() {
    var cuadro = document.getElementById("cuadro");
    var cuadroPos = cuadro.getBoundingClientRect();
    var cuadroWidth = cuadroPos.width;
    cuadro.style.left = "-" + cuadroWidth + "px"; // Ocultar el cuadro
}

// Función para cargar la información de la taxonomía
function cargarInformacionTaxonomia(pin) {
    var contenidoTaxonomia = document.getElementById("contenidoTaxonomia");
    contenidoTaxonomia.innerHTML = ""; // Limpiar el contenido anterior

    var xhrTaxonomia = new XMLHttpRequest();
    xhrTaxonomia.open('GET', 'obtener_planta.php?id=' + encodeURIComponent(pin.ID_Pin), true);
    console.log(pin);
    console.log(pin.ID_Pin);
    xhrTaxonomia.onload = function () {
        if (xhrTaxonomia.status >= 200 && xhrTaxonomia.status < 300) {
            var plantaData = JSON.parse(xhrTaxonomia.responseText);
            var propiedades = ["Nombre", "Reino", "Division", "Subdivision", "Clase", "Subclase", "Orden", "Familia", "Genero", "Especie"];
            console.log(plantaData);
            var lista = document.createElement("ul");
            propiedades.forEach(function (propiedad) {
                var li = document.createElement("li");
                var propiedadMin = propiedad.charAt(0).toLowerCase() + propiedad.slice(1);
                if (plantaData[propiedadMin] !== undefined) {
                    li.textContent = `${propiedad}: ${plantaData[propiedadMin]}`;
                } else {
                    li.textContent = `${propiedad}: No disponible`;
                }
                lista.appendChild(li);
            });

            // Crear elemento de imagen
            var imagen = document.createElement("img");
            imagen.src = plantaData.direccion; // Asignar la dirección de la imagen
            imagen.alt = "Imagen de la planta"; // Texto alternativo para la imagen
            contenidoTaxonomia.appendChild(imagen); // Agregar la imagen al contenido de la taxonomía
            
            contenidoTaxonomia.appendChild(lista); // Agregar la lista al contenido de la taxonomía

            // Mostrar información de investigación
            var propiedadesClima = [
                { propiedad: "nombrec", nombreCasilla: "Ciudad" },
                { propiedad: "descripcionc", nombreCasilla: "Descripción" },
                { propiedad: "temperatura_promedio", nombreCasilla: "Temperatura Promedio" },
                { propiedad: "humedad_promedio", nombreCasilla: "Humedad Promedio" }
            ];
            
            var listaClima = document.createElement("ul");
            
            propiedadesClima.forEach(function (propiedadObj) {
                var li = document.createElement("li");
                var propiedad = propiedadObj.propiedad;
                var nombreCasilla = propiedadObj.nombreCasilla;
            
                if (plantaData[propiedad] !== undefined) {
                    li.textContent = `${nombreCasilla}: ${plantaData[propiedad]}`;
                } else {
                    li.textContent = `${nombreCasilla}: No disponible`;
                }
            
                listaClima.appendChild(li);
            });
            
            contenidoTaxonomia.appendChild(listaClima);


            var propiedadesregion = [
                { propiedad: "Nombre", nombreCasilla: "Planta" },
                { propiedad: "Ciudad", nombreCasilla: "Region" },
                { propiedad: "Descripcions", nombreCasilla: "Descripción del suelo" }
            ];
            
            var listaregion = document.createElement("ul");
            
            propiedadesregion.forEach(function (propiedadObj) {
                var li = document.createElement("li");
                var propiedad = propiedadObj.propiedad;
                var nombreCasilla = propiedadObj.nombreCasilla;
            
                if (plantaData[propiedad] !== undefined) {
                    li.textContent = `${nombreCasilla}: ${plantaData[propiedad]}`;
                } else {
                    li.textContent = `${nombreCasilla}: No disponible`;
                }
            
                listaregion.appendChild(li);
            });
            
            contenidoTaxonomia.appendChild(listaregion);


            var propiedadescuidado = [
                { propiedad: "tipo_cuidado", nombreCasilla: "Tipo de cuidado" },
                { propiedad: "INFORMACION", nombreCasilla: "Informacion de cuidado" },
                { propiedad: "TIPO", nombreCasilla: "Tipo de Plaga" },
                { propiedad: "Descripcion", nombreCasilla: "Informacion de plagas" },
                { propiedad: "Ciudad", nombreCasilla: "Tipo de Plaga" },
                { propiedad: "Direccion", nombreCasilla: "Direccion" },
                { propiedad: "nombrej", nombreCasilla: "Jardin Botanico" },
                { propiedad: "superficie", nombreCasilla: "Superficie" },
                { propiedad: "ubicacion", nombreCasilla: "Ubicacion del Jardin" },
                { propiedad: "nombrez", nombreCasilla: "Zona de Reserva" },
                { propiedad: "inicio_flora", nombreCasilla: "Inicio de Flora" },
                { propiedad: "fin_flora", nombreCasilla: "Finalizacion de Flora" }
            ];
            
            var listacuidado = document.createElement("ul");
            
            propiedadescuidado.forEach(function (propiedadObj) {
                var li = document.createElement("li");
                var propiedad = propiedadObj.propiedad;
                var nombreCasilla = propiedadObj.nombreCasilla;
            
                if (plantaData[propiedad] !== undefined) {
                    li.textContent = `${nombreCasilla}: ${plantaData[propiedad]}`;
                } else {
                    li.textContent = `${nombreCasilla}: No disponible`;
                }
            
                listacuidado.appendChild(li);
            });
            
            contenidoTaxonomia.appendChild(listacuidado);

            
            if (plantaData.investigaciones && plantaData.investigaciones.length > 0) {
                var investigacionesTitulo = document.createElement("h3");
                investigacionesTitulo.textContent = "Investigaciones";
                contenidoTaxonomia.appendChild(investigacionesTitulo);

                var listaInvestigaciones = document.createElement("ul");
                plantaData.investigaciones.forEach(function (investigacion) {
                    var li = document.createElement("li");
                    li.innerHTML = `<strong>Autor:</strong> ${investigacion.autor} <br><strong>Título:</strong> ${investigacion.titulo} <br><strong>Información:</strong> ${investigacion.contenido}`;

                    listaInvestigaciones.appendChild(li);
                });
                contenidoTaxonomia.appendChild(listaInvestigaciones);
            } else {
                var sinInvestigaciones = document.createElement("p");
                sinInvestigaciones.textContent = "No hay investigaciones disponibles.";
                contenidoTaxonomia.appendChild(sinInvestigaciones);
            }

            // Mostrar el cuadro después de cargar los datos
            var cuadro = document.getElementById("cuadro");
            cuadro.style.left = "0";
        } else {
            console.error('Error al obtener la información de la taxonomía.');
            contenidoTaxonomia.textContent = 'Error al cargar la información de la taxonomía.';
        }
    };
    xhrTaxonomia.send();
}







