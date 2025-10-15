<?php
session_start();
require_once '../includes/database.php';

$alerta = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identificador = $_POST['matricula'] ?? $_POST['correo'] ?? '';
    $passwordIngresado = $_POST['password'] ?? '';

    if ($identificador !== '' && $passwordIngresado !== '') {
        $stmt = $db->prepare('SELECT identificador, nombre, apellido, password, tipo FROM alumnos WHERE identificador = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('s', $identificador);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado && $resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();
                if (password_verify($passwordIngresado, $usuario['password'])) {
                    $_SESSION['alumno'] = $usuario;
                    $_SESSION['tipo'] = $usuario['tipo'];
                    header('Location: ../index.php?op=1');
                    exit;
                } else {
                    $alerta[0] = 'Error';
                    $alerta[1] = 'Contraseña Incorrecta, inténtalo de nuevo';
                }
            } else {
                $alerta[0] = 'Error';
                $alerta[1] = 'Usuario no encontrado, inténtalo de nuevo';
            }

            $stmt->close();
        } else {
            $alerta[0] = 'Error';
            $alerta[1] = 'No se pudo preparar la consulta';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="/src/css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div class="login-container">
        
        <div class="left-panel">
            <div class="logo-bear">
                <img src="/src/img/oso_fime.jpg" alt="Oso FIME">
            </div>
        </div>

        <div class="right-panel">
            
            <div class="header-logo">
                <a href="https://www.fime.uanl.mx/tramites/educacion-continua/" target="_blank" rel="noopener noreferrer"><img src="/src/img/ecc_logo.jpg" alt="Logo UANL"></a>
            </div>

            <div class="middle-content">
                <form class="login-form" method="POST" action="">
                    <h2>Inicio de Sesión</h2>

                    <div class="tipo">
                        <label for="tipo">Tipo:</label>
                        <select name="tipo-usuario" id="tipo-usuario-select">
                            <option value="alumno" <?php echo (isset($_POST['tipo-usuario']) && $_POST['tipo-usuario'] == 'alumno') ? 'selected' : ''; ?>>Alumno UANL</option>
                            <option value="externo" <?php echo (isset($_POST['tipo-usuario']) && $_POST['tipo-usuario'] == 'externo') ? 'selected' : ''; ?>>Externo</option>
                        </select>
                    </div>
                
                    <label for="matricula" id="label-identificador">Ingrese su Matrícula:</label>
                    <input type="text" id="input-identificador" name="matricula" placeholder="Matrícula" value="<?php echo $_POST['matricula'] ?? $_POST['correo'] ?? ''; ?>" required>
                
                    <label for="password">Ingrese su Contraseña:</label>
                    <input type="password" id="password" name="password" placeholder="Contraseña" required>
                
                    <button type="submit">Iniciar Sesión</button>
                </form>

                <div class="acciones">
                    <a href="#">¿Ya estas registrado? Inicia Sesión</a>
                    <a href="registro.php">¿Aún no estas registrado? Regístrate</a>
                </div>
            </div>

            <div class="footer-logos">
                <div class="logo-placeholder">
                    <a href="https://www.uanl.mx/" target="_blank" rel="noopener noreferrer"><img src="/src/img/uanl_logo.jpg" alt="Logo UANL"></a>
                </div>
                <div class="logo-placeholder">
                    <a href="https://www.fime.uanl.mx/" target="_blank" rel="noopener noreferrer"><img src="/src/img/fime_logo.png" alt="Logo FIME"></a>
                </div>
            </div>

        </div>

    </div>
</body>
</html>

<?php // ... (código de alerta SweetAlert) ...
 if (!empty($alerta)): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: '<?php echo $alerta[0]; ?> ',
            text: '<?php echo $alerta[1]; ?>',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#0a4624' 
        });
    </script>
    <?php endif; 
?>


<script src="/src/js/registro.js"></script>





