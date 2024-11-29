<?php
session_start();

$usuarios = [
    'admin' => '123456', // Usuário padrão para login
];

$error = ""; // Variável para exibir mensagens de erro
$success = ""; // Variável para exibir mensagem de sucesso

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($action == 'register') {
        // Armazenar novo usuário no cookie (apenas para fins didáticos)
        $usuariosSalvos = isset($_COOKIE['usuarios']) ? json_decode($_COOKIE['usuarios'], true) : [];
        $usuariosSalvos[$username] = $password;
        
        // Configura o cookie com o novo usuário (expira em 30 dias)
        setcookie('usuarios', json_encode($usuariosSalvos), time() + (30 * 24 * 60 * 60), "/");
        
        $success = "Usuário cadastrado com sucesso! Faça login.";
    } elseif ($action == 'login') {
        // Verificar login utilizando os usuários salvos no cookie
        $usuariosSalvos = isset($_COOKIE['usuarios']) ? json_decode($_COOKIE['usuarios'], true) : [];
        $todosUsuarios = array_merge($usuarios, $usuariosSalvos);
        
        if (isset($todosUsuarios[$username]) && $todosUsuarios[$username] == $password) {
            $_SESSION['logado'] = true;
            header('Location: ../page/index.php'); // Login bem-sucedido
            exit;
        } else {
            $error = "Usuário ou senha incorretos!"; // Define a mensagem de erro
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    <style>
        /* Estilos para a página de login */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .background-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .login-container {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
            text-align: center;
        }

        .form-container {
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
        }

        .error {
            color: #f04e30;
            margin-bottom: 10px;
        }

        .success {
            color: #4CAF50;
            margin-bottom: 10px;
        }

        input {
            margin: 10px 0;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
        }

        button {
            background-color: #f04e30;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #d0432b;
        }

        a {
            color: #f04e30;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Vídeo de fundo -->
    <video id="videoBG" autoplay muted loop >           
        <source src="../page/video-bg/cozinhando.mp4" type="video/mp4">
    </video>

    <div class="login-container">
        <div class="form-container">
            <!-- Mostrar a mensagem de sucesso após o cadastro -->
            <?php if ($success): ?>
                <h2 class="success"><?= htmlspecialchars($success) ?></h2>
                <a href="../index.php">Faça login</a>
                <br><br>
            <?php endif; ?>

            <!-- Formulário de Login -->
            <?php if (!$success): ?>
                <h2>Login</h2>
                <?php if ($error): ?>
                    <p class="error"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <form action="auth.php" method="POST">
                    <input type="hidden" name="action" value="login">
                    <input type="text" name="username" placeholder="Username" maxlength="12" required>
                    <input type="password" name="password" placeholder="Senha" required>
                    <button type="submit">Entrar</button>
                </form>
                <br>
                <button onclick="window.location.href='../cadastro.php'">Ir para Cadastro</button>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
