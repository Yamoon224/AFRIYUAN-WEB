<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code de vérification AfriYuan</title>
</head>
<body style="margin:0;padding:0;background:#F3F4F6;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#F3F4F6;padding:40px 20px;">
    <tr>
        <td align="center">
            <table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">

                {{-- Header --}}
                <tr>
                    <td style="background:linear-gradient(135deg,#D4132B,#9B0E1F);padding:32px 40px;text-align:center;">
                        <span style="font-size:28px;font-weight:900;color:#ffffff;letter-spacing:-0.5px;">A<span style="color:#FFD700;">¥</span> AfriYuan</span>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:40px;">
                        <p style="margin:0 0 8px;font-size:22px;font-weight:700;color:#111827;">Votre code de vérification</p>
                        <p style="margin:0 0 28px;font-size:14px;color:#6B7280;">Utilisez ce code pour confirmer votre compte AfriYuan. Il est valable <strong>5 minutes</strong>.</p>

                        {{-- OTP code --}}
                        <div style="background:#FFF7ED;border:2px dashed #FED7AA;border-radius:12px;padding:24px;text-align:center;margin-bottom:28px;">
                            <span style="font-size:42px;font-weight:900;letter-spacing:12px;color:#D4132B;font-family:'Courier New',monospace;">{{ $otp }}</span>
                        </div>

                        <p style="margin:0 0 8px;font-size:13px;color:#9CA3AF;">
                            ⚠️ Ne partagez jamais ce code. AfriYuan ne vous demandera jamais votre code par téléphone ou email.
                        </p>
                        <p style="margin:0;font-size:13px;color:#9CA3AF;">
                            Si vous n'avez pas demandé ce code, ignorez cet email.
                        </p>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#F9FAFB;padding:20px 40px;border-top:1px solid #F3F4F6;">
                        <p style="margin:0;font-size:12px;color:#D1D5DB;text-align:center;">
                            © {{ date('Y') }} AfriYuan — Transfert d'argent international<br>
                            Afrique ↔ Chine
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
