<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - To Do List</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 550px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
        }

        .add-box {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
        }

        .add-box input, .add-box select {
            padding: 10px;
            flex: 1;
            border-radius: 6px;
            border: 1px solid #aaa;
        }

        .add-box button {
            padding: 10px 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .filters {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .filters button {
            padding: 7px 12px;
            border: none;
            background: #ddd;
            border-radius: 5px;
            cursor: pointer;
        }

        .task {
            margin-bottom: 10px;
            background: #f8fafc;
            padding: 10px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            border-left: 4px solid #4CAF50;
        }

        .task span {
            flex: 1;
            margin-left: 10px;
        }

        .task.completed span {
            text-decoration: line-through;
            opacity: 0.6;
        }

        .done-btn, .delete-btn {
            border: none;
            padding: 6px 9px;
            cursor: pointer;
            border-radius: 5px;
            margin-left: 5px;
        }

        .done-btn {
            background: #2a9d8f;
            color: white;
        }

        .delete-btn {
            background: #e63946;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>To Do List - Dashboard</h2>

    <!-- Sección Agregar -->
    <div class="add-box">
        <input type="text" id="taskInput" placeholder="Escribe una nueva tarea">
        <select id="category">
            <option value="Trabajo">Trabajo</option>
            <option value="Personal">Personal</option>
            <option value="Importante">Importante</option>
        </select>
        <button onclick="addTask()">Agregar</button>
    </div>

    <!-- Filtros -->
    <div class="filters">
        <button onclick="filterTasks('todas')">Todas</button>
        <button onclick="filterTasks('pendientes')">Pendientes</button>
        <button onclick="filterTasks('completadas')">Completadas</button>
    </div>

    <!-- Lista de tareas -->
    <div id="taskList"></div>
</div>


<script>
    let tasks = [];

    function addTask() {
        let text = document.getElementById("taskInput").value;
        let cat = document.getElementById("category").value;

        if (text.trim() === "") return alert("Debes escribir una tarea.");

        let task = {
            id: Date.now(),
            name: text,
            category: cat,
            completed: false
        };

        tasks.push(task);
        renderTasks();

        // AJAX futuro:
        // fetch('/tareas/agregar', {method:'POST', body:JSON.stringify(task)});
    }

    function renderTasks(filter = "todas") {
        let area = document.getElementById("taskList");
        area.innerHTML = "";

        let filtered = tasks.filter(t => {
            if (filter === "pendientes") return !t.completed;
            if (filter === "completadas") return t.completed;
            return true;
        });

        filtered.forEach(t => {
            let div = document.createElement("div");
            div.classList.add("task");
            if (t.completed) div.classList.add("completed");

            div.innerHTML = `
                <strong>[${t.category}]</strong>
                <span>${t.name}</span>
                <button class="done-btn" onclick="toggleTask(${t.id})">✔</button>
                <button class="delete-btn" onclick="deleteTask(${t.id})">✘</button>
            `;

            area.appendChild(div);
        });
    }

    function toggleTask(id) {
        let task = tasks.find(t => t.id === id);
        task.completed = !task.completed;
        renderTasks();

        // AJAX futuro aquí
    }

    function deleteTask(id) {
        tasks = tasks.filter(t => t.id !== id);
        renderTasks();

        // AJAX futura eliminación en BD
    }

    function filterTasks(type) {
        renderTasks(type);
    }
</script>

</body>
</html>
