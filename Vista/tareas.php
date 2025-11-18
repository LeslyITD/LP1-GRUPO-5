<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Tareas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }
        button {
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
        }
        .btn-edit { background: #007bff; color: white; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-estado { background: #28a745; color: white; }
    </style>
</head>
<body>

<h2>Agregar nueva tarea</h2>

<label>Título</label><br>
<input type="text" id="titulo"><br><br>

<label>Descripción</label><br>
<textarea id="descripcion"></textarea><br><br>

<label>Categoría</label><br>
<input type="text" id="categoria"><br><br>

<button onclick="guardarTarea()">Guardar</button>

<hr>

<h3>Lista de tareas</h3>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="tablaTareas"></tbody>
</table>

<script>
// ===================== GUARDAR TAREA =====================
function guardarTarea() {
    const datos = new FormData();
    datos.append("titulo", document.getElementById("titulo").value);
    datos.append("descripcion", document.getElementById("descripcion").value);
    datos.append("categoria", document.getElementById("categoria").value);

    fetch("../Controlador/guardar_tarea.php", {
        method: "POST",
        body: datos
    })
    .then(res => res.text())
    .then(data => {
        if (data === "ok") {
            alert("Tarea guardada");
            cargarTareas();
        } else {
            alert("Error al guardar");
        }
    });
}

// ===================== LISTAR TAREAS =====================
function cargarTareas() {
    fetch("../Controlador/listarTareas.php")
    .then(res => res.json())
    .then(tareas => {
        let tabla = "";

        tareas.forEach(t => {
            tabla += `
                <tr>
                    <td>${t.id}</td>
                    <td>${t.titulo}</td>
                    <td>${t.descripcion}</td>
                    <td>${t.categoria}</td>
                    <td>${t.completado == 1 ? "✔" : "❌"}</td>
                    <td>
                        <button class="btn-estado" onclick="cambiarEstado(${t.id}, ${t.completado})">Estado</button>
                        <button class="btn-edit" onclick="editarTarea(${t.id})">Editar</button>
                        <button class="btn-delete" onclick="eliminarTarea(${t.id})">Eliminar</button>
                    </td>
                </tr>
            `;
        });

        document.getElementById("tablaTareas").innerHTML = tabla;
    });
}

window.onload = cargarTareas;

// ===================== CAMBIAR ESTADO =====================
function cambiarEstado(id, estado) {
    let datos = new FormData();
    datos.append("id", id);
    datos.append("estado", estado == 1 ? 0 : 1);

    fetch("../Controlador/cambiar_estado.php", {
        method: "POST",
        body: datos
    })
    .then(res => res.text())
    .then(() => cargarTareas());
}

// ===================== ELIMINAR =====================
function eliminarTarea(id) {
    if (!confirm("¿Eliminar tarea?")) return;

    let datos = new FormData();
    datos.append("id", id);

    fetch("../Controlador/eliminar_tarea.php", {
        method: "POST",
        body: datos
    })
    .then(res => res.text())
    .then(() => cargarTareas());
}

// ===================== EDITAR =====================
function editarTarea(id) {
    let nuevoTitulo = prompt("Nuevo título:");
    let nuevaDesc = prompt("Nueva descripción:");
    let nuevaCategoria = prompt("Nueva categoría:");

    let datos = new FormData();
    datos.append("id", id);
    datos.append("titulo", nuevoTitulo);
    datos.append("descripcion", nuevaDesc);
    datos.append("categoria", nuevaCategoria);

    fetch("../Controlador/editar_tarea.php", {
        method: "POST",
        body: datos
    }).then(() => cargarTareas());
}
</script>

</body>
</html>
