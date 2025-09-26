# Instalação para análise:

instruções feitas para a interface do phpMyAdmin em inglês *


1. Extraia os conteúdos da pasta compactada em C:\xampp\htdocs (Diretório do index.php será C:\xampp\htdocs\php_galeria\index.php)
2. Execute xampp-control.exe e ative os módulos Apache e MySQL
3. Clique no botão "Admin" do módulo MySQL para abrir o visualizador do banco de dados
4. Na janela do phpMyAdmin selecione "New" na coluna da esquerda da interface, e sob a seção "Create database" insira "oficina_db" e clique "Create"
5. Acessse a aba "SQL" dentro do banco de dados "oficina_db", e dentro da caixa de comando execute as seguintes linhas antes e depois dos traços:
```
CREATE TABLE `sobre` (
  `id` varchar(12) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descricao` text NOT NULL,
  `foto_path` varchar(255) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE `sobre`
  ADD PRIMARY KEY (`id`);
```

6. Aperte em "Go" para executar os comandos, o banco de dados ficará pronto para execução das páginas php
7. Se desejar analizar as entidades do banco de dados, basta clicar em "sobre" na coluna da esquerda sob a seção "oficina_db", e selecionar a aba "Structure"
8. retorne ao diretório C:\xampp\htdocs\php_galeria e abra o arquivo index.php com o seu navegador de preferência


> [!IMPORTANT]
> Mantenha os módulos Apache e MySQL rodando enquanto testar as páginas

Extras:
- se desejar, há uma pasta de fotos para teste das funções de upload localizadas em C:\xampp\htdocs\php_galeria\samples
- arquivos usados em upload serão salvos em C:\xampp\htdocs\php_galeria\uploads


Feito por:
THIAGO MIRANDA DE OLIVEIRA © 2025
