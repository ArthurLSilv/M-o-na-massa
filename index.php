<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Gestão de Produtos</title>
    <style>
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            margin: 0; 
            padding: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        .container { 
            max-width: 1000px; 
            margin: 0 auto; 
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 8px;
            font-weight: 500;
            color: #444;
        }
        input, select { 
            width: 100%; 
            padding: 10px; 
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76,175,80,0.2);
        }
        button { 
            background-color: #4CAF50; 
            color: white; 
            padding: 12px 20px; 
            border: none; 
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover { 
            background-color: #45a049; 
        }
        .produto { 
            border: 1px solid #eee; 
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            background: white;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .produto:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .error { 
            color: #d32f2f;
            background: #fde8e8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success { 
            color: #388e3c;
            background: #edf7ed;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        h1, h2, h3 { 
            color: #2c3e50;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 2.5em;
            text-align: center;
            margin-bottom: 30px;
        }
        h2 {
            font-size: 1.8em;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }
        h3 {
            font-size: 1.5em;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sistema de Gestão de Produtos</h1>
        
        <?php
        require_once 'CadastroProdutos.php';
        session_start();

        if (!isset($_SESSION['estoque'])) {
            $_SESSION['estoque'] = new Estoque();
        }

        // Adicionar Produto
        if (isset($_POST['adicionar'])) {
            $nome = $_POST['nome'];
            $preco = floatval($_POST['preco']);
            $quantidade = intval($_POST['quantidade']);
            $categoria = $_POST['categoria'];

            if ($nome && $preco > 0 && $quantidade >= 0) {
                $produto = new Produto($nome, $preco, $quantidade, $categoria);
                $_SESSION['estoque']->adicionarProduto($produto);
                echo "<p class='success'>Produto adicionado com sucesso!</p>";
            } else {
                echo "<p class='error'>Por favor, preencha todos os campos corretamente.</p>";
            }
        }

        // Remover Produto
        if (isset($_POST['remover'])) {
            $index = $_POST['index'];
            if ($_SESSION['estoque']->removerProduto($index)) {
                echo "<p class='success'>Produto removido com sucesso!</p>";
            } else {
                echo "<p class='error'>Produto não encontrado.</p>";
            }
        }
        ?>

        <h2>Adicionar Novo Produto</h2>
        <form method="POST">
            <div class="form-group">
                <label for="nome">Nome do Produto:</label>
                <input type="text" name="nome" required>
            </div>
            <div class="form-group">
                <label for="preco">Preço (R$):</label>
                <input type="number" name="preco" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" name="quantidade" min="0" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoria:</label>
                <select name="categoria">
                    <option value="Geral">Geral</option>
                    <option value="Eletrônicos">Eletrônicos</option>
                    <option value="Roupas">Roupas</option>
                    <option value="Alimentos">Alimentos</option>
                </select>
            </div>
            <button type="submit" name="adicionar">Adicionar Produto</button>
        </form>

        <h2>Produtos em Estoque</h2>
        <?php
        $_SESSION['estoque']->exibirProdutos();
        
        $valorTotal = $_SESSION['estoque']->calcularValorTotal();
        echo "<h3>Valor Total do Estoque: R$ " . number_format($valorTotal, 2, ',', '.') . "</h3>";
        
        echo "<h2>Produtos por Categoria</h2>";
        $categorias = ['Geral', 'Eletrônicos', 'Roupas', 'Alimentos'];
        foreach ($categorias as $categoria) {
            $_SESSION['estoque']->listarPorCategoria($categoria);
        }
        ?>
    </div>
</body>
</html>
