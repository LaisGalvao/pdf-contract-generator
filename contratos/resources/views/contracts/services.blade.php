<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>{{ $themeCss }}</style>
</head>
<body>
  @if(!empty($logo_url))
    <img src="{{ $logo_url }}" style="max-height:60px;margin-bottom:10px;">
  @endif

  <h1>Contrato de Prestação de Serviços</h1>

  <p><strong>Contratante:</strong> {{ $data['contratante_nome'] }} - CPF/CNPJ: {{ $data['contratante_doc'] }}</p>
  <p><strong>Contratado(a):</strong> {{ $data['contratado_nome'] }} - CPF/CNPJ: {{ $data['contratado_doc'] }}</p>

  <h3>Objeto</h3>
  <p>{{ $data['objeto'] }}</p>

  <h3>Prazo e Vigência</h3>
  <p>Início: {{ $data['inicio'] }} — Término: {{ $data['termino'] }}</p>

  <h3>Remuneração</h3>
  <p>Valor: R$ {{ $data['valor'] }} — Forma de pagamento: {{ $data['pagamento'] }}</p>

  <h3>Cláusulas</h3>
  <ul>
    <li>Confidencialidade conforme item 1.1;</li>
    <li>Propriedade intelectual conforme item 2.1;</li>
    <li>Rescisão com aviso prévio de {{ $data['aviso_previo_dias'] }} dias;</li>
  </ul>

  <p style="margin-top:40px;">{{ $data['cidade'] }}, {{ $data['data_assinatura'] }}</p>

  <div style="margin-top:60px;">
    <p>______________________________________</p>
    <p>{{ $data['contratante_nome'] }}</p>
  </div>
  <div style="margin-top:40px;">
    <p>______________________________________</p>
    <p>{{ $data['contratado_nome'] }}</p>
  </div>
</body>
</html>
