<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .header { border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 28px; }
        .header .invoice-number { color: #666; margin-top: 5px; }
        .org-name { font-weight: bold; }
        .details { margin: 20px 0; }
        .details table { width: 100%; border-collapse: collapse; }
        .details td { padding: 10px 0; border-bottom: 1px solid #eee; }
        .details td:first-child { color: #666; }
        .details td:last-child { text-align: right; font-weight: 500; }
        .total { background: #f5f5f5; padding: 15px; margin: 20px 0; display: flex; justify-content: space-between; }
        .total-label { font-size: 16px; font-weight: bold; }
        .total-amount { font-size: 24px; font-weight: bold; }
        .signature { margin-top: 60px; text-align: center; }
        .signature-line { border-top: 1px solid #333; width: 200px; margin: 0 auto; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <h1>INVOICE</h1>
                    <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                </td>
                <td align="right">
                    <div class="org-name">{{ $invoice->org_name }}</div>
                    <div>{{ $invoice->expense->date->format('d M Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="details">
        <table>
            <tr>
                <td>Category</td>
                <td>{{ $invoice->expense->category }}</td>
            </tr>
            <tr>
                <td>Responsible</td>
                <td>{{ $invoice->expense->member->name }}</td>
            </tr>
            <tr>
                <td>Description</td>
                <td>{{ $invoice->expense->description ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <table width="100%" style="background: #f5f5f5; padding: 15px;">
        <tr>
            <td class="total-label">Total Amount</td>
            <td align="right" class="total-amount">Rp {{ number_format($invoice->expense->amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="signature">
        <div class="signature-line">Authorized Signature</div>
    </div>
</body>
</html>
