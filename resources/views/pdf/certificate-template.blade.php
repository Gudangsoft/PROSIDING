<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - {{ $certNumber }}</title>
    <style>
        @page {
            margin: 1cm;
            size: A4 landscape;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .certificate {
            width: 100%;
            height: 100vh;
            padding: 40px;
            box-sizing: border-box;
            position: relative;
        }
        
        .border-outer {
            border: 8px solid #FFD700;
            padding: 15px;
            height: 100%;
            box-shadow: inset 0 0 30px rgba(0,0,0,0.2);
        }
        
        .border-inner {
            border: 2px solid #FFD700;
            padding: 30px;
            height: 100%;
            background: white;
            position: relative;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header img {
            height: 70px;
            margin-bottom: 10px;
        }
        
        .header h3 {
            margin: 5px 0;
            font-size: 14pt;
            color: #333;
        }
        
        .cert-title {
            text-align: center;
            margin: 30px 0;
        }
        
        .cert-title h1 {
            font-size: 36pt;
            color: #667eea;
            margin: 10px 0;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        
        .cert-number {
            text-align: center;
            font-size: 10pt;
            color: #666;
            margin-bottom: 20px;
        }
        
        .content {
            text-align: center;
            margin: 30px 60px;
        }
        
        .content p {
            font-size: 13pt;
            line-height: 1.8;
            margin: 15px 0;
        }
        
        .recipient-name {
            font-size: 28pt;
            font-weight: bold;
            color: #1a5490;
            margin: 20px 0;
            text-decoration: underline;
        }
        
        .paper-title {
            font-style: italic;
            font-size: 14pt;
            color: #444;
            margin: 15px 40px;
        }
        
        .footer {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-around;
            padding: 0 80px;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-box p {
            margin: 3px 0;
            font-size: 11pt;
        }
        
        .signature-image {
            height: 50px;
            margin: 10px 0;
        }
        
        .underline {
            text-decoration: underline;
            font-weight: bold;
        }
        
        .qr-watermark {
            position: absolute;
            bottom: 20px;
            right: 40px;
            opacity: 0.8;
        }
        
        .qr-watermark img {
            width: 80px;
            height: 80px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-outer">
            <div class="border-inner">
                {{-- Header --}}
                <div class="header">
                    @if($conference && $conference->logo)
                        <img src="{{ public_path('storage/' . $conference->logo) }}" alt="Logo">
                    @endif
                    @if($conference)
                        <h3>{{ $conference->name }}</h3>
                        <p style="margin: 2px 0; font-size: 11pt;">{{ $conference->theme }}</p>
                        <p style="margin: 2px 0; font-size: 10pt;">
                            @if($conference->start_date && $conference->end_date)
                                {{ $conference->start_date->format('d') }} - {{ $conference->end_date->format('d F Y') }}
                            @endif
                        </p>
                    @endif
                </div>
                
                {{-- Certificate Title --}}
                <div class="cert-title">
                    <h1>CERTIFICATE</h1>
                    <p style="font-size: 12pt; color: #666;">OF {{ strtoupper($type === 'author' ? 'PRESENTER' : $type) }}</p>
                </div>
                
                <div class="cert-number">
                    No: {{ $certNumber }}
                </div>
                
                {{-- Content --}}
                <div class="content">
                    <p>This is to certify that</p>
                    
                    <div class="recipient-name">
                        {{ $user->name }}
                    </div>
                    
                    @if($user->institution)
                        <p style="font-size: 11pt; color: #666;">{{ $user->institution }}</p>
                    @endif
                    
                    @if($type === 'author' && $paper)
                        <p>has presented a paper entitled:</p>
                        <div class="paper-title">
                            "{{ $paper->title }}"
                        </div>
                        <p>at {{ $conference ? $conference->name : 'the conference' }}</p>
                    @elseif($type === 'participant')
                        <p>has participated in {{ $conference ? $conference->name : 'the conference' }}</p>
                    @elseif($type === 'reviewer')
                        <p>has served as a reviewer for {{ $conference ? $conference->name : 'the conference' }}</p>
                    @elseif($type === 'committee')
                        <p>has served as a committee member of {{ $conference ? $conference->name : 'the conference' }}</p>
                    @endif
                </div>
                
                {{-- Footer with Signatures --}}
                <div class="footer">
                    <div class="signature-box">
                        <p>Chairperson</p>
                        @if(file_exists(public_path('storage/signatures/chairman.png')))
                            <img src="{{ public_path('storage/signatures/chairman.png') }}" class="signature-image" alt="Signature">
                        @else
                            <div style="height: 50px;"></div>
                        @endif
                        <p class="underline">{{ $conference->organizer ?? 'Chairman Name' }}</p>
                    </div>
                    
                    <div class="signature-box">
                        <p>Secretary</p>
                        <div style="height: 50px;"></div>
                        <p class="underline">Secretary Name</p>
                    </div>
                </div>
                
                {{-- QR Code Watermark --}}
                <div class="qr-watermark">
                    <img src="{{ $qrCode }}" alt="QR Verification">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
