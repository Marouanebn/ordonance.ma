<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ordonnance PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h2 { color: #6f42c1; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <h2>Ordonnance Médicale</h2>
    <p><b>Patient:</b> {{ $ordonnance->patient->user->name ?? '-' }}</p>
    <p><b>CIN:</b> {{ $ordonnance->patient->cin ?? '-' }}</p>
    <p><b>Médecin:</b> {{ $ordonnance->medecin->user->name ?? '-' }}</p>
    <p><b>Date:</b> {{ $ordonnance->date_prescription ?? $ordonnance->created_at }}</p>
    <h4>Médicaments</h4>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Quantité</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ordonnance->medicaments as $med)
                <tr>
                    <td>{{ $med->nom }}</td>
                    <td>{{ $med->pivot->quantite ?? 1 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p><b>Détail:</b> {{ $ordonnance->detail ?? '-' }}</p>
    <p>Ordonnance ID: {{ $ordonnance->id }}</p>
</body>
</html>
