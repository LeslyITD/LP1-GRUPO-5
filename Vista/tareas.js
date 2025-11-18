function cargarTareas() {
    $.ajax({
        url: "tareas_ajax.php",
        type: "POST",
        data: { accion: "listar" },
        success: function (response) {
            let tareas = JSON.parse(response);
            let html = "";

            tareas.forEach(t => {
                html += `
                    <tr>
                        <td>${t.id}</td>
                        <td>${t.titulo}</td>
                        <td>${t.descripcion}</td>
                        <td>${t.categoria}</td>
                        <td>${t.completado == 1 ? "✔" : "❌"}</td>
                        <td>
                            <button onclick="completar(${t.id}, ${t.completado})">Estado</button>
                            <button onclick="eliminar(${t.id})">Eliminar</button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById("tabla_tareas").innerHTML = html;
        }
    });
}

function agregarTarea() {
    let titulo = $("#titulo").val();
    let descripcion = $("#descripcion").val();
    let categoria = $("#categoria").val();

    $.ajax({
        url: "tareas_ajax.php",
        type: "POST",
        data: {
            accion: "agregar",
            titulo: titulo,
            descripcion: descripcion,
            categoria: categoria
        },
        success: function (response) {
            if (response == "1") {
                alert("Tarea creada");
                cargarTareas();
            } else {
                alert("Error al crear");
            }
        }
    });
}

function completar(id, estadoActual) {
    let nuevoEstado = estadoActual == 1 ? 0 : 1;

    $.ajax({
        url: "tareas_ajax.php",
        type: "POST",
        data: {
            accion: "estado",
            id: id,
            estado: nuevoEstado
        },
        success: function () {
            cargarTareas();
        }
    });
}

function eliminar(id) {
    if (!confirm("¿Eliminar tarea?")) return;

    $.ajax({
        url: "tareas_ajax.php",
        type: "POST",
        data: {
            accion: "eliminar",
            id: id
        },
        success: function () {
            cargarTareas();
        }
    });
}
