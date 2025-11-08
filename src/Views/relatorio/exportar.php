<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Livros por Autor</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        .autor-group { background-color: #ecf0f1; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Livros por Autor</h1>
        <p>Gerado em: <?= date('d/m/Y H:i:s') ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Autor</th>
                <th>Título</th>
                <th>Editora</th>
                <th>Edição</th>
                <th>Ano</th>
                <th>Valor</th>
                <th>Assuntos</th>
                <th>Outros Autores</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $autorAtual = '';
            foreach ($dados as $row): 
                if ($row['NomeAutor'] !== $autorAtual):
                    $autorAtual = $row['NomeAutor'];
            ?>
                <tr class="autor-group">
                    <td colspan="8"><?= htmlspecialchars($autorAtual) ?></td>
                </tr>
            <?php endif; ?>
                <tr>
                    <td></td>
                    <td><?= htmlspecialchars($row['Titulo']) ?></td>
                    <td><?= htmlspecialchars($row['Editora']) ?></td>
                    <td><?= htmlspecialchars($row['Edicao']) ?>ª</td>
                    <td><?= htmlspecialchars($row['AnoPublicacao']) ?></td>
                    <td>R$ <?= number_format($row['Valor'], 2, ',', '.') ?></td>
                    <td><?= htmlspecialchars($row['Assuntos'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['OutrosAutores'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
