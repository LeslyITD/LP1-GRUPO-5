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

// Actualizar estadísticas
function actualizarEstadisticas() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'api.php?accion=obtener_tareas', true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            var resp = JSON.parse(xhr.responseText);
            if (resp.exito) {
                var total = resp.datos.length;
                var completadas = 0;
                for (var i = 0; i < resp.datos.length; i++) {
                    if (resp.datos[i].completada == 1) completadas++;
                }
                document.getElementById('total-tareas').textContent = total;
                document.getElementById('tareas-completadas').textContent = completadas;
                document.getElementById('tareas-pendientes').textContent = total - completadas;
            }
        }
    };
    xhr.send();
}

// Agregar tarea
function agregarTarea(e) {
    e.preventDefault();
    
    var datos = 'accion=agregar_tarea';
    datos += '&titulo=' + encodeURIComponent(document.getElementById('titulo').value);
    datos += '&descripcion=' + encodeURIComponent(document.getElementById('descripcion').value);
    datos += '&categoria_id=' + document.getElementById('categoria').value;
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'api.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status == 200) {
            var resp = JSON.parse(xhr.responseText);
            if (resp.exito) {
                document.getElementById('form-tarea').reset();
                cargarTareas();
                alert('Tarea agregada');
            }
        }
    };
    xhr.send(datos);
}

// Marcar/desmarcar completada
function toggleCompletada(id) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'api.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status == 200) {
            cargarTareas();
        }
    };
    xhr.send('accion=toggle_completada&id=' + id);
}

// Eliminar tarea
function eliminarTarea(id) {
    if (!confirm('¿Eliminar esta tarea?')) return;
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'api.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status == 200) {
            cargarTareas();
            alert('Tarea eliminada');
        }
    };
    xhr.send('accion=eliminar_tarea&id=' + id);
}

// Abrir modal para editar
function abrirModal(id) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'api.php?accion=obtener_tareas', true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            var resp = JSON.parse(xhr.responseText);
            for (var i = 0; i < resp.datos.length; i++) {
                if (resp.datos[i].id == id) {
                    var t = resp.datos[i];
                    document.getElementById('editar-id').value = t.id;
                    document.getElementById('editar-titulo').value = t.titulo;
                    document.getElementById('editar-descripcion').value = t.descripcion || '';
                    document.getElementById('editar-categoria').value = t.categoria_id || '';
                    document.getElementById('modal').classList.add('mostrar');
                    break;
                }
            }
        }
    };
    xhr.send();
}

// Cerrar modal
function cerrarModal() {
    document.getElementById('modal').classList.remove('mostrar');
}

// Guardar edición
function guardarEdicion(e) {
    e.preventDefault();
    
    var datos = 'accion=editar_tarea';
    datos += '&id=' + document.getElementById('editar-id').value;
    datos += '&titulo=' + encodeURIComponent(document.getElementById('editar-titulo').value);
    datos += '&descripcion=' + encodeURIComponent(document.getElementById('editar-descripcion').value);
    datos += '&categoria_id=' + document.getElementById('editar-categoria').value;
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'api.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status == 200) {
            var resp = JSON.parse(xhr.responseText);
            if (resp.exito) {
                cerrarModal();
                cargarTareas();
                alert('Tarea actualizada');
            }
        }
    };
    xhr.send(datos);
}
