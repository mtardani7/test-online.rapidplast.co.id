<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>{{ $heading }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f3f5f8;font-family:Arial,Helvetica,sans-serif;color:#182433;-webkit-text-size-adjust:100%;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">{{ $heading }} — {{ $brand }}</div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f3f5f8;width:100%;">
        <tr>
            <td align="center" style="padding:36px 16px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;">
                    <tr>
                        <td style="padding:0 0 18px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td width="42" height="42" align="center" valign="middle" style="width:42px;height:42px;background:#b4232b;border-radius:10px;color:#ffffff;font-size:14px;font-weight:700;letter-spacing:1px;">RP</td>
                                    <td style="padding-left:12px;">
                                        <div style="font-size:15px;line-height:20px;font-weight:700;letter-spacing:.4px;color:#172536;">{{ $brand }}</div>
                                        <div style="font-size:10px;line-height:15px;font-weight:700;letter-spacing:1.2px;color:#7a8795;">KARIER &amp; ASESMEN</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:4px;background:#b4232b;border-radius:4px 4px 0 0;font-size:0;line-height:0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding:38px 42px 34px;background:#ffffff;border:1px solid #e4e9ef;border-top:0;border-radius:0 0 12px 12px;">
                            <div style="margin-bottom:14px;font-size:10px;line-height:16px;font-weight:700;letter-spacing:1.5px;color:#b4232b;">{{ $eyebrow }}</div>
                            <h1 style="margin:0 0 20px;font-size:26px;line-height:34px;font-weight:700;color:#172536;">{{ $heading }}</h1>
                            <p style="margin:0 0 10px;font-size:15px;line-height:24px;color:#263548;">Halo {{ $name }},</p>
                            <p style="margin:0 0 26px;font-size:14px;line-height:23px;color:#586777;">{{ $intro }}</p>
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 0 24px;">
                                <tr>
                                    <td align="center" bgcolor="#b4232b" style="border-radius:7px;">
                                        <a href="{{ $actionUrl }}" style="display:inline-block;padding:13px 22px;border:1px solid #b4232b;border-radius:7px;color:#ffffff;text-decoration:none;font-size:14px;line-height:20px;font-weight:700;">{{ $buttonLabel }}</a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:0 0 8px;font-size:12px;line-height:19px;color:#6b7785;">{{ $expiry }}</p>
                            <div style="margin:22px 0;padding:14px 16px;background:#f6f8fa;border:1px solid #e7ebef;border-radius:7px;font-size:12px;line-height:19px;color:#596675;">{{ $securityNote }}</div>
                            <p style="margin:0 0 7px;font-size:12px;line-height:19px;color:#7a8795;">Jika tombol tidak berfungsi, salin dan buka tautan berikut di peramban:</p>
                            <p style="margin:0;word-break:break-all;font-size:12px;line-height:19px;"><a href="{{ $actionUrl }}" style="color:#9f1d27;text-decoration:underline;">{{ $actionUrl }}</a></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:22px 12px 0;font-size:11px;line-height:18px;color:#8994a0;">
                            Email otomatis dari sistem rekrutmen {{ $brand }}.<br>
                            Mohon tidak membalas email ini.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
