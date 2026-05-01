-- ============================================================
-- SEED: datos de ejemplo para BarberShop
-- Se ejecuta automáticamente en el primer docker compose up
-- Todas las fechas son relativas al día de ejecución (CURDATE)
-- Contraseña de todos los empleados: 12345
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Empleados adicionales (id=1 'admin' ya existe en barber_shop.sql)
-- --------------------------------------------------------
INSERT IGNORE INTO `empleados` (`id`, `nombre`, `password`, `telefono`, `cargo`, `fecha_contratacion`, `salario`, `status`) VALUES
(2, 'Carlos Mendez', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '3109876543', 'Barbero',       DATE_SUB(CURDATE(), INTERVAL 365 DAY), 1200000, 1),
(3, 'Maria Lopez',   '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '3205551234', 'Recepcionista', DATE_SUB(CURDATE(), INTERVAL 180 DAY),  900000, 1);

-- --------------------------------------------------------
-- Clientes
-- --------------------------------------------------------
INSERT IGNORE INTO `clientes` (`id`, `nombre`, `telefono`, `fecha_registro`, `observaciones`, `status`) VALUES
(1, 'Juan Garcia',   '3001112233', DATE_SUB(NOW(), INTERVAL 30 DAY),  'Cliente frecuente',           1),
(2, 'Pedro Ramirez', '3112223344', DATE_SUB(NOW(), INTERVAL 20 DAY),  NULL,                          1),
(3, 'Ana Torres',    '3203334455', DATE_SUB(NOW(), INTERVAL 10 DAY),  'Alergia a algunos productos', 1),
(4, 'Luis Martinez', '3154445566', DATE_SUB(NOW(), INTERVAL  5 DAY),  NULL,                          1);

-- --------------------------------------------------------
-- Servicios
-- --------------------------------------------------------
INSERT IGNORE INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracionMinutos`, `imagen`, `status`) VALUES
(1, 'Corte de cabello', 'Corte clasico a tijera y maquina',          25000, 30, 'Assets/img/barber_shop.jpg', 1),
(2, 'Afeitado clasico', 'Afeitado con navaja y toalla caliente',     20000, 20, 'Assets/img/barber_shop.jpg', 1),
(3, 'Corte + Barba',    'Combo completo de corte y arreglo de barba',40000, 45, 'Assets/img/barber_shop.jpg', 1);

-- --------------------------------------------------------
-- Productos
-- --------------------------------------------------------
INSERT IGNORE INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `stock`, `stockMin`, `status`) VALUES
(1, 'Pomada para cabello', 'Fijacion fuerte con acabado mate',           25000, 20, 5, 1),
(2, 'Shampoo para barba',  'Shampoo acondicionador especial para barba', 35000, 15, 3, 1),
(3, 'Aceite de barba',     'Aceite hidratante con aroma de madera',      45000, 10, 2, 1),
(4, 'Cera moldeadora',     'Fijacion media con brillo natural',          28000, 12, 3, 1);

-- --------------------------------------------------------
-- Citas (hoy a distintas horas)
-- --------------------------------------------------------
INSERT IGNORE INTO `citas` (`id`, `cliente_id`, `fechaInicio`, `fechaFin`, `notas`, `total`, `status`) VALUES
(1, 1, CONCAT(CURDATE(), ' 09:00:00'), CONCAT(CURDATE(), ' 09:30:00'), NULL,            25000, 1),
(2, 2, CONCAT(CURDATE(), ' 10:00:00'), CONCAT(CURDATE(), ' 10:45:00'), 'Llega puntual', 40000, 1),
(3, 3, CONCAT(CURDATE(), ' 11:00:00'), CONCAT(CURDATE(), ' 11:20:00'), NULL,            20000, 1);

-- --------------------------------------------------------
-- Citas Servicios (pivot — mismas horas que las citas)
-- --------------------------------------------------------
INSERT IGNORE INTO `citas_servicios` (`id`, `cita_id`, `servicio_id`, `empleado_id`, `duracionM`, `fechaInicio`, `fechaFin`, `precio`) VALUES
(1, 1, 1, 1, 30, CONCAT(CURDATE(), ' 09:00:00'), CONCAT(CURDATE(), ' 09:30:00'), 25000),
(2, 2, 3, 2, 45, CONCAT(CURDATE(), ' 10:00:00'), CONCAT(CURDATE(), ' 10:45:00'), 40000),
(3, 3, 2, 1, 20, CONCAT(CURDATE(), ' 11:00:00'), CONCAT(CURDATE(), ' 11:20:00'), 20000);

-- --------------------------------------------------------
-- Ventas (hoy y dias anteriores para que los charts muestren datos)
-- --------------------------------------------------------
INSERT IGNORE INTO `ventas` (`id`, `cliente_id`, `empleado_id`, `fecha_venta`, `total`, `metodo_pago`, `observaciones`, `status`) VALUES
(1, 1, 1, CONCAT(CURDATE(), ' 09:45:00'),                         25000.00, 'Efectivo', NULL,             1),
(2, 4, 2, CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), ' 15:00:00'), 73000.00, 'Tarjeta',  'Primera compra', 1),
(3, 2, 1, CONCAT(DATE_SUB(CURDATE(), INTERVAL 2 DAY), ' 10:30:00'), 35000.00, 'Efectivo', NULL,             1),
(4, 3, 2, CONCAT(DATE_SUB(CURDATE(), INTERVAL 3 DAY), ' 14:00:00'), 70000.00, 'Tarjeta',  NULL,             1),
(5, 1, 1, CONCAT(DATE_SUB(CURDATE(), INTERVAL 4 DAY), ' 11:00:00'), 28000.00, 'Efectivo', NULL,             1);

-- --------------------------------------------------------
-- Ventas Productos (pivot)
-- --------------------------------------------------------
INSERT IGNORE INTO `ventas_productos` (`id`, `ventas_id`, `productos_id`, `cantidad`, `subtotal`) VALUES
(1, 1, 1, 1, 25000),
(2, 2, 3, 1, 45000),
(3, 2, 4, 1, 28000),
(4, 3, 2, 1, 35000),
(5, 4, 1, 1, 25000),
(6, 4, 3, 1, 45000),
(7, 5, 4, 1, 28000);

COMMIT;
