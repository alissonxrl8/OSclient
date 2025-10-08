<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Ordem de Serviço #{{ $ordem->id }}</title>
<style>
  body { font-family: Arial, sans-serif; font-size: 14px; }
  h1 { text-align: center; }
  table { width: 100%; border-collapse: collapse; margin-top: 20px; }
  th, td { border: 1px solid #333; padding: 8px; text-align: left; }
  th { background: #f2f2f2; }
</style>
</head>
<body>
<h1>Ordem de Serviço #{{ $ordem->id }}</h1>

<p><strong>Cliente:</strong> {{ $cliente->nome }}<br>
<strong>Modelo:</strong> {{ $ordem->modelo }}<br>
<strong>Data:</strong> {{ $data_ordem }}<br>
<strong>Observações:</strong> {{ $ordem->obs ?? 'Nenhuma' }}</p>

<table>
<tr>
<th>Serviço</th>
<th>Descrição</th>
<th>Preço</th>
<th>Preço Pago</th>
<th>Dias Garantia</th>
</tr>
<tr>
<td>{{ $servico->servico }}</td>
<td>{{ $servico->descricao }}</td>
<td>R$ {{ number_format($ordem->preco,2,',','.') }}</td>
<td>R$ {{ number_format($ordem->preco_pago,2,',','.') }}</td>
<td>{{ $ordem->dias_garantia }}</td>
</tr>
</table>

</body>
</html>
