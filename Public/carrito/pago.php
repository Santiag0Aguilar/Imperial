<?php
@include_once __DIR__ . '/../../includes/function.php';
@include_once __DIR__ . '/../../includes/config/db.php';
incluirTamplate('session');

$db = db();

if (!isset($_SESSION['idUsuario'])) {
    header('Location: /Perfumeria/Public/login.php');
    exit;
}


$idUsuario = $_SESSION['idUsuario'];
try {
    // Verificar si el usuario ya tiene una dirección de envío registrada
    $stmt = $db->prepare("SELECT COUNT(*) FROM direcciones_envio WHERE Usuarios_id = ?");
    $stmt->execute([$idUsuario]);
    $existe = $stmt->fetchColumn(); // Devuelve el número de registros

    $FormDireccion = $existe > 0 ? true : false;
} catch (PDOException $e) {
    die("Error al verificar dirección: " . $e->getMessage());
}


$total = 0.00;

try {
    // Obtener productos del pedido pendiente
    $stmt = $db->prepare("
        SELECT dp.cantidad, dp.precio_unitario, p.PrecioDescuento
        FROM pedidos pe
        JOIN detallepedido dp ON pe.Id = dp.Pedidos_Id
        JOIN productos p ON dp.Productos_Id = p.Id
        WHERE pe.Usuarios_id = ? AND pe.estado = 'pendiente'
    ");
    $stmt->execute([$idUsuario]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($productos as $producto) {
        $precio = (isset($producto['PrecioDescuento']) && $producto['PrecioDescuento'] > 0)
            ? $producto['PrecioDescuento']
            : $producto['precio_unitario'];

        $subtotal = $precio * $producto['cantidad'];
        $total += $subtotal;
    }
} catch (PDOException $e) {
    die("Error al calcular el total: " . $e->getMessage());
}


try {
    $stmt = $db->prepare("SELECT id FROM pedidos WHERE Usuarios_id = ? AND estado = 'pendiente'");
    $stmt->execute([$idUsuario]);
    $pedidoId = $stmt->fetchColumn();
    var_dump($pedidoId); // Verifica si se obtiene el ID del pedido
} catch (PDOException $e) {
    die("Error al obtener el ID del pedido: " . $e->getMessage());
}

incluirTamplate('header', [
    'Paypal' => $Paypal = true,
    'Swal' => $Swal = true
]);
?>


<h2 class="centrar-texto">Pagar Pedido</h2>
<p class="centrar-texto">Total a pagar: <strong>$<?php echo number_format($total, 2); ?> MXN</strong></p>

<main class="Pago__Contenedor content">
    <?php if ($FormDireccion): ?>
        <p class="DireccionGuardada">Ya tienes una dirección de envío registrada. Puedes proceder a realizar el pago. <span class="resaltar">Puedes cambiarla desde las configuraciones de tu cuenta</span></p>
    <?php else: ?>
        <form class="Formulario__Contacto" id="formulario_direccion-Pago" method="POST">
            <fieldset>
                <legend>Datos de envio</legend>
                <label for="nombre_destinatario">Nombre:</label>
                <input type="text" name="nombre_destinatario" id="nombre_destinatario" placeholder="Nombre completo">

                <label for="telefono">Numero:</label>
                <input type="text" id="telefono" name="telefono" placeholder="Teléfono">

                <label for="codigo_postal">Codigo postal:</label>
                <input type="text" id="codigo_postal" name="codigo_postal" placeholder="Código postal">

                <label for="estado">Estado:</label>
                <select name="estado" id="estado">
                    <option selected disabled value="#">--Selecionar estado</option>
                </select>

                <label for="municipio">Municipio:</label>
                <select id="municipio" name="municipio">
                    <option selected disabled value="#">--Selecionar Municipio</option>
                </select>

                <label for="colonia">Colonia:</label>
                <input type="text" id="colonia" name="colonia" placeholder="Colonia">


                <label for="Calle">Calle:</label>
                <input type="text" id="Calle" name="calle" placeholder="Calle">

                <label for="numero_exterior">Numero exterior:</label>
                <input type="text" id="numero_exterior" name="numero_exterior" placeholder="Número exterior">

                <label for="numero_interior">Numero interior:</label>
                <input type="text" id="numero_interior" name="numero_interior" placeholder="Número interior (opcional)">


                <label for="referencias">Referencias:</label>
                <textarea name="referencias" id="referencias" placeholder="Referencias (opcional)"></textarea>

                <input type="hidden" name="total" value="<?php echo number_format($total, 2, '.', ''); ?>">
                <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>">

                <input type="submit" value="Guardar direccion" class="btn Boton-1">
            </fieldset>
        </form>
    <?php endif; ?>
    <div id="paypal-button-container"></div>
</main>



<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo number_format($total, 2, '.', ''); ?>'
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            actions.order.capture().then(function(details) {
                console.log(details);
                if (details.status === "COMPLETED") {
                    const {
                        id,
                        status,
                        create_time,
                        update_time,
                        payer: {
                            email_address,
                            name,
                            payer_id
                        }
                    } = details;
                    const monto = details.purchase_units[0].amount.value;
                    const PedidosId = <?php echo json_encode($pedidoId); ?>;
                    fetch('/Perfumeria/API/pago/GuardarPago.php', {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                paypal_order_id: id,
                                estado: status,
                                create_time,
                                update_time,
                                email_pagador: email_address,
                                payer_id,
                                nombre_pagador: `${name.given_name} ${name.surname}`,
                                monto,
                                json_respuesta: details,
                                Pedidos_Id: PedidosId
                            })

                        }).then(res => res.json())
                        .then(data => {
                            console.log("Respuesta del servidor:", data);
                        })
                }
            });
        },
        onCancel: function(data) {
            alert('El pago ha sido cancelado.');
            console.log('Pago Cancelado', data);
        }
    }).render('#paypal-button-container')
</script>

<?php incluirTamplate('footer'); ?>