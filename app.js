// Variables globales
var filtroActual = 'todas';
var categoriaFiltro = 0;

// Cuando carga la página
document.addEventListener('DOMContentLoaded', function() {
    cargarCategorias();
    cargarTareas();
    
    // Agregar tarea
    document.getElementById('form-tarea').addEventListener('submit', agregarTarea);
    
    // Editar tarea
    document.getElementById('form-editar').addEventListener('submit', guardarEdicion);
    
    // Filtros
    var botones = document.querySelectorAll('.btn-filtro');
    for (var i = 0; i < botones.length; i++) {
        botones[i].addEventListener('click', function() {
            for (var j = 0; j < botones.length; j++) {
                botones[j].classList.remove('activo');
            }
            this.classList.add('activo');
            filtroActual = this.getAttribute('data-filtro');
            cargarTareas();
        });
    }
    
    // Filtro categoría
    document.getElementById('filtro-categoria').addEventListener('change', function() {
        categoriaFiltro = this.value;
        cargarTareas();
    });
});

// Cargar categorías
function cargarCategorias() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'api.php?accion=obtener_categorias', true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            var resp = JSON.parse(xhr.responseText);
            if (resp.exito) {
                var opciones = '<option value="">Sin categoría</option>';
                var opcionesFiltro = '<option value="0">Todas las categorías</option>';
                for (var i = 0; i < resp.datos.length; i++) {
                    var cat = resp.datos[i];
                    opciones += '<option value="' + cat.id + '">' + cat.nombre + '</option>';
                    opcionesFiltro += '<option value="' + cat.id + '">' + cat.nombre + '</option>';
                }
                document.getElementById('categoria').innerHTML = opciones;
                document.getElementById('filtro-categoria').innerHTML = opcionesFiltro;
                document.getElementById('editar-categoria').innerHTML = opciones;
            }
        }
    };
    xhr.send();
}

// Cargar tareas
function cargarTareas() {
    var xhr = new XMLHttpRequest();
    var url = 'api.php?accion=obtener_tareas&filtro=' + filtroActual + '&categoria=' + categoriaFiltro;
    xhr.open('GET', url, true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            var resp = JSON.parse(xhr.responseText);
            if (resp.exito) {
                mostrarTareas(resp.datos);
                actualizarEstadisticas();
            }
        }
    };
    xhr.send();
}

// Mostrar tareas en pantalla
function mostrarTareas(tareas) {
    var contenedor = document.getElementById('lista-tareas');
    
    if (tareas.length == 0) {
        contenedor.innerHTML = '<p class="mensaje-vacio">No hay tareas</p>';
        return;
    }
    
    var html = '';
    for (var i = 0; i < tareas.length; i++) {
        var t = tareas[i];
        var claseCompletada = t.completada == 1 ? 'completada' : '';
        
        html += '<div class="tarea ' + claseCompletada + '">';
        html += '<input type="checkbox" ' + (t.completada == 1 ? 'checked' : '') + ' onchange="toggleCompletada(' + t.id + ')">';
        html += '<div class="tarea-info">';
        html += '<div class="tarea-titulo">' + t.titulo + '</div>';
        if (t.descripcion) {
            html += '<div class="tarea-descripcion">' + t.descripcion + '</div>';
        }
        if (t.categoria_nombre) {
            html += '<span class="tarea-categoria" style="background:' + t.categoria_color + '">' + t.categoria_nombre + '</span>';
        }
        html += '</div>';
        html += '<div class="tarea-acciones">';
        html += '<button class="btn-editar" onclick="abrirModal(' + t.id + ')">Editar</button>';
        html += '<button class="btn-eliminar" onclick="eliminarTarea(' + t.id + ')">Eliminar</button>';
        html += '</div>';
        html += '</div>';
    }
    
    contenedor.innerHTML = html;
}