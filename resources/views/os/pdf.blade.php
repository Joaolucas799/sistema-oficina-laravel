

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #1f2430;
            margin: 0;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #0c1f33;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }
        .header-logo {
            display: table-cell;
            width: 90px;
            vertical-align: middle;
        }
        .header-logo img {
            width: 75px;
            height: 75px;
        }
        .header-empresa {
            display: table-cell;
            vertical-align: middle;
            padding-left: 12px;
        }
        .header-empresa h1 {
            font-size: 20px;
            margin: 0;
            color: #0c1f33;
            letter-spacing: 0.3px;
        }
        .header-empresa p {
            margin: 2px 0 0;
            font-size: 10.5px;
            color: #666;
        }
        .header-os {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 160px;
        }
        .header-os .numero {
            display: inline-block;
            background: #0c1f33;
            color: #85B7EB;
            font-size: 16px;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 6px;
        }
        .header-os .data {
            margin-top: 6px;
            font-size: 10px;
            color: #888;
        }

        .info-box {
            display: table;
            width: 100%;
            margin-bottom: 16px;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        .info-card {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 12px;
        }
        .info-card .titulo {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #888;
            margin: 0 0 6px;
        }
        .info-card p {
            margin: 2px 0;
            font-size: 11.5px;
        }
        .info-card p strong {
            color: #0c1f33;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .observacoes {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 16px;
            font-size: 11.5px;
            background: #fafafa;
        }
        .observacoes .titulo {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #888;
            margin: 0 0 4px;
        }

        table.itens {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        table.itens th {
            background: #0c1f33;
            color: #fff;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 8px 10px;
            text-align: left;
        }
        table.itens td {
            padding: 8px 10px;
            font-size: 11.5px;
            border-bottom: 1px solid #eee;
        }
        table.itens tr:nth-child(even) td {
            background: #f7f8fa;
        }
        .tipo-tag {
            font-size: 9px;
            color: #999;
        }

        .totais {
            display: table;
            width: 100%;
            margin-top: 14px;
        }
        .totais-esq {
            display: table-cell;
            width: 60%;
            font-size: 9.5px;
            color: #999;
            vertical-align: bottom;
        }
        .totais-dir {
            display: table-cell;
            width: 40%;
            text-align: right;
        }
        .totais-dir .valor-total {
            background: #0c1f33;
            color: #85B7EB;
            display: inline-block;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
        }
        .totais-dir p {
            margin: 0 0 4px;
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 8px;
            text-align: center;
            font-size: 9px;
            color: #aaa;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-logo">
            <img src="{{ public_path('img/logo.png') }}">
        </div>
        <div class="header-empresa">
            <h1>Cascavel</h1>
            <p>Mecânica Auto Elétrica</p>
            <p>ENDEREÇO A DEFINIR &nbsp;|&nbsp; (34) 99983-0791</p>
        </div>
        <div class="header-os">
            <div class="numero">OS Nº {{ str_pad($os->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div class="data">Emitida em {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <div class="info-box">
        <div class="info-col">
            <div class="info-card">
                <p class="titulo">Cliente</p>
                <p><strong>{{ $os->cliente->nome }}</strong></p>
                <p>{{ $os->cliente->telefone ?? '—' }}</p>
                <p>{{ $os->cliente->cpf_cnpj ?? '' }}</p>
            </div>
        </div>
        <div class="info-col" style="padding-right:0; padding-left:10px;">
            <div class="info-card">
                <p class="titulo">Veículo</p>
                <p><strong>{{ $os->veiculo->marca }} {{ $os->veiculo->modelo }}</strong></p>
                <p>Placa: {{ $os->veiculo->placa }} &nbsp;|&nbsp; Ano: {{ $os->veiculo->ano ?? '—' }}</p>
                <p>
                    Status:
                    @php
                        $coresPdf = [
                            'aberta' => 'background:#e6f1fb;color:#185FA5;',
                            'em_andamento' => 'background:#faeeda;color:#ba7517;',
                            'concluida' => 'background:#eaf3de;color:#3B6D11;',
                            'cancelada' => 'background:#fbe4e4;color:#A32D2D;',
                        ];
                    @endphp
                    <span class="status-badge" style="{{ $coresPdf[$os->status] }}">
                        {{ str_replace('_', ' ', $os->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    @if ($os->observacoes)
        <div class="observacoes">
            <p class="titulo">Observações</p>
            <p>{{ $os->observacoes }}</p>
        </div>
    @endif

    <table class="itens">
        <thead>
            <tr>
                <th style="width:45%">Descrição</th>
                <th style="width:15%">Tipo</th>
                <th style="width:10%; text-align:right;">Qtd</th>
                <th style="width:15%; text-align:right;">Valor unit.</th>
                <th style="width:15%; text-align:right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($os->itens as $item)
                <tr>
                    <td>{{ $item->nomeItem() }}</td>
                    <td><span class="tipo-tag">{{ $item->produto_id ? 'PEÇA' : 'SERVIÇO' }}</span></td>
                    <td style="text-align:right;">{{ $item->quantidade }}</td>
                    <td style="text-align:right;">R$ {{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                    <td style="text-align:right;">R$ {{ number_format($item->quantidade * $item->valor_unitario, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totais">
        <div class="totais-esq">
            Contato: (34) 99983-0791
        </div>
        <div class="totais-dir">
            <p>Valor total</p>
            <div class="valor-total">R$ {{ number_format($os->valor_total, 2, ',', '.') }}</div>
        </div>
    </div>

    <div class="footer">
        Mecânica Auto Elétrica Cascavel — (34) 99983-0791 — Documento sem valor fiscal
    </div>

</body>
</html>