<?php
require_once '../includes/database.php';
session_start();
$alerta = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="/src/css/registro.css">
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
                    <h2>Registro</h2>
                    
                    <div class="form-row-2-col">
                        <div class="form-col">
                            <label for="tipo-usuario-select">Tipo:</label>
                            <select name="tipo-usuario" id="tipo-usuario-select">
                                <option value="alumno" <?php echo (isset($_POST['tipo-usuario']) && $_POST['tipo-usuario'] == 'alumno') ? 'selected' : ''; ?>>Alumno UANL</option>
                                <option value="externo" <?php echo (isset($_POST['tipo-usuario']) && $_POST['tipo-usuario'] == 'externo') ? 'selected' : ''; ?>>Externo</option>
                            </select>
                        </div>
                        
                        <div class="form-col">
                            <label for="input-identificador" id="label-identificador">Ingrese su Matrícula:</label>
                        <input type="text" id="input-identificador" name="matricula" placeholder="Matrícula" value="<?php echo $_POST['matricula'] ?? $_POST['correo'] ?? ''; ?>"  required>
                        </div>
                    </div>

                    <div class="form-row-2-col">
                        <div class="form-col">
                            <label for="nombre">Ingrese su nombre:</label>
                            <input type="text" name="nombre" placeholder="Nombre(s)" value="<?php echo (isset($_POST['nombre'])) ? $_POST['nombre'] : ''; ?>"required>
                        </div>

                        <div class="form-col">
                            <label for="apellido">Ingrese su apellido:</label>
                            <input type="text" name="apellido" placeholder="Apellido(s)" value="<?php echo (isset($_POST['apellido'])) ? $_POST['apellido'] : ''; ?>" required>
                        </div>
                    </div>

                    <div class="form-row-2-col">
                        <div class="form-col">
                            <label for="password">Cree su Contraseña:</label>
                            <input type="password" id="password" name="password" placeholder="Contraseña" required>
                        </div>
                        
                        <div class="form-col">
                            <label for="password2">Confirme su Contraseña:</label>
                            <input type="password" id="password2" name="password2" placeholder="Confirme su contraseña" required>
                        </div>
                    </div>
                
                    <button type="submit">Registrarme</button>
                </form>

                <div class="acciones">
                    <a href="login.php">¿Ya estas registrado? Inicia Sesión</a>
                    <a href="#">¿Aún no estas registrado? Regístrate</a>
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

<?php 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Determinar si es 'matricula' o 'correo'
    $tipo_usuario = $_POST['tipo-usuario'] ?? 'alumno'; // Valor por defecto 'alumno'


    if ($tipo_usuario === 'externo') {
        $identificador = $_POST['correo'] ?? '';
        $campo_db = 'correo';
    } else {
        $identificador = $_POST['matricula'] ?? '';
        $campo_db = 'matricula';
    }

    $query = "SELECT * FROM alumnos WHERE identificador = '$identificador'"; 

    $password_ingresado = $_POST['password'];
    $password_confirmar = $_POST['password2'];

    $resultado = mysqli_query($db, $query);


    // Ya hay usuario con esta matricula o correo
    if ($resultado && $resultado->num_rows > 0) {

        if($campo_db == "matricula"){
            $alerta[1] = "Esta matrícula ya esta en uso, inténtalo de nuevo";
        } else if($campo_db == "correo"){
           $alerta[1] = "Este correo ya esta en uso, inténtalo de nuevo"; 
        }

        $alerta[0] = "Error";
        $alerta[2] = "error";

    }
    else {
        // no hay alguien con esta matricula o correo
        if($password_ingresado != $password_confirmar){
            $alerta[0] = "Error";
            $alerta[1] = "Las contraseña no coincide, inténtalo de nuevo";
            $alerta[2] = "error";
        } else if(strlen($password_ingresado) < 6 ){
            $alerta[0] = "Error";
            $alerta[1] = "Las contraseña debe contener al menos 6 carácteres, inténtalo de nuevo";
            $alerta[2] = "error";
        } else{ 
            //  bien
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $passwordHash = password_hash($password_ingresado, PASSWORD_DEFAULT);

            if($campo_db == "matricula"){
                $tipo = 1;
                $query = "INSERT INTO alumnos (identificador, nombre, apellido, password, tipo) VALUES ('$identificador', '$nombre', '$apellido','$passwordHash', '$tipo')";
            } else if($campo_db == "correo"){
                $tipo = 0;
                $query = "INSERT INTO alumnos (identificador, nombre, apellido, password, tipo) VALUES ('$identificador', '$nombre', '$apellido','$passwordHash', '$tipo')";
            }

            $resultado = mysqli_query($db, $query);

            if($resultado){

                $querySelect = "SELECT * FROM alumnos WHERE identificador = '$identificador'"; 

                $resultadoSelect = mysqli_query($db, $querySelect);
                $usuario = mysqli_fetch_assoc($resultadoSelect);

                $_SESSION['alumno'] = $usuario; 
                $_SESSION['tipo'] = $tipo;

                $alerta[0] = "Éxito";
                $alerta[1] = "Te has registrado correctamente";
                $alerta[2] = "success";
            }   else{
                $alerta[0] = "Error";
                $alerta[1] = "Hubo algún error durante el registro";
                $alerta[2] = "error"; 
            }

        }
    }
} 


if (!empty($alerta)): ?>
    <script>
        
        Swal.fire({
            icon: '<?php echo $alerta[2];?>',
            title: '<?php echo $alerta[0];?>',
            text: '<?php echo $alerta[1];?>',
            // El texto del botón se mantiene condicional
            confirmButtonText: '<?php echo ($alerta[2] == "error") ? "Aceptar" : "Iniciar Sesión"; ?>',
            confirmButtonColor: '#0a4624' 
        }).then((result) => { 
            if (result.isConfirmed && '<?php echo $alerta[2]; ?>' != 'error') {
                window.location.href = '../index.php?op=1'; 
            }
        });
    </script>
<?php endif; ?>

?>

<script src="/src/js/registro.js"></script>

</body>
</html>
