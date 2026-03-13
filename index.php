<?php
session_start();

// Simulação simples de usuário (substituir por banco de dados depois)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Cadastro
    if (isset($_POST['cadastro'])) {
        $_SESSION['usuario'] = $_POST['nome'];
        header("Location: index.php");
        exit();
    }

    // Login
    if (isset($_POST['login'])) {
        $_SESSION['usuario'] = $_POST['email'];
        header("Location: index.php");
        exit();
    }

    // Logout
    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>GLS Summer</title>
    <link rel="stylesheet" href="gls1.css">
</head>

<body>

    <header>
        <img src="imagens/logo2.png" width="70">

        <nav>
            <button onclick="showSection('categorias')">Categorias</button>

            <?php if (isset($_SESSION['usuario'])): ?>
                <span>Olá, <?php echo $_SESSION['usuario']; ?> 👋</span>
                <form method="POST" style="display:inline;">
                    <button type="submit" name="logout">Sair</button>
                </form>
            <?php else: ?>
                <button onclick="showSection('login')">Login</button>
            <?php endif; ?>

            <button class="cart-btn" onclick="showSection('carrinho')">
                🛒 <span id="cart-count">0</span>
            </button>
        </nav>
    </header>

    <main>

        <!-- HOME -->
        <section id="home" class="active">
    <div class="hero">
        <h1>Verão 2026</h1>
        <p>Sinta a brisa do mar com estilo.</p>
        <button onclick="showSection('categorias')">Ver Coleção</button>
    </div>
    <div id="dashboard-vendas" class="dashboard-container"></div>
</section>

        <!-- LOGIN -->
        <section id="login" class="hidden">
            <div class="form-container">
                <h2>Login</h2>
                <form method="POST">
                    <input type="email" name="email" placeholder="E-mail" required>
                    <input type="password" name="senha" placeholder="Senha" required>
                    <button class="btn-main" type="submit" name="login">Entrar</button>
                </form>
                <p>Não tem conta?
                    <a href="#" onclick="showSection('cadastro')">Cadastre-se</a>
                </p>
            </div>
        </section>

        <!-- CADASTRO -->
        <section id="cadastro" class="hidden">
            <div class="form-container">
                <h2>Cadastro</h2>
                <form method="POST">
                    <input type="text" name="nome" placeholder="Nome Completo" required>
                    <input type="email" name="email" placeholder="E-mail" required>
                    <input type="password" name="senha" placeholder="Crie uma Senha" required>
                    <button class="btn-main" type="submit" name="cadastro">Criar Conta</button>
                </form>
                <p>Já tem conta?
                    <a href="#" onclick="showSection('login')">Fazer Login</a>
                </p>
            </div>
        </section>

        <!-- CATEGORIAS -->
        <section id="categorias" class="hidden">
    <div class="filter-bar">
        <button onclick="displayProducts('all')">Todos</button>
        <button onclick="displayProducts('banho')">Roupas de Banho</button>
        <button onclick="displayProducts('saidas')">Saídas de Praia</button>
        <button onclick="displayProducts('calcados')">Calçados</button>
        <button onclick="displayProducts('acessorios')">Acessórios</button>
    </div>
    <div id="product-grid" class="product-grid"></div>
</section>

        <!-- CARRINHO -->
        <section id="carrinho" class="hidden">
            <?php if (isset($_SESSION['usuario'])): ?>
                <h2>Seu Carrinho</h2>
                <div id="cart-items"></div>

                <div class="checkout-container">
                    <div class="checkout-section">
                        <h3>📍 Endereço de Entrega</h3>
                        <input type="text" id="cep" placeholder="CEP" onblur="validarFrete()">
                        <input type="text" id="rua" placeholder="Rua/Avenida">
                        <input type="number" id="numero" placeholder="Número">
                    </div>

                    <div class="checkout-section">
                        <h3>💳 Pagamento</h3>
                        <select id="metodo-pagamento" onchange="toggleCartao()">
                            <option value="pix">Pix (5% OFF)</option>
                            <option value="boleto">Boleto Bancário</option>
                            <option value="debito">Cartão de Débito</option>
                            <option value="credito">Cartão de Crédito</option>
                        </select>

                        <div id="detalhes-cartao" class="hidden">
                            <select id="parcelas" onchange="updateCart()"></select>
                        </div>
                    </div>
                </div>

                <div class="cart-summary">
                    <p>Subtotal: <span id="subtotal-price">R$ 0,00</span></p>
                    <p id="frete-info">Frete: R$ 20,00</p>
                    <p id="desconto-info" style="color: green;"></p>
                    <hr>
                    <p class="total-final">Total: <span id="total-price">R$ 0,00</span></p>
                    <div id="parcela-display" style="font-weight: bold; color: #010845;"></div>
                    <button class="btn-main" onclick="finalizePurchase()">Finalizar Compra</button>
                </div>
            <?php else: ?>
                <h2>Você precisa estar logado para acessar o carrinho.</h2>
                <button onclick="showSection('login')" class="btn-main">Fazer Login</button>
            <?php endif; ?>
        </section>

        <!-- COMPRA FINALIZADA -->
        <section id="finalizado" class="hidden">
            <div class="success-message">
                <h2>✨ Compra Realizada!</h2>
                <p>Obrigada por escolher a GLS Moda Praia.</p>
                <button class="btn-main" onclick="showSection('home')">
                    Voltar ao Início
                </button>
            </div>
        </section>

    </main>

    <script src="gls1.js"></script>
</body>

</html>