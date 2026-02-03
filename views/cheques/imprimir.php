<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cheque - <?php echo $data['cheque']['numero_cheque']; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10px; background: #fff; }
        
        .cheque {
            width: 100%;
            max-width: 260mm;
            height: 155mm;
            border: 2px solid #000;
            padding: 6mm 8mm;
            position: relative;
            background: #fff;
        }
        
        .cheque.anulado {
            background: repeating-linear-gradient(
                45deg,
                #ffcccc,
                #ffcccc 10px,
                #fff 10px,
                #fff 20px
            );
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(255, 0, 0, 0.1);
            font-weight: bold;
            z-index: 0;
            display: none;
            pointer-events: none;
        }
        
        .cheque.anulado .watermark { display: block; }
        
        .info-anulacion {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            padding: 12px 20px;
            border: 3px solid #cc0000;
        }
        
        .cheque.anulado .info-anulacion { display: block; }
        
        .anulado-titulo { font-size: 28px; color: #cc0000; font-weight: bold; }
        .anulado-motivo { color: #cc0000; font-size: 11px; margin-top: 6px; }
        .anulado-fecha { color: #666; font-size: 9px; margin-top: 4px; }
        
        .header { display: flex; justify-content: space-between; margin-bottom: 6px; padding-bottom: 4px; border-bottom: 2px solid #000; }
        .header-izq { width: 40%; }
        .header-der { width: 60%; text-align: right; }
        .empresa-nombre { font-weight: bold; font-size: 11px; }
        .empresa-dir { font-size: 8px; color: #666; }
        
        .fila { display: flex; margin-bottom: 5px; }
        .col { flex: 1; }
        .col-label { font-size: 7px; color: #666; display: block; }
        
        .serie { text-align: right; font-weight: bold; font-size: 12px; background: #f0f0f0; padding: 3px 8px; display: inline-block; float: right; }
        
        .datos-principales { border: 1px solid #000; padding: 6px 8px; margin-bottom: 5px; }
        .datos-row { display: flex; justify-content: space-between; margin-bottom: 3px; }
        .datos-row:last-child { margin-bottom: 0; }
        .datos-label { font-size: 7px; color: #666; }
        .datos-valor { font-weight: bold; font-size: 10px; }
        
        .proveedor-box { border: 1px solid #000; padding: 4px 6px; font-weight: bold; font-size: 10px; min-height: 18px; }
        
        .monto-box { border: 1px solid #000; padding: 4px 6px; }
        .monto-letras { font-size: 8px; font-style: italic; margin-bottom: 2px; min-height: 12px; }
        .monto-numero { font-size: 12px; font-weight: bold; text-align: left; }
        
        .concepto-box { border: 1px solid #000; padding: 3px 6px; font-size: 9px; min-height: 15px; }
        
        .banco-footer { font-size: 8px; color: #666; margin-top: 4px; display: flex; gap: 15px; }
        .banco-item { display: flex; gap: 5px; }
        .banco-item strong { color: #000; }
        
        .partida { margin-top: 8px; }
        .partida-titulo { font-size: 9px; font-weight: bold; text-align: center; margin-bottom: 3px; }
        
        .partida-tabla { width: 100%; border-collapse: collapse; font-size: 8px; }
        .partida-tabla th, .partida-tabla td { border: 1px solid #000; padding: 2px 4px; text-align: center; }
        .partida-tabla th { background: #e0e0e0; font-weight: bold; }
        
        .partida-totales { text-align: right; font-weight: bold; font-size: 9px; margin-top: 3px; }
        
        .footer { display: flex; justify-content: space-between; }
        .firma { width: 32%; text-align: center; }
        .firma-linea { border-top: 1px solid #000; height: 30px; margin-bottom: 2px; }
        .firma-label { font-size: 8px; color: #666; }
    </style>
</head>
<body>
    <?php
    $empresa = isset($data['empresa']) ? $data['empresa'] : [
        'nombre' => 'MAQUINARIA AGRÍCOLA SA DE CV',
        'direccion' => 'San Salvador'
    ];
    $cheque = $data['cheque'];
    $detalle = isset($data['detalle']) ? $data['detalle'] : [];
    $montoLetras = numeroALetras($cheque['monto'], 'DÓLARES', 'CENTAVOS');
    ?>
    
    <div class="cheque <?php echo $cheque['estado'] == 'anulado' ? 'anulado' : ''; ?>">
        <div class="watermark">ANULADO</div>
        
        <?php if ($cheque['estado'] == 'anulado'): ?>
        <div class="info-anulacion">
            <div class="anulado-titulo">ANULADO</div>
            <div class="anulado-motivo"><?php echo $cheque['motivo_anulacion'] ?? ''; ?></div>
            <div class="anulado-fecha"><?php echo $cheque['fecha_anulacion'] ? 'Fecha: ' . date('d/m/Y H:i', strtotime($cheque['fecha_anulacion'])) : ''; ?></div>
            <?php if (!empty($cheque['anulado_por_nombre'])): ?>
            <div class="anulado-fecha">Anulado por: <?php echo $cheque['anulado_por_nombre']; ?></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Header -->
        <div class="header">
            <div class="header-izq">
                <span class="col-label">LUGAR</span>
                <span><?php echo $empresa['direccion']; ?></span>
                <span class="col-label" style="margin-top: 4px;">FECHA</span>
                <span><?php echo date('d/m/Y', strtotime($cheque['fecha_emision'])); ?></span>
            </div>
            <div class="header-der">
                <div class="empresa-nombre"><?php echo $empresa['nombre']; ?></div>
                <div class="empresa-dir"><?php echo $empresa['direccion']; ?></div>
            </div>
        </div>
        
        <!-- Serie -->
        <div class="serie">CHEQUE No. <?php echo $cheque['numero_cheque']; ?></div>
        <div style="clear:both;"></div>
        
        <!-- Banco -->
        <div class="banco-footer">
            <div class="banco-item">
                <strong>BANCO:</strong> <?php echo $cheque['banco']; ?>
            </div>
            <div class="banco-item">
                <strong>CUENTA:</strong> <?php echo !empty($cheque['numero_cuenta_bancaria']) ? $cheque['numero_cuenta_bancaria'] : 'No asignada'; ?>
            </div>
        </div>
        
        <!-- Proveedor -->
        <div class="fila" style="margin-top: 6px;">
            <span class="col-label" style="display:block; margin-bottom: 2px;">PÁGUESE A LA ORDEN DE</span>
        </div>
        <div class="proveedor-box"><?php echo $cheque['proveedor']; ?> (RUC: <?php echo $cheque['ruc']; ?>)</div>
        
        <!-- Monto -->
        <div class="fila" style="margin-top: 6px;">
            <span class="col-label" style="display:block; margin-bottom: 2px;">LA CANTIDAD DE</span>
        </div>
        <div class="monto-box">
            <div class="monto-letras"><?php echo $montoLetras; ?></div>
            <div class="monto-numero">US$ <?php echo number_format($cheque['monto'], 2); ?></div>
        </div>
        
        <!-- Concepto -->
        <div class="fila" style="margin-top: 6px;">
            <span class="col-label" style="display:block; margin-bottom: 2px;">CONCEPTO / DETALLE DEL PAGO</span>
        </div>
        <div class="concepto-box"><?php echo $cheque['concepto']; ?></div>
        
        <!-- Partida Contable -->
        <?php if (!empty($detalle)): ?>
        <div class="partida">
            <div class="partida-titulo">PARTIDA CONTABLE</div>
            <table class="partida-tabla">
                <thead>
                    <tr>
                        <th>CUENTA</th>
                        <th>TIPO</th>
                        <th>MONTO US$</th>
                        <th>CONCEPTO</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalDebe = 0;
                    $totalHaber = 0;
                    foreach ($detalle as $row): 
                        if ($row['tipo'] == 'Debe') $totalDebe += $row['monto'];
                        else $totalHaber += $row['monto'];
                    ?>
                    <tr>
                        <td><?php echo $row['cuenta_contable']; ?></td>
                        <td><?php echo $row['tipo']; ?></td>
                        <td><?php echo number_format($row['monto'], 2); ?></td>
                        <td style="text-align: left;"><?php echo $row['concepto']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="partida-totales">
                DEBE: <?php echo number_format($totalDebe, 2); ?> | HABER: <?php echo number_format($totalHaber, 2); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Firmas -->
        <table width="100%" style="margin-top: 15px;">
            <tr>
                <td width="33%" align="center">
                    <div style="border-top: 1px solid #000; height: 40px;"></div>
                    <div style="font-size: 8px; color: #666;">REVISO</div>
                </td>
                <td width="33%" align="center">
                    <div style="border-top: 1px solid #000; height: 40px;"></div>
                    <div style="font-size: 8px; color: #666;">AUTORIZO</div>
                </td>
                <td width="34%" align="center">
                    <div style="border-top: 1px solid #000; height: 40px;"></div>
                    <div style="font-size: 8px; color: #666;">RECIBI CONFORME</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

<?php
function numeroALetras($numero, $moneda, $centavos) {
    $numero = round($numero, 2);
    $entero = intval($numero);
    $centavos = round(($numero - $entero) * 100);
    
    $unidades = ['', 'UNO', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
    $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
    $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];
    
    function convertirMillones($n, $unidades, $decenas, $centenas) {
        if ($n >= 1000000) {
            return convertirMillones(intval($n / 1000000), $unidades, $decenas, $centenas) . ' MILLONES ' . convertirMiles($n % 1000000, $unidades, $decenas, $centenas);
        } else {
            return convertirMiles($n, $unidades, $decenas, $centenas);
        }
    }
    
    function convertirMiles($n, $unidades, $decenas, $centenas) {
        if ($n >= 1000) {
            $miles = intval($n / 1000);
            if ($miles == 1) {
                return 'MIL ' . convertirCientos($n % 1000, $unidades, $decenas, $centenas);
            } else {
                return convertirCientos($miles, $unidades, $decenas, $centenas) . ' MIL ' . convertirCientos($n % 1000, $unidades, $decenas, $centenas);
            }
        } else {
            return convertirCientos($n, $unidades, $decenas, $centenas);
        }
    }
    
    function convertirCientos($n, $unidades, $decenas, $centenas) {
        if ($n >= 100) {
            return $centenas[intval($n / 100)] . ' ' . convertirDecenas($n % 100, $unidades, $decenas);
        } else {
            return convertirDecenas($n, $unidades, $decenas);
        }
    }
    
    function convertirDecenas($n, $unidades, $decenas) {
        if ($n >= 10) {
            if ($n >= 10 && $n < 20) {
                $especiales = ['DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE'];
                return $especiales[$n - 10];
            } else {
                return $decenas[intval($n / 10)] . ($n % 10 > 0 ? ' Y ' . $unidades[$n % 10] : '');
            }
        } else {
            return $unidades[$n];
        }
    }
    
    $letras = '';
    if ($entero == 0) {
        $letras = 'CERO';
    } else {
        $letras = convertirMillones($entero, $unidades, $decenas, $centenas);
    }
    
    if ($centavos > 0) {
        $letras .= ' CON ' . convertirDecenas($centavos, $unidades, $decenas) . ' ' . $centavos;
    }
    
    return $letras . ' ' . $moneda . ($centavos == 1 ? '' : '');
}
?>
