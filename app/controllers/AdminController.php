<?php

namespace App\controllers;

use App\Core\Conexion;
use eftec\bladeone\BladeOne;

class AdminController
{
    public function guardarProducto()
    {
        if (
            empty($_POST['nombre']) ||
            empty($_POST['precio']) ||
            empty($_POST['categoria']) ||
            empty($_POST['coleccion']) ||
            empty($_POST['descripcion']) ||
            empty($_FILES['imagen']['name'])
        ) {
            header('Location: index.php?accion=agregarProducto&error=campos');
            exit;
        }

        $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
        $tipoArchivo = $_FILES['imagen']['type'];
        if (!in_array($tipoArchivo, $permitidos)) {
            header('Location: index.php?accion=agregarProducto&error=tipo');
            exit;
        }

        $tamanoArchivo = $_FILES['imagen']['size'];
        if ($tamanoArchivo > 2 * 1024 * 1024) {
            header('Location: index.php?accion=agregarProducto&error=tamano');
            exit;
        }

        $directorio = __DIR__ . '/../../public/assets/img/';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreImagen = 'img_' . uniqid() . '_' . basename($_FILES['imagen']['name']);
        $rutaDestino = $directorio . $nombreImagen;
        $rutaWeb = '/assets/img/' . $nombreImagen;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
            header('Location: index.php?accion=agregarProducto&error=upload');
            exit;
        }

        $conn = Conexion::getConexion();
        $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen, categoria, coleccion) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssdsss",
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['precio'],
            $rutaWeb,
            $_POST['categoria'],
            $_POST['coleccion']
        );
        $stmt->execute();

        header('Location: index.php?accion=adminProductos&success=1');
        exit;
    }

    public static function listarProductos()
    {
        $conexion = Conexion::getConexion();
        $consulta = $conexion->query("SELECT * FROM productos ORDER BY id DESC");
        $productos = $consulta->fetch_all(MYSQLI_ASSOC);

        $views = __DIR__ . '/../views';
        $cache = __DIR__ . '/../../cache';
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

        echo $blade->run('admin.listaProductos', ['productos' => $productos]);
    }

    public static function editarProducto()
    {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            echo "ID inválido.";
            return;
        }

        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();

        $stmtTalla = $conexion->prepare("SELECT talla, stock FROM producto_tallas WHERE id_producto = ?");
        $stmtTalla->bind_param("i", $id);
        $stmtTalla->execute();
        $resultTalla = $stmtTalla->get_result();
        while ($fila = $resultTalla->fetch_assoc()) {
            $producto['stock_' . strtolower($fila['talla'])] = $fila['stock'];
        }

        $views = __DIR__ . '/../views';
        $cache = __DIR__ . '/../../cache';
        $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
        echo $blade->run('admin.editarProducto', ['producto' => $producto]);
    }

  public static function guardarEdicion()
{
    $id = $_GET['id'] ?? null;
    if (!$id || !is_numeric($id)) {
        header('Location: index.php?accion=adminProductos&error=id');
        exit;
    }

    if (
        empty($_POST['nombre']) ||
        empty($_POST['precio']) ||
        empty($_POST['categoria']) ||
        empty($_POST['coleccion']) ||
        empty($_POST['descripcion'])
    ) {
        header("Location: index.php?accion=editarProducto&id=$id&error=campos");
        exit;
    }

    $conexion = \App\Core\Conexion::getConexion();
    $imagen = null;

    if (!empty($_FILES['imagen']['name'])) {
        $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
        $tipoArchivo = $_FILES['imagen']['type'];
        $tamanoArchivo = $_FILES['imagen']['size'];

        if (!in_array($tipoArchivo, $permitidos)) {
            header("Location: index.php?accion=editarProducto&id=$id&error=tipo");
            exit;
        }

        if ($tamanoArchivo > 2 * 1024 * 1024) {
            header("Location: index.php?accion=editarProducto&id=$id&error=tamano");
            exit;
        }

        $directorio = __DIR__ . '/../../public/assets/img/';
        $nombreImagen = uniqid('img_') . '_' . basename($_FILES['imagen']['name']);
        $rutaArchivo = $directorio . $nombreImagen;
        $rutaWeb = '/assets/img/' . $nombreImagen;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaArchivo)) {
            header("Location: index.php?accion=editarProducto&id=$id&error=subida");
            exit;
        }

        $imagen = $rutaWeb;
    }

    if ($imagen) {
        $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, categoria=?, coleccion=?, imagen=? WHERE id=?";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            die("Error en prepare (imagen): " . $conexion->error);
        }
        $stmt->bind_param(
            "ssdsssi",
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['precio'],
            $_POST['categoria'],
            $_POST['coleccion'],
            $imagen,
            $id
        );
    } else {
        $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, categoria=?, coleccion=? WHERE id=?";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            die("Error en prepare (sin imagen): " . $conexion->error);
        }
        $stmt->bind_param(
            "ssdssi",
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['precio'],
            $_POST['categoria'],
            $_POST['coleccion'],
            $id
        );
    }

    $stmt->execute();

    $stmtDelete = $conexion->prepare("DELETE FROM producto_tallas WHERE id_producto = ?");
    if (!$stmtDelete) {
        die("Error al preparar DELETE: " . $conexion->error);
    }
    $stmtDelete->bind_param("i", $id);
    $stmtDelete->execute();

    $stmtTalla = $conexion->prepare("INSERT INTO producto_tallas (id_producto, talla, stock) VALUES (?, ?, ?)");
    if (!$stmtTalla) {
        die("Error al preparar INSERT de tallas: " . $conexion->error);
    }

    foreach (['S', 'M', 'L', 'XL'] as $talla) {
        $stock = max(0, intval($_POST['stock_' . strtolower($talla)] ?? 0));
        if ($stock > 0) {
            $stmtTalla->bind_param("isi", $id, $talla, $stock);
            $stmtTalla->execute();
        }
    }

    header("Location: index.php?accion=adminProductos&success=1");
    exit;
}


    public static function eliminarProducto()
    {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            echo "ID inválido.";
            return;
        }

        $conexion = Conexion::getConexion();
        $stmt = $conexion->prepare("SELECT imagen FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $producto = $res->fetch_assoc();

        if ($producto && isset($producto['imagen'])) {
            $rutaImagen = __DIR__ . '/../../public' . $producto['imagen'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }

        $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header('Location: index.php?accion=adminProductos');
        exit;
    }
}

