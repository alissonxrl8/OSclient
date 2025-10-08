<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Termo de Garantia</title>
<style>
body { font-family: DejaVu Sans, sans-serif; margin: 40px; }
h1 { text-align: center; color: #333; }
section { margin-bottom: 25px; }
p { line-height: 1.6; }
strong { color: #000; }
</style>
</head>
<body>
<h1>Termo de Garantia de Serviço</h1>

<section>
  <h3>Dados do Cliente</h3>
  <p><strong>Nome:</strong> {{ $cliente->nome }}</p>
  <p><strong>Contato:</strong> {{ $cliente->telefone ?? 'Não informado' }}</p>
</section>

<section>
  <h3>Dados do Serviço</h3>
  <p><strong>Serviço:</strong> {{ $ordem->servico ?? 'N/A' }}</p>
  <p><strong>Valor:</strong> R$ {{ number_format($ordem->valor ?? 0, 2, ',', '.') }}</p>
  <p><strong>Data Inicial:</strong> {{ $data_inicio }}</p>
  <p><strong>Validade:</strong> {{ $data_final }} ({{ $dias_garantia }} dias)</p>
</section>

<section>
  <h3>Condições da Garantia</h3>
  <p>Esta garantia cobre exclusivamente defeitos de execução ou materiais relacionados ao serviço prestado. 
  A garantia será anulada nos seguintes casos:</p>
  <ul>
    <li>Uso indevido do equipamento;</li>
    <li>Alterações ou reparos realizados por terceiros;</li>
    <li>Danos causados por quedas, líquidos ou descargas elétricas;</li>
    <li>Falta de manutenção adequada.</li>
  </ul>
</section>

<p style="margin-top:40px;">Assinatura do Responsável: ___________________________</p>

<p style="text-align:center; margin-top:60px;">Emitido em {{ now()->format('d/m/Y') }}</p>
</body>
</html>
