<?php
class Produto {
    private $nome;
    private $preco;
    private $quantidade;
    private $categoria;

    public function __construct($nome, $preco, $quantidade, $categoria = 'Geral') {
        $this->nome = $nome;
        $this->preco = $preco;
        $this->quantidade = $quantidade;
        $this->categoria = $categoria;
    }

    public function exibirInfo() {
        echo "Produto: {$this->nome}<br>";
        echo "Preço: R$ {$this->preco}<br>";
        echo "Quantidade: {$this->quantidade}<br>";
        echo "Categoria: {$this->categoria}<br><br>";
    }

    public function aplicarDesconto($percentual) {
        if ($percentual > 0 && $percentual <= 100) {
            $this->preco = $this->preco * (1 - $percentual/100);
            return true;
        }
        return false;
    }

    public function atualizarQuantidade($quantidade) {
        if ($quantidade >= 0) {
            $this->quantidade = $quantidade;
            return true;
        }
        return false;
    }

    public function getPreco() {
        return $this->preco;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

    public function getCategoria() {
        return $this->categoria;
    }
}

class Estoque {
    private $produtos = [];

    public function adicionarProduto(Produto $produto) {
        $this->produtos[] = $produto;
    }

    public function removerProduto($index) {
        if (!is_numeric($index) || $index < 0) {
            return false;
        }
        if (isset($this->produtos[$index])) {
            unset($this->produtos[$index]);
            $this->produtos = array_values($this->produtos); // Reindex array
            return true;
        }
        return false;
    }

    public function exibirProdutos() {
        if (empty($this->produtos)) {
            echo "Nenhum produto no estoque.<br>";
            return;
        }
        foreach ($this->produtos as $index => $produto) {
            echo "ID: {$index} - ";
            $produto->exibirInfo();
        }
    }

    public function calcularValorTotal() {
        $total = 0;
        foreach ($this->produtos as $produto) {
            $total += $produto->getPreco() * $produto->getQuantidade();
        }
        return $total;
    }

    public function listarPorCategoria($categoria) {
        echo "Produtos na categoria {$categoria}:<br>";
        $encontrou = false;
        foreach ($this->produtos as $index => $produto) {
            if ($produto->getCategoria() == $categoria) {
                echo "ID: {$index} - ";
                $produto->exibirInfo();
                $encontrou = true;
            }
        }
        if (!$encontrou) {
            echo "Nenhum produto encontrado nesta categoria.<br>";
        }
    }
}
?>
