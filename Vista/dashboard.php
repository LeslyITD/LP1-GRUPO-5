<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Lista de Tareas</title>
    <style>
        body {
            font-family: Poppins, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        h2 {
            margin: 0;
            margin-bottom: 20px;
            text-align: center;
            color: #444;
        }
        .formulario {
            display: flex;
            gap: 10px;
        }
        input, select {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            flex: 1;
        }
        button {
            padding: 10px 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background: #3e8e41;
        }
        ul {
            list-style: none;
            padding: 0;
            margin-top: 25px;
        }
        li {
            background: #f9f9f9;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 5px solid #4CAF50;
        }
        .categoria {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 6px;
            background: #ddd;
        }
        .acciones button {
            background: #2196F3;
            margin-left: 5px;
        }
        .acciones button.eliminar {
            background: #f44336;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Lista de Tareas (Demo Estético)</h2>
    <div class="formulario">
        <input type="text" id="titulo" placeholder="Nueva tarea...">
        <select id="categoria">
            <option>Trabajo</option>
            <option>Personal</option>
            <option>Importante</option>
        </select>
        <button onclick="agregarTarea()">Agregar</button>
    </div>
    <ul id="listaTareas">
        <!-- Se llenará con AJAX simulado -->
    </ul>
</div>
<script>
function cargarTareas() {
    fetch("tareas_ajax.php")
        .then(res => res.json())
        .then(data => {
            const lista = document.getElementById("listaTareas");
            lista.innerHTML = "";
            data.tareas.forEach(t => {
                lista.innerHTML += `
                    <li>
                        <div>
                            <strong>${t.titulo}</strong><br>
                            <span class="categoria">${t.categoria}</span>
                        </div>
                        <div class="acciones">
                            <button>✔</button>
                            <button class="eliminar">✘</button>
                        </div>
                    </li>
                `;
            });
        });
}
function agregarTarea() {
    alert("Solo demostración (no funcional).");
}  
cargarTareas();
</script>

</body>
</html>

