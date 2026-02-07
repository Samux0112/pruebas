<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Remesa - <?php echo $data['remesa']['id'] ?? ''; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10px; background: #fff; }
        
        .documento {
            width: 100%;
            max-width: 200mm;
            height: 260mm;
            border: 2px solid #000;
            padding: 5mm 8mm;
            margin: 0 auto;
            position: relative;
            background: #fff;
        }
        
        .documento.anulado {
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
            font-size: 80px;
            color: rgba(255, 0, 0, 0.1);
            font-weight: bold;
            z-index: 0;
            display: none;
            pointer-events: none;
        }
        
        .documento.anulado .watermark { display: block; }
        
        .info-anulacion {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 25px;
            border: 3px solid #cc0000;
        }
        
        .documento.anulado .info-anulacion { display: block; }
        
        .anulado-titulo { font-size: 30px; color: #cc0000; font-weight: bold; }
        .anulado-motivo { color: #cc0000; font-size: 12px; margin-top: 8px; }
        .anulado-fecha { color: #666; font-size: 10px; margin-top: 5px; }
        
        .empresa-nombre { 
            font-weight: bold; 
            font-size: 16px; 
            text-align: center; 
            margin-bottom: 3mm;
        }
        
        .documento-titulo {
            font-weight: bold; 
            font-size: 14px; 
            text-align: center; 
            margin-bottom: 3mm;
            text-transform: uppercase;
            color: #333;
        }
        
        .header-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3mm;
            font-size: 9px;
        }
        
        .header-right { 
            font-weight: bold; 
            font-size: 12px;
        }
        
        .info-section {
            margin: 2mm 0;
            font-size: 9px;
        }
        
        .info-box { 
            border: 1px solid #000; 
            padding: 2mm 3mm; 
            font-weight: bold; 
            font-size: 10px; 
            margin: 1mm 0;
            min-height: 6mm;
        }
        
        .label { font-weight: bold; color: #333; }
        
        .partida-info-mejorada {
            margin: 4mm 0;
            font-size: 10px;
            padding: 2mm;
            background: #f5f5f5;
            border-left: 3px solid #333;
        }
        
        .partida-row {
            margin-bottom: 1.5mm;
            display: flex;
            align-items: baseline;
        }
        
        .partida-row:last-child { margin-bottom: 0; }
        
        .partida-label {
            font-weight: bold;
            color: #333;
            min-width: 120px;
        }
        
        .partida-value { color: #000; }
        
        .partida-tabla { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 8px;
            margin-top: 2mm;
        }
        
        .partida-tabla th, .partida-tabla td { 
            border: 1px solid #000; 
            padding: 1mm 2mm; 
            text-align: center; 
        }
        .partida-tabla th { 
            background: #e0e0e0; 
            font-weight: bold; 
        }
        
        .footer-firmas { 
            display: table; 
            width: 100%; 
            margin-top: 8mm; 
            table-layout: fixed; 
        }
        
        .firma-cell { 
            display: table-cell; 
            text-align: center; 
            padding: 0 2mm;
        }
        
        .firma-linea {
            border-top: 1px solid #000;
            margin-bottom: 1mm;
            padding-top: 1mm;
        }
        
        .firma-texto { font-size: 7px; }
        .firma-label { font-size: 8px; font-weight: bold; margin-top: 8px; }
        
        .tipo-documento {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 2mm;
        }
        .tipo-documento.remesa { background: #17a2b8; color: white; }
        .tipo-documento.transferencia { background: #28a745; color: white; }
    </style>
</head>
<body>
    <?php
    $remesa = $data['remesa'] ?? [];
    $detalle = isset($data['detalle']) ? $data['detalle'] : [];
    $empresa = isset($data['empresa']) ? $data['empresa'] : [
        'nombre' => 'FABIANSCORP',
        'direccion' => 'San Salvador'
    ];
    ?>
    
    <div class="documento <?php echo ($remesa['estado'] ?? '') == 'anulado' ? 'anulado' : ''; ?>">
        <div class="watermark">ANULADO</div>
        
        <?php if (($remesa['estado'] ?? '') == 'anulado'): ?>
        <div class="info-anulacion">
            <div class="anulado-titulo">ANULADO</div>
            <div class="anulado-motivo"><?php echo $remesa['motivo_anulacion'] ?? ''; ?></div>
            <div class="anulado-fecha"><?php echo !empty($remesa['fecha_anulacion']) ? 'Fecha: ' . date('d/m/Y H:i', strtotime($remesa['fecha_anulacion'])) : ''; ?></div>
            <?php if (!empty($remesa['anulado_por_nombre'])): ?>
            <div class="anulado-fecha">Anulado por: <?php echo $remesa['anulado_por_nombre']; ?></div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Empresa centrada -->
        <div class="empresa-nombre"><?php echo strtoupper($empresa['nombre']); ?></div>
        
        <!-- Título del documento -->
        <div class="documento-titulo">Remesa / Transferencia</div>
        
        <?php 
        $tipoClase = $remesa['tipo_transaccion'] ?? 'remesa';
        $tipoTexto = ($remesa['tipo_transaccion'] ?? 'remesa') == 'remesa' ? 'REMESA' : 'TRANSFERENCIA';
        ?>
        <div class="tipo-documento <?php echo $tipoClase; ?>"><?php echo $tipoTexto; ?></div>
        
        <!-- Header con fecha y monto -->
        <div class="header-info">
            <div>
                <div>LUGAR Y FECHA: <?php echo $empresa['direccion'] . ', ' . (!empty($remesa['fecha_creacion']) ? date('d/m/Y', strtotime($remesa['fecha_creacion'])) : ''); ?></div>
            </div>
            <div class="header-right">
                US$ <?php echo number_format($remesa['monto'] ?? 0, 2); ?>
            </div>
        </div>
        
        <!-- Beneficiario -->
        <div class="info-section">
            <div><span class="label">TIPO PARTIDA REMESA:</span></div>
            <div class="info-box"><?php echo $remesa['tipo_partida_remesa'] ?? ''; ?></div>
        </div>
        
        <!-- Concepto -->
        <div class="info-section">
            <div><span class="label">CONCEPTO:</span> <?php echo $remesa['concepto'] ?? ''; ?></div>
        </div>
        
        <!-- Información de documento -->
        <div class="partida-info-mejorada">
            <div class="partida-row">
                <span class="partida-label">CORRELATIVO:</span> 
                <span class="partida-value">REM-<?php echo str_pad($remesa['id'] ?? '', 6, '0', STR_PAD_LEFT); ?></span>
            </div>
            <div class="partida-row">
                <span class="partida-label">BANCO / CUENTA:</span> 
                <span class="partida-value"><?php echo $remesa['banco_nombre'] ?? ''; ?> - <?php echo $remesa['banco_cuenta'] ?? ''; ?></span>
            </div>
            <div class="partida-row">
                <span class="partida-label">CUENTA CONTABLE:</span> 
                <span class="partida-value"><?php echo $remesa['banco_cuenta'] ?? ''; ?></span>
            </div>
        </div>
        
        <!-- Partida de Egreso -->
        <div class="detalle-cheque">
            <div class="partida-titulo">PARTIDA DE EGRESO <?php echo 'REMR-' . str_pad($remesa['id'] ?? '', 6, '0', STR_PAD_LEFT); ?></div>
            <?php if (!empty($detalle)): ?>
            <table class="partida-tabla">
                <thead>
                    <tr>
                        <th>CUENTA</th>
                        <th>CONCEPTO</th>
                        <th>DEBE</th>
                        <th>HABER</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totalDebe = 0;
                    $totalHaber = 0;
                    $numeroRemesa = $remesa['id'] ?? '';
                    foreach ($detalle as $row): 
                        $cuenta = $row['cuenta_contable'] ?? ($row['cuenta'] ?? 'N/A');
                        $nombreCuenta = $row['nombre_cuenta'] ?? '';
                        $monto = floatval($row['monto'] ?? 0);
                        $tipo = $row['tipo'] ?? 'Debe';
                        $concepto = $row['concepto'] ?? '';
                        
                        if ($tipo == 'Debe') $totalDebe += $monto;
                        else $totalHaber += $monto;
                        
                        $detalleConcepto = trim($cuenta . ' ' . $nombreCuenta);
                        if (!empty($numeroRemesa)) {
                            $detalleConcepto .= ' - REMESA REMR-' . str_pad($numeroRemesa, 6, '0', STR_PAD_LEFT);
                        }
                    ?>
                    <tr>
                        <td style="text-align: left; font-weight: bold;"><?php echo $cuenta; ?></td>
                        <td style="text-align: left;"><?php echo $detalleConcepto; ?></td>
                        <td><?php echo $tipo == 'Debe' ? number_format($monto, 2) : ''; ?></td>
                        <td><?php echo $tipo == 'Haber' ? number_format($monto, 2) : ''; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr style="background: #e0e0e0; font-weight: bold;">
                        <td colspan="2" style="text-align: left;">TOTALES</td>
                        <td><?php echo number_format($totalDebe, 2); ?></td>
                        <td><?php echo number_format($totalHaber, 2); ?></td>
                    </tr>
                </tbody>
            </table>
            <?php else: ?>
            <div style="padding: 5px; text-align: center; color: #999; font-size: 8px;">
                No hay detalle contable registrado
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Firmas -->
        <div class="footer-firmas">
            <div class="firma-cell">
                <div class="firma-linea"></div>
                <div class="firma-texto">HECHO POR</div>
                <div class="firma-label">REVISO</div>
            </div>
            <div class="firma-cell">
                <div class="firma-linea"></div>
                <div class="firma-texto">AUTORIZADO POR</div>
                <div class="firma-label">CONTABILIDAD</div>
            </div>
            <div class="firma-cell">
                <div class="firma-linea"></div>
                <div class="firma-texto">RECIBI CONFORME</div>
                <div class="firma-label">BENEFICIARIO</div>
            </div>
        </div>
        
        <!-- Pie de página -->
        <div style="position: absolute; bottom: 3mm; left: 8mm; right: 8mm; text-align: center; font-size: 7px; color: #999;">
            Documento generado el <?php echo date('d/m/Y H:i'); ?> | <?php echo $empresa['nombre']; ?>
        </div>
    </div>
</body>
</html>
