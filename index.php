<?php

session_start();

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeNova – Kullanıcı Paneli</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* ===== ACİL DURUM KİŞİSİ KARTI ===== */
        .emergency-section { margin: 30px 0 10px; }
        .emergency-section h2 { font-size: 18px; color: #4a0012; margin-bottom: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .emergency-section h2 i { color: #ff2e7a; }
        .emergency-cards-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .ec-card { background: rgba(255,255,255,0.75); backdrop-filter: blur(12px); border-radius: 20px; padding: 22px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .ec-card h3 { font-size: 15px; color: #4a0012; margin-bottom: 14px; display: flex; align-items: center; gap: 7px; }
        .ec-card h3 i { color: #ff2e7a; }
        .contact-list { list-style: none; margin-bottom: 14px; }
        .contact-list li { display: flex; align-items: center; justify-content: space-between; background: #fff0f6; border-radius: 12px; padding: 10px 14px; margin-bottom: 8px; font-size: 13px; }
        .contact-list li .c-info { display: flex; align-items: center; gap: 10px; }
        .contact-list li .c-avatar { width: 36px; height: 36px; background: linear-gradient(135deg,#ff4fa0,#8a2be2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 13px; }
        .contact-list li .c-name { font-weight: 600; color: #333; font-size: 13px; }
        .contact-list li .c-phone { font-size: 11px; color: #888; }
        .contact-list li .c-actions { display: flex; gap: 6px; }
        .contact-list li .c-actions button { border: none; border-radius: 8px; padding: 5px 10px; cursor: pointer; font-size: 11px; transition: 0.2s; }
        .btn-call-ec { background: #e8f5e9; color: #388e3c; }
        .btn-call-ec:hover { background: #388e3c; color: white; }
        .btn-del-ec { background: #fde8e8; color: #d32f2f; }
        .btn-del-ec:hover { background: #d32f2f; color: white; }
        .no-contact-msg { font-size: 13px; color: #aaa; text-align: center; padding: 10px 0; }
        .add-contact-form { display: flex; flex-direction: column; gap: 10px; }
        .add-contact-form input { padding: 11px 14px; border: 1.5px solid #ffd0e8; border-radius: 12px; font-size: 13px; outline: none; transition: 0.2s; font-family: Arial; }
        .add-contact-form input:focus { border-color: #ff2e7a; }
        .btn-add-contact { padding: 11px; border: none; border-radius: 12px; background: linear-gradient(135deg,#ff2e7a,#ff6aa0); color: white; font-size: 14px; cursor: pointer; transition: 0.25s; font-family: Arial; }
        .btn-add-contact:hover { background: linear-gradient(135deg,#e02163,#ff4fa0); transform: scale(1.03); }
        .alert-banner { display: none; background: linear-gradient(135deg,#ff2e7a,#8a2be2); color: white; border-radius: 16px; padding: 16px 22px; margin-bottom: 18px; font-size: 13px; animation: pulseAlert 1.5s infinite; align-items: center; gap: 14px; }
        .alert-banner.show { display: flex; }
        @keyframes pulseAlert { 0%,100% { box-shadow: 0 0 0 0 rgba(255,46,122,0.4); } 50% { box-shadow: 0 0 0 10px rgba(255,46,122,0); } }
        .alert-banner i { font-size: 22px; }
        .alert-banner .alert-text { flex: 1; }
        .alert-banner .alert-text b { display: block; font-size: 15px; margin-bottom: 2px; }
        .btn-close-alert { background: rgba(255,255,255,0.25); border: none; border-radius: 8px; color: white; padding: 6px 12px; cursor: pointer; font-size: 12px; }
        .police-card { background: linear-gradient(135deg,#1a0033,#3d0070); border-radius: 20px; padding: 22px; color: white; display: flex; flex-direction: column; gap: 14px; box-shadow: 0 10px 30px rgba(138,43,226,0.3); }
        .police-card h3 { font-size: 15px; display: flex; align-items: center; gap: 8px; }
        .police-card h3 i { color: #c084fc; }
        .police-card p { font-size: 12px; color: #c4b5d4; line-height: 1.6; }
        .police-btns { display: flex; flex-direction: column; gap: 10px; }
        .btn-police { padding: 13px; border: none; border-radius: 14px; font-size: 14px; font-weight: 700; cursor: pointer; transition: 0.25s; display: flex; align-items: center; justify-content: center; gap: 8px; font-family: Arial; }
        .btn-police-main { background: linear-gradient(135deg,#ff2e7a,#ff6aa0); color: white; }
        .btn-police-main:hover { transform: scale(1.04); background: linear-gradient(135deg,#e02163,#ff4fa0); }
        .btn-police-sec { background: rgba(255,255,255,0.12); color: white; border: 1.5px solid rgba(255,255,255,0.2); }
        .btn-police-sec:hover { background: rgba(255,255,255,0.2); transform: scale(1.03); }
        .sent-log { background: rgba(255,255,255,0.7); border-radius: 20px; padding: 18px 22px; margin-top: 20px; box-shadow: 0 6px 20px rgba(0,0,0,0.06); }
        .sent-log h3 { font-size: 15px; color: #4a0012; margin-bottom: 12px; display: flex; align-items: center; gap: 7px; }
        .sent-log h3 i { color: #ff2e7a; }
        .log-list { list-style: none; max-height: 300px; overflow-y: auto; }
        .log-list li { font-size: 12px; color: #555; border-left: 3px solid #ff2e7a; padding: 7px 12px; margin-bottom: 8px; background: #fff0f6; border-radius: 0 10px 10px 0; display: flex; justify-content: space-between; align-items: center; }
        .log-list li .log-content { flex: 1; }
        .log-list li span { color: #999; font-size: 11px; display: block; margin-top: 2px; }
        .log-list li .log-actions { display: flex; gap: 6px; }
        .log-list li .log-actions button { border: none; border-radius: 6px; padding: 4px 8px; cursor: pointer; font-size: 10px; transition: 0.2s; }
        .btn-delete-log { background: #fde8e8; color: #d32f2f; }
        .btn-delete-log:hover { background: #d32f2f; color: white; }
        .log-empty { font-size: 13px; color: #aaa; text-align: center; padding: 8px 0; }

        /* ===================================================
           MEDYA PANELİ (SES / FOTOĞRAF TAM EKRAN)
           =================================================== */
        .media-panel {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: #f8f0f8;
            z-index: 9999;
            flex-direction: column;
            overflow: hidden;
            -webkit-overflow-scrolling: touch;
        }
        .media-panel.show { display: flex; }
        .media-panel-header {
            background: linear-gradient(135deg,#1a0033,#3d0070);
            color: white;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }
        .media-panel-header h2 { margin: 0; font-size: 18px; }
        .media-panel-back {
            background: rgba(255,255,255,0.18);
            border: none;
            color: white;
            padding: 9px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: 0.2s;
        }
        .media-panel-back:hover { background: rgba(255,255,255,0.3); }
        .media-panel-body {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .media-panel-record-bar {
            background: white;
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            flex-shrink: 0;
        }
        .mpanel-btn {
            padding: 11px 20px;
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 7px;
            font-family: Arial;
        }
        .mpanel-btn:disabled { opacity: 0.45; cursor: not-allowed; }
        .mpanel-btn-primary { background: linear-gradient(135deg,#ff2e7a,#ff6aa0); color: white; }
        .mpanel-btn-primary:hover:not(:disabled) { transform: scale(1.03); }
        .mpanel-btn-secondary { background: linear-gradient(135deg,#8a2be2,#c084fc); color: white; }
        .mpanel-btn-secondary:hover:not(:disabled) { transform: scale(1.03); }
        .mpanel-btn-danger { background: #fde8e8; color: #d32f2f; }
        .mpanel-btn-danger:hover:not(:disabled) { background: #d32f2f; color: white; }

        /* Ses kayıt kartı */
        .audio-item {
            background: white;
            border-radius: 16px;
            padding: 18px 20px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            gap: 12px;
            border-left: 4px solid #ff2e7a;
        }
        .audio-item-header { display: flex; justify-content: space-between; align-items: center; }
        .audio-item-header .info { flex: 1; }
        .audio-item-header .info p { margin: 3px 0; font-size: 13px; font-weight: 600; color: #1a0033; }
        .audio-item-header .info .time { color: #999; font-size: 12px; font-weight: 400; }
        .audio-item-header .actions { display: flex; gap: 8px; }
        .audio-item-header .actions button { border: none; border-radius: 10px; padding: 8px 14px; cursor: pointer; font-size: 12px; transition: 0.2s; font-family: Arial; }
        .btn-send-audio { background: #e8f5e9; color: #388e3c; }
        .btn-send-audio:hover { background: #388e3c; color: white; }
        .btn-delete-audio { background: #fde8e8; color: #d32f2f; }
        .btn-delete-audio:hover { background: #d32f2f; color: white; }
        .audio-controls audio { width: 100%; border-radius: 8px; }

        /* Fotoğraf kartı */
        .foto-item {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            border-left: 4px solid #8a2be2;
        }
        .foto-item img { width: 100%; max-height: 320px; object-fit: cover; display: block; background: #f0f0f0; }
        .foto-item-info { padding: 14px 18px; display: flex; justify-content: space-between; align-items: center; }
        .foto-item-info .time { font-size: 12px; color: #999; }
        .foto-item-info .actions { display: flex; gap: 8px; }
        .foto-item-info .actions button { border: none; border-radius: 10px; padding: 8px 14px; cursor: pointer; font-size: 12px; transition: 0.2s; font-family: Arial; }
        .btn-send-photo { background: #e8f5e9; color: #388e3c; }
        .btn-send-photo:hover { background: #388e3c; color: white; }
        .btn-delete-photo { background: #fde8e8; color: #d32f2f; }
        .btn-delete-photo:hover { background: #d32f2f; color: white; }

        .mp-loading { text-align: center; color: #aaa; padding: 40px; font-size: 15px; }
        .mp-empty { text-align: center; color: #bbb; padding: 40px; font-size: 14px; }
        #toast { position: fixed; bottom: 30px; right: 30px; background: #1a0033; color: white; padding: 14px 20px; border-radius: 14px; font-size: 13px; display: none; z-index: 99999; box-shadow: 0 8px 25px rgba(0,0,0,0.2); max-width: 300px; animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .location-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 10000; justify-content: center; align-items: center; }
        .location-modal.show { display: flex; }
        .location-modal-content { background: white; border-radius: 20px; padding: 0; width: 90%; max-width: 600px; height: 80vh; display: flex; flex-direction: column; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        .location-modal-header { padding: 16px 22px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
        .location-modal-header h3 { margin: 0; color: #4a0012; font-size: 16px; }
        .location-modal-header button { background: none; border: none; font-size: 24px; cursor: pointer; color: #999; }
        .location-modal-body { flex: 1; overflow: hidden; }
        .location-modal-body iframe { width: 100%; height: 100%; border: none; }
        .inline-map-wrapper { position: relative; width: 100%; height: 160px; border-radius: 10px; overflow: hidden; cursor: pointer; background: #f0f0f0; }
        .inline-map-wrapper iframe { width: 100%; height: 100%; border: none; pointer-events: none; }
        .inline-map-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2; }
        .inline-map-loading { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); color: #999; font-size: 13px; pointer-events: none; z-index: 1; }
        .audio-popup { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center; }
        .audio-popup.show { display: flex; }
        .audio-popup-content { background: white; border-radius: 20px; padding: 25px; width: 90%; max-width: 600px; max-height: 80vh; overflow-y: auto; position: relative; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        .audio-popup-content button.close-btn { position: absolute; top: 10px; right: 10px; background: #ff2e7a; color: white; border: none; border-radius: 50%; width: 35px; height: 35px; cursor: pointer; font-size: 16px; transition: 0.2s; }
        .audio-popup-content button.close-btn:hover { background: #d32f2f; }
        .audio-popup-content h3 { color: #ff2e7a; margin-bottom: 20px; margin-top: 0; }
        .photo-popup { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center; }
        .photo-popup.show { display: flex; }
        .photo-popup-content { background: white; border-radius: 20px; padding: 25px; width: 90%; max-width: 700px; max-height: 80vh; overflow-y: auto; position: relative; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        .photo-popup-content button.close-btn { position: absolute; top: 10px; right: 10px; background: #ff2e7a; color: white; border: none; border-radius: 50%; width: 35px; height: 35px; cursor: pointer; font-size: 16px; transition: 0.2s; }
        .photo-popup-content button.close-btn:hover { background: #d32f2f; }
        .photo-popup-content h3 { color: #ff2e7a; margin-bottom: 20px; margin-top: 0; }
        .photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; }
        .photo-item { border-radius: 12px; overflow: hidden; background: #fff9fc; box-shadow: 0 4px 10px rgba(0,0,0,0.08); transition: 0.2s; }
        .photo-item:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        .photo-item img { width: 100%; height: 150px; object-fit: cover; display: block; }
        .photo-item-info { padding: 8px; }
        .photo-item-info p { margin: 3px 0; font-size: 11px; color: #999; }
        .photo-item-actions { display: flex; gap: 5px; margin-top: 6px; }
        .photo-item-actions button { flex: 1; border: none; border-radius: 6px; padding: 5px; cursor: pointer; font-size: 10px; transition: 0.2s; }
        .report-popup { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 10500; justify-content: center; align-items: center; }
        .report-popup.show { display: flex; }
        .report-popup-content { background: white; border-radius: 20px; padding: 25px; width: 90%; max-width: 600px; max-height: 80vh; overflow-y: auto; position: relative; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        .report-popup-content button.close-btn { position: absolute; top: 10px; right: 10px; background: #ff2e7a; color: white; border: none; border-radius: 50%; width: 35px; height: 35px; cursor: pointer; font-size: 16px; transition: 0.2s; }
        .report-popup-content button.close-btn:hover { background: #d32f2f; }
        .report-popup-content h3 { color: #ff2e7a; margin-bottom: 20px; margin-top: 0; }
        .report-item { border: 1px solid #f0f0f0; border-radius: 12px; padding: 12px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; background: #fff9fc; cursor: pointer; transition: 0.2s; }
        .report-item:hover { background: #fff0f6; }
        .report-item-info { flex: 1; }
        .report-item-info p { margin: 3px 0; font-size: 12px; }
        .report-item-info .time { color: #999; font-size: 11px; }
        .report-item-actions { display: flex; gap: 6px; }
        .report-item-actions button { border: none; border-radius: 8px; padding: 6px 12px; cursor: pointer; font-size: 11px; transition: 0.2s; }
        .btn-view-report { background: #4caf50; color: white; }
        .btn-view-report:hover { background: #388e3c; }
        .btn-delete-report { background: #ff6b6b; color: white; }
        .btn-delete-report:hover { background: #d32f2f; }
        .content-area { overflow-y: auto; }
        .page-content { padding-bottom: 40px; }
        .map-popup { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.75); z-index: 9998; justify-content: center; align-items: center; }
        .map-box { background: white; border-radius: 20px; width: 90%; max-width: 750px; height: 78vh; position: relative; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        .map-box iframe { width: 100%; height: 100%; border: none; }
        .close-map { position: absolute; top: 12px; right: 12px; background: #ff2e7a; border: none; border-radius: 10px; color: white; padding: 7px 14px; cursor: pointer; font-size: 13px; z-index: 10; font-weight: 700; }
        .close-map:hover { background: #d32f2f; }

        /* ===================================================
           HESAP MAKİNESİ GİZLEME MODU
           =================================================== */
        #calcOverlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: #f2f2f7;
            z-index: 99999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', sans-serif;
        }
        #calcOverlay.show { display: flex; }
        .calc-wrapper { width: 100%; max-width: 360px; padding: 20px; }
        .calc-display { background: #1c1c1e; border-radius: 24px; padding: 24px 20px 16px; margin-bottom: 16px; text-align: right; }
        .calc-expression { color: #636366; font-size: 18px; min-height: 24px; margin-bottom: 4px; word-break: break-all; }
        .calc-result { color: white; font-size: 52px; font-weight: 300; letter-spacing: -2px; word-break: break-all; line-height: 1.1; }
        .calc-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
        .calc-btn { border: none; border-radius: 50%; width: 72px; height: 72px; font-size: 22px; font-weight: 500; cursor: pointer; transition: all 0.1s; display: flex; align-items: center; justify-content: center; justify-self: center; font-family: inherit; -webkit-tap-highlight-color: transparent; }
        .calc-btn:active { transform: scale(0.93); }
        .calc-btn.gray { background: #a5a5a5; color: white; }
        .calc-btn.gray:hover { background: #bfbfbf; }
        .calc-btn.dark { background: #333333; color: white; }
        .calc-btn.dark:hover { background: #4a4a4a; }
        .calc-btn.orange { background: #ff9f0a; color: white; }
        .calc-btn.orange:hover { background: #ffc03b; }
        .calc-btn.wide { width: 100%; border-radius: 36px; grid-column: span 2; justify-self: stretch; justify-content: flex-start; padding-left: 26px; }
        .panic-hint { text-align: center; font-size: 11px; color: #aeaeb2; margin-top: 12px; letter-spacing: 0.3px; }
        .panic-dots { display: flex; justify-content: center; gap: 6px; margin-top: 6px; }
        .panic-dot { width: 7px; height: 7px; border-radius: 50%; background: #d1d1d6; transition: background 0.2s; }
        .panic-dot.active { background: #ff2e7a; }

        /* GİZLEME MODU BUTONU */
        .hide-mode-btn {
            width: 42px; height: 42px; border-radius: 12px; border: none;
            background: linear-gradient(135deg, #ff2e7a, #8a2be2); color: white;
            font-size: 20px; font-weight: 900; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 14px rgba(255,46,122,0.35); transition: 0.2s;
            font-family: Georgia, serif; letter-spacing: -1px; line-height: 1; position: relative;
        }
        .hide-mode-btn:hover { transform: scale(1.08); box-shadow: 0 6px 20px rgba(255,46,122,0.5); }
        .hide-mode-btn::after { content: ''; position: absolute; top: 6px; right: 6px; width: 7px; height: 7px; background: #fff; border-radius: 50%; border: 1.5px solid rgba(255,255,255,0.6); }

        /* PROFİL SAYFASI */
        .profile-section { margin: 30px 0 10px; }
        .profile-section h2 { font-size: 18px; color: #4a0012; margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .profile-section h2 i { color: #ff2e7a; }
        .profile-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .profile-card { background: rgba(255,255,255,0.90); backdrop-filter: blur(18px); border-radius: 24px; padding: 28px; box-shadow: 0 12px 36px rgba(138,43,226,0.10); border: 1px solid rgba(255,200,230,0.3); }
        .profile-card h3 { font-size: 15px; color: #4a0012; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        .profile-card h3 i { color: #ff2e7a; }
        .profile-avatar-area { display: flex; flex-direction: column; align-items: center; gap: 10px; margin-bottom: 24px; }
        .profile-avatar-big { width: 100px; height: 100px; background: linear-gradient(135deg,#ff4fa0,#8a2be2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 800; font-size: 34px; box-shadow: 0 8px 28px rgba(255,46,122,0.40); border: 4px solid white; }
        .profile-name-tag { text-align: center; }
        .profile-name-tag strong { font-size: 20px; color: #1a0033; display: block; font-weight: 800; }
        .profile-name-tag span { font-size: 12px; color: #aaa; margin-top: 2px; display: block; }
        .profile-info-list { list-style: none; display: flex; flex-direction: column; gap: 0; }
        .profile-info-list li { display: flex; justify-content: space-between; align-items: center; padding: 12px 14px; border-radius: 12px; font-size: 13px; margin-bottom: 6px; background: #fff5fb; transition: 0.2s; }
        .profile-info-list li:hover { background: #ffe8f4; }
        .profile-info-list li .pil-label { color: #888; display: flex; align-items: center; gap: 8px; font-weight: 500; }
        .profile-info-list li .pil-value { font-weight: 700; color: #1a0033; font-size: 13px; }
        .profile-form { display: flex; flex-direction: column; gap: 12px; }
        .profile-form label { font-size: 12px; color: #888; font-weight: 600; margin-bottom: -6px; }
        .profile-form input, .profile-form select { padding: 12px 14px; border: 1.5px solid #ffd0e8; border-radius: 14px; font-size: 13px; outline: none; transition: 0.2s; font-family: Arial; background: white; box-shadow: 0 2px 6px rgba(255,46,122,0.06); }
        .profile-form input:focus, .profile-form select:focus { border-color: #ff2e7a; box-shadow: 0 0 0 3px rgba(255,46,122,0.12); }
        .btn-save-profile { padding: 14px; border: none; border-radius: 14px; background: linear-gradient(135deg,#ff2e7a,#ff6aa0); color: white; font-size: 15px; font-weight: 700; cursor: pointer; transition: 0.25s; font-family: Arial; margin-top: 6px; box-shadow: 0 6px 20px rgba(255,46,122,0.30); display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-save-profile:hover { background: linear-gradient(135deg,#e02163,#ff4fa0); transform: translateY(-2px); box-shadow: 0 10px 28px rgba(255,46,122,0.40); }

        /* Hesap Makinesi Exit FAB */
        .calc-hide-fab { position: fixed; bottom: 24px; left: 24px; z-index: 100001; background: linear-gradient(135deg,#1a0033,#3d0070); color: white; border: none; border-radius: 50%; width: 46px; height: 46px; font-size: 18px; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.3); display: none; align-items: center; justify-content: center; transition: 0.2s; }
        .calc-hide-fab.show { display: flex; }
        .calc-hide-fab:hover { transform: scale(1.1); }

        @media(max-width:900px) {
            .emergency-cards-row { grid-template-columns: 1fr; }
            .photo-grid { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); }
            .profile-grid { grid-template-columns: 1fr; }
            .calc-btn { width: 64px; height: 64px; font-size: 20px; }
        }

        @media(max-width:768px) {
            .sidebar { display: none !important; }
            .content-area { padding: 12px 10px 80px 10px !important; }
            .top-header { padding: 14px 12px; margin-bottom: 16px; }
            .welcome-area h1 { font-size: 16px; }
            .welcome-area p { font-size: 12px; }
            .header-right { gap: 10px; }
            .user-name { display: none; }
            .dashboard-cards { grid-template-columns: 1fr !important; gap: 14px; }
            .top-cards { grid-template-columns: 1fr !important; gap: 12px; }
            .emergency-cards-row { grid-template-columns: 1fr !important; }
            .profile-grid { grid-template-columns: 1fr !important; }
            .card { padding: 16px; }
            .card h3 { font-size: 15px; }
            .card p { font-size: 13px; }
            .card button { width: 100%; padding: 12px; font-size: 14px; }
            .media-panel { width: 100% !important; height: 100vh !important; }
            .police-card { padding: 16px; }
            #toast { bottom: 90px; right: 10px; left: 10px; max-width: 100%; text-align: center; }
            .audio-popup-content, .photo-popup-content, .report-popup-content { width: 96% !important; padding: 16px !important; }
            .profile-avatar-area { margin-bottom: 14px; }
            .mobile-nav { display: flex !important; }
        }

        .mobile-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            z-index: 9000;
            background: linear-gradient(135deg, #ff4fa0, #8a2be2);
            padding: 8px 0 10px;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.15);
        }
        .mobile-nav a { display: flex; flex-direction: column; align-items: center; gap: 3px; color: rgba(255,255,255,0.75); text-decoration: none; font-size: 10px; font-family: Arial; cursor: pointer; padding: 4px 8px; border-radius: 10px; transition: 0.2s; border: none; background: none; }
        .mobile-nav a.active, .mobile-nav a:hover { color: white; background: rgba(255,255,255,0.2); }
        .mobile-nav a i { font-size: 20px; }

        @media(max-width:768px) {
            .btn-police { padding: 16px !important; font-size: 15px !important; border-radius: 16px !important; }
            .btn-police-main { font-size: 20px !important; padding: 20px !important; }
            .btn-police i { font-size: 18px !important; }
            .card button, .info-card button { padding: 14px !important; font-size: 15px !important; }
            .card-icon { font-size: 32px !important; }
            .profile-input { font-size: 15px !important; padding: 12px !important; }
            .mpanel-btn { padding: 14px 20px !important; font-size: 15px !important; }
            .top-header { padding: 10px 12px !important; }
            .mobile-nav a i { font-size: 18px !important; }
            .mobile-nav a { font-size: 9px !important; padding: 3px 4px !important; }
            .audio-popup-content h3, .photo-popup-content h3, .report-popup-content h3 { font-size: 17px !important; }
            .contact-list li { padding: 12px 14px !important; font-size: 14px !important; }
            input[type=text], input[type=tel], input[type=email], input[type=password], textarea { font-size: 16px !important; padding: 12px !important; border-radius: 12px !important; }
            * { -webkit-overflow-scrolling: touch; }
            audio { height: 44px !important; }
        }

        @media(max-width:380px) {
            .btn-police-main { font-size: 17px !important; padding: 16px !important; }
            .welcome-area h1 { font-size: 14px !important; }
            .card { padding: 12px !important; }
        }

        /* ===== EK MOBİL İYİLEŞTİRMELER ===== */
        @media(max-width:768px) {
            /* Media panel body scroll */
            .media-panel-body { padding: 14px !important; gap: 12px !important; }

            /* Ses kayıt butonları tam genişlik */
            .media-panel-record-bar { flex-direction: column !important; align-items: stretch !important; }
            .media-panel-record-bar .mpanel-btn { width: 100%; justify-content: center; }

            /* Audio player tam genişlik */
            .audio-item { padding: 12px !important; }
            .audio-item-header { flex-direction: column !important; gap: 10px !important; }
            .audio-item-header .actions { width: 100%; justify-content: flex-end; }
            .audio-controls audio { height: 48px !important; }

            /* Fotoğraf grid tek kolon */
            #fotoListesi { grid-template-columns: 1fr !important; }

            /* Buluşma formu inputlar */
            #bulusmaPanel input,
            #bulusmaPanel select,
            #bulusmaPanel textarea { font-size: 16px !important; }

            /* Buluşma liste kartları */
            #bulusmaListesi > div { padding: 12px 14px !important; }

            /* Popup'lar ekranı kaplamasın */
            .report-popup-content,
            .audio-popup-content,
            .photo-popup-content { width: 96% !important; max-height: 85vh !important; }

            /* Telefon arama overlay */
            #phoneCallOverlay > div { max-width: 100% !important; }

            /* Hesap makinesi */
            .calc-btn { width: 56px !important; height: 56px !important; font-size: 18px !important; }
            .calc-result { font-size: 40px !important; }

            /* Header kompakt */
            .top-header { flex-wrap: wrap; gap: 8px; }
            .header-icons { gap: 6px !important; }
            .hide-mode-btn { width: 36px !important; height: 36px !important; font-size: 16px !important; }
            .icon-circle { width: 36px !important; height: 36px !important; }

            /* İhbar popup */
            #ihbarPopup .report-popup-content { padding: 14px !important; }

            /* Konum modal */
            .location-modal-content { height: 70vh !important; }

            /* Gönderim geçmişi */
            .log-list li { flex-direction: column !important; gap: 6px !important; align-items: flex-start !important; }

            /* Acil kişi listesi butonları */
            .c-actions { flex-direction: row !important; }
        }
    </style>
</head>
<body>

<!-- HESAP MAKİNESİ OVERLAY -->
<div id="calcOverlay">
    <div class="calc-wrapper">
        <div class="calc-display">
            <div class="calc-expression" id="calcExpression"></div>
            <div class="calc-result" id="calcResult">0</div>
        </div>
        <div class="calc-grid">
            <button class="calc-btn gray" onclick="calcInput('AC')">AC</button>
            <button class="calc-btn gray" onclick="calcInput('+/-')">+/-</button>
            <button class="calc-btn gray" onclick="calcInput('%')">%</button>
            <button class="calc-btn orange" onclick="calcInput('÷')">÷</button>
            <button class="calc-btn dark" onclick="calcInput('7')">7</button>
            <button class="calc-btn dark" onclick="calcInput('8')">8</button>
            <button class="calc-btn dark" onclick="calcInput('9')">9</button>
            <button class="calc-btn orange" onclick="calcInput('×')">×</button>
            <button class="calc-btn dark" onclick="calcInput('4')">4</button>
            <button class="calc-btn dark" onclick="calcInput('5')">5</button>
            <button class="calc-btn dark" onclick="calcInput('6')">6</button>
            <button class="calc-btn orange" onclick="calcInput('−')">−</button>
            <button class="calc-btn dark" onclick="calcInput('1')">1</button>
            <button class="calc-btn dark" onclick="calcInput('2')">2</button>
            <button class="calc-btn dark" onclick="calcInput('3')">3</button>
            <button class="calc-btn orange" onclick="calcInput('+')">+</button>
            <button class="calc-btn dark wide" onclick="calcInput('0')">0</button>
            <button class="calc-btn dark" onclick="calcInput('.')">.</button>
            <button class="calc-btn orange" onclick="calcInput('=')">=</button>
        </div>
        <div class="panic-dots" id="panicDots">
            <div class="panic-dot" id="pd0"></div>
            <div class="panic-dot" id="pd1"></div>
            <div class="panic-dot" id="pd2"></div>
            <div class="panic-dot" id="pd3"></div>
        </div>
    </div>
</div>

<button class="calc-hide-fab" id="calcExitFab" onclick="exitCalcMode()" title="Uygulamaya Dön">🔒</button>

<div class="main-container">
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="logo.png" alt="logo" class="mini-logo">
            <h2>SafeNova</h2>
        </div>
        <nav class="menu">
            <ul>
                <li class="active"><a href="#" onclick="scrollToSection('top-section',this)"><i>🏠 Ana Sayfa</i></a></li>
                <li><a href="#" onclick="scrollToSection('acil-section',this)"><i>🆘 Acil Durum Kişisi</i></a></li>
                <li><a href="#" onclick="scrollToSection('card-guvenli',this)"><i>📍 Güvenli Noktalar</i></a></li>
                <li><a href="#" onclick="openMediaPanel('ses',this)"><i>🎤 Ses Kaydı</i></a></li>
                <li><a href="#" onclick="openMediaPanel('foto',this)"><i>📷 Gizli Fotoğraf</i></a></li>
                <li><a href="#" onclick="scrollToSection('card-arama',this)"><i>📞 Sahte Arama</i></a></li>
                <li><a href="#" onclick="openMediaPanel('bulusma',this)"><i>📅 Buluşma Takibi</i></a></li>
                <li><a href="#" onclick="scrollToSection('profil-section',this)"><i>👤 Profil</i></a></li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <a href="login.php">Çıkış Yap</a>
        </div>
    </aside>

    <div class="content-area">
        <header class="top-header" id="top-section">
            <div class="welcome-area">
                <h1>Hoş Geldin, <?php echo isset($_SESSION['ad_soyad']) ? htmlspecialchars($_SESSION['ad_soyad']) : 'Misafir'; ?>!</h1>
                <p>Safe Nova ile şu an güvendesin.</p>
            </div>
            <div class="header-right">
                <div class="header-icons">
                    <div class="icon-circle">
                        <i class="fas fa-bell"></i>
                        <span class="badge"></span>
                    </div>
                    <button class="hide-mode-btn" onclick="enterCalcMode()" title="Gizleme Modu">!</button>
                    <div class="icon-circle">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
                <div class="profile-info">
                    <span class="user-name"><?php echo isset($_SESSION['ad_soyad']) ? htmlspecialchars($_SESSION['ad_soyad']) : 'Misafir'; ?></span>
                    <div class="user-avatar" onclick="openProfileModal()" title="Profil Bilgileri" style="cursor:pointer;">
                        <?php
                        if (isset($_SESSION['ad_soyad'])) {
                            $p = explode(" ", $_SESSION['ad_soyad']);
                            echo strtoupper(mb_substr($p[0],0,1) . (count($p)>1 ? mb_substr(end($p),0,1) : ''));
                        } else { echo '??'; }
                        ?>
                    </div>
                </div>
            </div>
        </header>

        <div class="alert-banner" id="alertBanner">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="alert-text">
                <b id="alertTitle">Acil Durum Bildirimi Gönderildi!</b>
                <span id="alertDesc">Acil durum kişileriniz bilgilendirildi.</span>
            </div>
            <button class="btn-close-alert" onclick="closeAlert()">✕ Kapat</button>
        </div>

        <div class="top-cards">
            <div class="info-card map-card">
                <h4>Canlı Konum</h4>
                <p>Konumunuz Aktif</p>
                <div class="inline-map-wrapper" onclick="openLiveLocation()">
                    <div class="inline-map-loading" id="inlineMapLoading">📍 Konum yükleniyor...</div>
                    <iframe id="inlineMap" src=""></iframe>
                    <div class="inline-map-overlay"></div>
                </div>
            </div>
            <div class="info-card" style="flex-direction:column;align-items:flex-start;">
                <div style="display:flex;align-items:center;gap:15px;">
                    <div class="info-icon warning"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="info-text">
                        <h4>Son İhbarlar <span class="badge-number" id="ihbar-sayi">0</span></h4>
                    </div>
                </div>
                <div style="display:flex;gap:8px;margin-top:10px;width:100%;">
                    <button onclick="sonIhbarlariGoster()" style="flex:1;padding:10px 18px;border:none;border-radius:10px;background:linear-gradient(135deg,#ff2e7a,#ff6aa0);color:white;cursor:pointer;font-size:14px;font-family:Arial;">Son İhbarlar</button>
                    <button onclick="ihbarYap()" style="flex:1;padding:10px 18px;border:none;border-radius:10px;background:linear-gradient(135deg,#ff2e7a,#ff6aa0);color:white;cursor:pointer;font-size:14px;font-family:Arial;">İhbar Yap</button>
                </div>
            </div>
        </div>

        <div class="emergency-section" id="acil-section">
            <h2><i class="fas fa-user-shield"></i> Acil Durum Kişileri</h2>
            <div class="emergency-cards-row">
                <div class="ec-card">
                    <h3><i class="fas fa-address-book"></i> Kayıtlı Kişiler</h3>
                    <ul class="contact-list" id="contactList"></ul>
                    <p class="no-contact-msg" id="noContactMsg" style="display:none;">Henüz acil kişi eklenmedi.</p>
                    <hr style="border:none;border-top:1px solid #ffd0e8;margin:12px 0;">
                    <h3 style="margin-bottom:10px;"><i class="fas fa-user-plus"></i> Yeni Kişi Ekle</h3>
                    <div class="add-contact-form">
                        <input type="text" id="ecName" placeholder="Ad Soyad" maxlength="60">
                        <input type="tel" id="ecPhone" placeholder="Telefon (0555 123 45 67)" maxlength="20">
                        <input type="text" id="ecRel" placeholder="Yakınlık (Anne, Arkadaş…)" maxlength="40">
                        <button class="btn-add-contact" onclick="addContact()"><i class="fas fa-plus-circle"></i> Kaydet</button>
                    </div>
                </div>
                <div class="police-card">
                    <h3><i class="fas fa-shield-alt"></i> Acil Yardım & Bildirim</h3>
                    <p>Tehlike anında seçtiğin türde (konum, ses, fotoğraf) acil durum kişilerinize WhatsApp ile mesaj gönderin.</p>
                    <div class="police-btns">
                        <button class="btn-police btn-police-main" onclick="callPolice()"><i class="fas fa-phone-alt"></i> 155 – Polisi Ara</button>
                        <button class="btn-police btn-police-sec" onclick="sendEmergencyAlert('konum')"><i class="fas fa-map-marker-alt"></i> Konumumu Acil Gönder</button>
                        <button class="btn-police btn-police-sec" onclick="sendEmergencyAlert('ses')"><i class="fas fa-microphone"></i> Ses Kaydımı Acil Gönder</button>
                        <button class="btn-police btn-police-sec" onclick="sendEmergencyAlert('foto')"><i class="fas fa-camera"></i> Fotoğrafımı Acil Gönder</button>
                        <button class="btn-police btn-police-sec" onclick="sendEmergencyAlert('hepsi')" style="background:linear-gradient(135deg,rgba(255,46,122,0.35),rgba(138,43,226,0.35));border-color:rgba(255,46,122,0.5);"><i class="fas fa-satellite-dish"></i> Tümünü Birden Gönder</button>
                    </div>
                </div>
            </div>
            <div class="sent-log" id="sentLogBox">
                <h3><i class="fas fa-history"></i> Gönderim Geçmişi</h3>
                <ul class="log-list" id="sentLogList">
                    <li class="log-empty" id="logEmpty">Henüz gönderim yapılmadı.</li>
                </ul>
            </div>
        </div>

        <div class="dashboard-cards">
            <div class="card" id="card-guvenli">
                <div class="card-icon"><i class="fas fa-map-marker-alt"></i></div>
                <h3>Güvenli Noktalar</h3>
                <p>Konumunuza en yakın güvenli noktaları bulun.</p>
                <button onclick="openGuvenliPopup()">📍 Güvenli Noktaları Gör</button>
            </div>

            <div id="guvenliPopup" class="map-popup">
                <div class="map-box" style="height:auto;padding:30px;display:flex;flex-direction:column;gap:16px;max-width:420px;">
                    <h3 style="color:#4a0012;margin:0;font-size:17px;">📍 Güvenli Noktalar</h3>
                    <p style="color:#888;font-size:13px;margin:0;">Konumunuza en yakın yerleri yeni sekmede açar.</p>
                    <button onclick="acGuvenliNokta('polis')" style="padding:15px;border:none;border-radius:14px;background:linear-gradient(135deg,#1a237e,#3949ab);color:white;font-size:15px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;">🚔 En Yakın Polis Karakolu</button>
                    <button onclick="acGuvenliNokta('hastane')" style="padding:15px;border:none;border-radius:14px;background:linear-gradient(135deg,#b71c1c,#e53935);color:white;font-size:15px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;">🏥 En Yakın Hastane</button>
                    <button onclick="acGuvenliNokta('eczane')" style="padding:15px;border:none;border-radius:14px;background:linear-gradient(135deg,#1b5e20,#43a047);color:white;font-size:15px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;">💊 En Yakın Eczane</button>
                    <button onclick="closeMap()" style="padding:11px;border:none;border-radius:12px;background:#f0f0f0;color:#555;font-size:14px;cursor:pointer;">✕ Kapat</button>
                </div>
            </div>

            <div class="card" id="card-ses">
                <div class="card-icon"><i class="fas fa-microphone"></i></div>
                <h3>Ses Kaydı Gönder</h3>
                <p>Hızlı Şekilde Ses Kaydı Gönder.</p>
                <button type="button" id="startBtn" onclick="startRecording()">🎤 Kaydı Başlat</button>
                <button type="button" id="stopBtn" onclick="stopRecording()" disabled>⏹ Durdur</button>
                <p id="statusText"></p>
                <button onclick="openMediaPanel('ses',null)" style="margin-top:10px;width:100%;padding:10px 18px;border:none;border-radius:10px;background:linear-gradient(135deg,#ff2e7a,#ff6aa0);color:white;cursor:pointer;font-size:14px;font-family:Arial;">🎧 Kayıtları Dinle</button>
            </div>

            <div class="card" id="card-foto">
                <div class="card-icon"><i class="fas fa-camera"></i></div>
                <h3>Gizli Fotoğraf</h3>
                <p>Gizli Fotoğraf Çekip Gönder.</p>
                <video id="camera" width="100%" autoplay style="border-radius:10px;max-height:200px;object-fit:cover;"></video>
                <button type="button" onclick="startCamera()">📷 Kamerayı Aç</button>
                <button type="button" onclick="takePhoto()">📸 Fotoğraf Çek</button>
                <canvas id="canvas" style="display:none;"></canvas>
                <p id="photoStatus"></p>
                <button onclick="openMediaPanel('foto',null)" style="margin-top:10px;width:100%;padding:10px 18px;border:none;border-radius:10px;background:linear-gradient(135deg,#ff2e7a,#ff6aa0);color:white;cursor:pointer;font-size:14px;font-family:Arial;">🖼️ Fotoğrafları Gör</button>
            </div>

            <div class="card" id="card-arama">
                <div class="card-icon"><i class="fas fa-phone"></i></div>
                <h3>Sahte Arama</h3>
                <p>Kurtulmak İçin Gizli Arama Başlat.</p>
                <button onclick="startFakeCall()">📞 Aramayı Başlat</button>
                <audio id="ringtone" src="https://www.soundjay.com/phone/phone-ring-01.mp3"></audio>
                <audio id="fakeCallAudio" src="baba.mp3"></audio>
            </div>

            <div class="card" id="card-bulusma">
                <div class="card-icon"><i class="fas fa-calendar-alt"></i></div>
                <h3>Buluşma Takibi</h3>
                <p>Şüpheli buluşmalarını kaydet ve takip et.</p>
                <button onclick="openMediaPanel('bulusma',null)" style="background:linear-gradient(135deg,#ff2e7a,#ff6aa0);">📅 Buluşmaları Yönet</button>
            </div>
        </div>

        <!-- TELEFON EKRANI OVERLAY -->
        <div id="phoneCallOverlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;z-index:99997;background:#000;justify-content:center;align-items:center;">
            <div style="width:100%;max-width:390px;height:100vh;background:linear-gradient(160deg,#1c1c2e 0%,#16213e 40%,#0f3460 100%);display:flex;flex-direction:column;align-items:center;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-60px;left:-60px;width:300px;height:300px;background:rgba(255,46,122,0.12);border-radius:50%;filter:blur(60px);pointer-events:none;"></div>
                <div style="position:absolute;bottom:100px;right:-80px;width:280px;height:280px;background:rgba(138,43,226,0.15);border-radius:50%;filter:blur(60px);pointer-events:none;"></div>
                <div style="width:100%;padding:14px 24px 0;display:flex;justify-content:space-between;align-items:center;color:white;font-size:13px;font-family:-apple-system,sans-serif;">
                    <span id="phoneRealTime" style="font-weight:600;"></span>
                    <div style="display:flex;gap:6px;align-items:center;">
                        <i class="fas fa-signal" style="font-size:11px;"></i>
                        <i class="fas fa-wifi" style="font-size:11px;"></i>
                        <i class="fas fa-battery-three-quarters" style="font-size:12px;"></i>
                    </div>
                </div>
                <div id="phoneCallState" style="margin-top:28px;color:rgba(255,255,255,0.7);font-size:15px;font-family:-apple-system,sans-serif;letter-spacing:0.3px;">Arıyor...</div>
                <div style="position:relative;margin-top:32px;display:flex;align-items:center;justify-content:center;">
                    <div style="position:absolute;width:170px;height:170px;border-radius:50%;border:1.5px solid rgba(255,255,255,0.1);animation:phoneRing 2s ease-out infinite;"></div>
                    <div style="position:absolute;width:140px;height:140px;border-radius:50%;border:1.5px solid rgba(255,255,255,0.15);animation:phoneRing 2s ease-out infinite 0.5s;"></div>
                    <div style="width:108px;height:108px;border-radius:50%;background:linear-gradient(135deg,#ff4fa0,#8a2be2);display:flex;align-items:center;justify-content:center;box-shadow:0 0 40px rgba(255,46,122,0.4);">
                        <i class="fas fa-user" style="font-size:44px;color:white;"></i>
                    </div>
                </div>
                <div style="margin-top:28px;text-align:center;">
                    <div style="font-size:32px;font-weight:700;color:white;font-family:-apple-system,sans-serif;letter-spacing:-0.5px;">Baba</div>
                    <div style="font-size:14px;color:rgba(255,255,255,0.5);margin-top:5px;font-family:-apple-system,sans-serif;">Mobil · Rehber</div>
                    <div id="phoneCallTimer" style="display:none;font-size:17px;color:rgba(255,255,255,0.8);margin-top:10px;font-family:-apple-system,sans-serif;font-weight:500;letter-spacing:1px;">00:00</div>
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:auto;margin-bottom:20px;padding:0 28px;width:100%;box-sizing:border-box;">
                    <div onclick="toggleMute()" style="display:flex;flex-direction:column;align-items:center;gap:8px;cursor:pointer;">
                        <div id="muteIcon" style="width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;"><i class="fas fa-microphone-slash" style="font-size:22px;color:white;"></i></div>
                        <span style="font-size:12px;color:rgba(255,255,255,0.65);font-family:-apple-system,sans-serif;">Sessiz</span>
                    </div>
                    <div onclick="toggleSpeaker()" style="display:flex;flex-direction:column;align-items:center;gap:8px;cursor:pointer;">
                        <div id="speakerIcon" style="width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;"><i class="fas fa-volume-up" style="font-size:22px;color:white;"></i></div>
                        <span style="font-size:12px;color:rgba(255,255,255,0.65);font-family:-apple-system,sans-serif;">Hoparlör</span>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:center;gap:8px;cursor:pointer;">
                        <div style="width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;"><i class="fas fa-keyboard" style="font-size:22px;color:white;"></i></div>
                        <span style="font-size:12px;color:rgba(255,255,255,0.65);font-family:-apple-system,sans-serif;">Tuşlar</span>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:center;gap:8px;cursor:pointer;">
                        <div style="width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;"><i class="fas fa-plus" style="font-size:22px;color:white;"></i></div>
                        <span style="font-size:12px;color:rgba(255,255,255,0.65);font-family:-apple-system,sans-serif;">Ekle</span>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:center;gap:8px;cursor:pointer;">
                        <div style="width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;"><i class="fas fa-video" style="font-size:22px;color:white;"></i></div>
                        <span style="font-size:12px;color:rgba(255,255,255,0.65);font-family:-apple-system,sans-serif;">Video</span>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:center;gap:8px;cursor:pointer;">
                        <div style="width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;"><i class="fas fa-pause" style="font-size:22px;color:white;"></i></div>
                        <span style="font-size:12px;color:rgba(255,255,255,0.65);font-family:-apple-system,sans-serif;">Beklet</span>
                    </div>
                </div>
                <div onclick="endCall()" style="width:72px;height:72px;border-radius:50%;background:#ff3b30;display:flex;align-items:center;justify-content:center;cursor:pointer;margin-bottom:40px;box-shadow:0 8px 25px rgba(255,59,48,0.5);">
                    <i class="fas fa-phone-slash" style="font-size:26px;color:white;transform:rotate(135deg);"></i>
                </div>
            </div>
        </div>
        <style>@keyframes phoneRing { 0% { transform:scale(1); opacity:0.8; } 100% { transform:scale(1.6); opacity:0; } }</style>

        <!-- PROFİL SAYFASI -->
        <div class="profile-section" id="profil-section">
            <h2><i class="fas fa-user-circle"></i> Profilim</h2>
            <div class="profile-grid">
                <div class="profile-card">
                    <h3><i class="fas fa-id-card"></i> Kişisel Bilgilerim</h3>
                    <div class="profile-avatar-area">
                        <div class="profile-avatar-big" id="profileAvatarBig">
                            <?php
                            if (isset($_SESSION['ad_soyad'])) {
                                $p2 = explode(" ", $_SESSION['ad_soyad']);
                                echo strtoupper(mb_substr($p2[0],0,1) . (count($p2)>1 ? mb_substr(end($p2),0,1) : ''));
                            } else { echo '??'; }
                            ?>
                        </div>
                        <div class="profile-name-tag">
                            <strong id="profileDisplayName"><?php echo isset($_SESSION['ad_soyad']) ? htmlspecialchars($_SESSION['ad_soyad']) : 'Misafir'; ?></strong>
                            <span id="profileDisplayPhone">Telefon bilgisi yok</span>
                        </div>
                    </div>
                    <ul class="profile-info-list" id="profileInfoList">
                        <li><span class="pil-label"><i class="fas fa-tint" style="color:#e53935;"></i> Kan Grubu</span><span class="pil-value" id="pil-kan">—</span></li>
                        <li><span class="pil-label"><i class="fas fa-birthday-cake" style="color:#ff9800;"></i> Doğum Tarihi</span><span class="pil-value" id="pil-dogum">—</span></li>
                        <li><span class="pil-label"><i class="fas fa-heartbeat" style="color:#f44336;"></i> Kronik Hastalık</span><span class="pil-value" id="pil-hastalik">—</span></li>
                        <li><span class="pil-label"><i class="fas fa-pills" style="color:#9c27b0;"></i> İlaç Kullanımı</span><span class="pil-value" id="pil-ilac">—</span></li>
                        <li><span class="pil-label"><i class="fas fa-allergies" style="color:#ff5722;"></i> Alerjiler</span><span class="pil-value" id="pil-alerji">—</span></li>
                    </ul>
                </div>
                <div class="profile-card">
                    <h3><i class="fas fa-edit"></i> Bilgileri Güncelle</h3>
                    <div class="profile-form">
                        <label for="pf-ad">Ad Soyad</label>
                        <input type="text" id="pf-ad" placeholder="Ad Soyad" value="<?php echo isset($_SESSION['ad_soyad']) ? htmlspecialchars($_SESSION['ad_soyad']) : ''; ?>">
                        <label for="pf-tel">Telefon Numarası</label>
                        <input type="tel" id="pf-tel" placeholder="0555 123 45 67">
                        <label for="pf-dogum">Doğum Tarihi</label>
                        <input type="date" id="pf-dogum">
                        <label for="pf-kan">Kan Grubu</label>
                        <select id="pf-kan">
                            <option value="">Seçiniz...</option>
                            <option value="A+">A Rh+ (A pozitif)</option>
                            <option value="A-">A Rh- (A negatif)</option>
                            <option value="B+">B Rh+ (B pozitif)</option>
                            <option value="B-">B Rh- (B negatif)</option>
                            <option value="AB+">AB Rh+ (AB pozitif)</option>
                            <option value="AB-">AB Rh- (AB negatif)</option>
                            <option value="0+">0 Rh+ (0 pozitif)</option>
                            <option value="0-">0 Rh- (0 negatif)</option>
                        </select>
                        <label for="pf-hastalik">Kronik Hastalıklar</label>
                        <input type="text" id="pf-hastalik" placeholder="Diyabet, Hipertansiyon, vb. (yoksa boş)">
                        <label for="pf-ilac">Kullandığı İlaçlar</label>
                        <input type="text" id="pf-ilac" placeholder="Metformin, vb. (yoksa boş)">
                        <label for="pf-alerji">Alerjiler</label>
                        <input type="text" id="pf-alerji" placeholder="Penisilin, vb. (yoksa boş)">
                        <button class="btn-save-profile" onclick="saveProfile()">
                            <i class="fas fa-save"></i> Profili Kaydet
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-content"></div>
    </div>
</div>

<!-- KONUM MODAL -->
<div class="location-modal" id="locationModal">
    <div class="location-modal-content">
        <div class="location-modal-header">
            <h3>📍 Canlı Konum</h3>
            <button onclick="closeLiveLocation()">✕</button>
        </div>
        <div class="location-modal-body">
            <iframe id="liveLocationMap" src=""></iframe>
        </div>
    </div>
</div>

<!-- SES KAYITLARI PANEL -->
<div class="media-panel" id="sesPanel">
    <div class="media-panel-header">
        <button class="media-panel-back" onclick="closeMediaPanel('ses')"><i class="fas fa-arrow-left"></i> Geri</button>
        <h2>🎧 Ses Kayıtlarım</h2>
        <div></div>
    </div>
    <div class="media-panel-body">
        <div class="media-panel-record-bar">
            <button class="mpanel-btn mpanel-btn-primary" id="mpStartBtn" onclick="startRecording2()"><i class="fas fa-microphone"></i> Yeni Kayıt Başlat</button>
            <button class="mpanel-btn mpanel-btn-danger" id="mpStopBtn" onclick="stopRecording2()" disabled><i class="fas fa-stop"></i> Durdur</button>
            <span id="mpStatusText" style="font-size:13px;color:#888;"></span>
            <div style="margin-top:12px;display:none;" id="onizlemeDiv">
                <p style="font-size:12px;color:#ff2e7a;margin-bottom:6px;">🔊 Son Kayıt Önizleme:</p>
                <audio id="kayitOnizleme" controls style="width:100%;border-radius:8px;display:none;"></audio>
            </div>
        </div>
        <div id="sesListesi" style="display:flex;flex-direction:column;gap:16px;"><div class="mp-loading"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</div></div>
    </div>
</div>

<!-- FOTOĞRAFLAR PANEL -->
<div class="media-panel" id="fotoPanel">
    <div class="media-panel-header">
        <button class="media-panel-back" onclick="closeMediaPanel('foto')"><i class="fas fa-arrow-left"></i> Geri</button>
        <h2>📷 Fotoğraflarım</h2>
        <div></div>
    </div>
    <div class="media-panel-body">
        <div class="media-panel-record-bar">
            <button class="mpanel-btn mpanel-btn-primary" onclick="startCamera2()"><i class="fas fa-camera"></i> Kamerayı Aç</button>
            <button class="mpanel-btn mpanel-btn-secondary" onclick="takePhoto2()"><i class="fas fa-circle"></i> Fotoğraf Çek</button>
        </div>
        <div id="mp-camera-area" style="display:none;margin-bottom:16px;">
            <video id="mp-camera" width="100%" autoplay style="border-radius:14px;max-height:280px;object-fit:cover;background:#000;"></video>
            <canvas id="mp-canvas" style="display:none;"></canvas>
            <p id="mpPhotoStatus" style="font-size:13px;color:#ff2e7a;margin-top:8px;text-align:center;"></p>
        </div>
        <div id="fotoListesi" style="display:flex;flex-direction:column;gap:16px;"><div class="mp-loading"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</div></div>
    </div>
</div>

<!-- BULUŞMA TAKİBİ PANELİ -->
<div class="media-panel" id="bulusmaPanel">
    <div class="media-panel-header">
        <button class="media-panel-back" onclick="closeMediaPanel('bulusma')"><i class="fas fa-arrow-left"></i> Geri</button>
        <h2>📅 Buluşma Takibi</h2>
        <div></div>
    </div>
    <div class="media-panel-body" style="flex-direction:column;gap:24px;">

        <!-- FORM -->
        <div style="background:white;border-radius:20px;padding:22px;box-shadow:0 4px 18px rgba(0,0,0,0.08);">
            <h3 id="bulusmaFormBaslik" style="color:#ff2e7a;font-size:16px;margin:0 0 18px;display:flex;align-items:center;gap:8px;"><i class="fas fa-plus-circle"></i> Yeni Buluşma Ekle</h3>
            <input type="hidden" id="bf-id" value="">
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div>
                    <label style="font-size:11px;color:#888;font-weight:700;display:block;margin-bottom:4px;" for="bf-kisi">👤 Kişi Adı Soyadı *</label>
                    <input id="bf-kisi" type="text" placeholder="Ad Soyad" style="width:100%;padding:11px 14px;border:1.5px solid #ffd0e8;border-radius:12px;font-size:13px;outline:none;box-sizing:border-box;font-family:Arial;">
                </div>
                <div>
                    <label style="font-size:11px;color:#888;font-weight:700;display:block;margin-bottom:4px;" for="bf-yakinlik">🤝 Yakınlık</label>
                    <select id="bf-yakinlik" style="width:100%;padding:11px 14px;border:1.5px solid #ffd0e8;border-radius:12px;font-size:13px;outline:none;box-sizing:border-box;font-family:Arial;background:white;">
                        <option value="">Seçiniz...</option>
                        <option value="Arkadaş">Arkadaş</option>
                        <option value="Aile">Aile</option>
                        <option value="İş Arkadaşı">İş Arkadaşı</option>
                        <option value="Tanıdık">Tanıdık</option>
                        <option value="Yabancı">Yabancı</option>
                        <option value="Diğer">Diğer</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:11px;color:#888;font-weight:700;display:block;margin-bottom:4px;" for="bf-adres">📍 Buluşma Adresi *</label>
                    <input id="bf-adres" type="text" placeholder="Cadde, mahalle, şehir..." style="width:100%;padding:11px 14px;border:1.5px solid #ffd0e8;border-radius:12px;font-size:13px;outline:none;box-sizing:border-box;font-family:Arial;">
                </div>
                <div style="display:flex;gap:10px;">
                    <div style="flex:1;">
                        <label style="font-size:11px;color:#888;font-weight:700;display:block;margin-bottom:4px;" for="bf-tarih">📆 Tarih *</label>
                        <input id="bf-tarih" type="date" style="width:100%;padding:11px 14px;border:1.5px solid #ffd0e8;border-radius:12px;font-size:13px;outline:none;box-sizing:border-box;font-family:Arial;">
                    </div>
                    <div style="flex:1;">
                        <label style="font-size:11px;color:#888;font-weight:700;display:block;margin-bottom:4px;" for="bf-saat">🕐 Saat *</label>
                        <input id="bf-saat" type="time" style="width:100%;padding:11px 14px;border:1.5px solid #ffd0e8;border-radius:12px;font-size:13px;outline:none;box-sizing:border-box;font-family:Arial;">
                    </div>
                </div>
                <div>
                    <label style="font-size:11px;color:#888;font-weight:700;display:block;margin-bottom:4px;" for="bf-suphe">⚠️ Neden Şüpheli?</label>
                    <textarea id="bf-suphe" rows="3" placeholder="Bu kişiyi veya buluşmayı neden şüpheli buluyorsun?" style="width:100%;padding:11px 14px;border:1.5px solid #ffd0e8;border-radius:12px;font-size:13px;outline:none;box-sizing:border-box;font-family:Arial;resize:vertical;"></textarea>
                </div>
                <div style="display:flex;gap:10px;margin-top:4px;">
                    <button onclick="bulusmaKaydet()" style="flex:1;padding:13px;border:none;border-radius:12px;background:linear-gradient(135deg,#ff2e7a,#ff6aa0);color:white;font-size:14px;font-weight:700;cursor:pointer;font-family:Arial;display:flex;align-items:center;justify-content:center;gap:7px;">
                        <i class="fas fa-save"></i> <span id="bfBtnText">Kaydet</span>
                    </button>
                    <button id="bfIptalBtn" onclick="bulusmaFormTemizle()" style="display:none;padding:13px 16px;border:none;border-radius:12px;background:#f0f0f0;color:#555;font-size:14px;cursor:pointer;font-family:Arial;">İptal</button>
                </div>
            </div>
        </div>

        <!-- LİSTE -->
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                <h3 style="color:#4a0012;font-size:16px;margin:0;display:flex;align-items:center;gap:8px;"><i class="fas fa-list" style="color:#ff2e7a;"></i> Buluşma Listesi</h3>
                <button onclick="bulusmaListele()" style="background:none;border:1.5px solid #ffd0e8;border-radius:10px;padding:7px 14px;color:#ff2e7a;font-size:12px;cursor:pointer;font-family:Arial;"><i class="fas fa-sync-alt"></i> Yenile</button>
            </div>
            <div id="bulusmaListesi"><div class="mp-loading"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</div></div>
        </div>

    </div>
</div>

<!-- BULUŞMA DETAY MODAL -->
<div id="bulusmaDetayModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:20100;justify-content:center;align-items:center;">
    <div style="background:white;border-radius:22px;width:92%;max-width:460px;max-height:85vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div style="background:linear-gradient(135deg,#1a0033,#3d0070);border-radius:22px 22px 0 0;padding:20px 22px;display:flex;justify-content:space-between;align-items:center;">
            <h3 style="color:white;margin:0;font-size:16px;display:flex;align-items:center;gap:8px;"><i class="fas fa-calendar-check"></i> Buluşma Detayı</h3>
            <button onclick="closeBulusmaDetay()" style="background:rgba(255,255,255,0.15);border:none;border-radius:50%;width:34px;height:34px;color:white;font-size:16px;cursor:pointer;">✕</button>
        </div>
        <div id="bulusmaDetayIcerik" style="padding:22px;display:flex;flex-direction:column;gap:12px;"></div>
    </div>
</div>

<!-- İHBARLAR POPUP -->
<div class="report-popup" id="ihbarPopup">
    <div class="report-popup-content">
        <button class="close-btn" onclick="closeIhbarPopup()">✕</button>
        <h3>📍 Son İhbarlarım</h3>
        <button onclick="ihbarYap()" style="width:100%;padding:12px;border:none;border-radius:12px;background:linear-gradient(135deg,#ff2e7a,#ff6aa0);color:white;font-size:14px;font-weight:700;cursor:pointer;font-family:Arial;margin-bottom:14px;">📍 Şu Anki Konumdan İhbar Yap</button>
        <div id="ihbarListesi" style="display:flex;flex-direction:column;gap:10px;">Yükleniyor...</div>
    </div>
</div>

<!-- PROFİL MODAL -->
<div id="profileModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.65);z-index:20000;justify-content:center;align-items:flex-start;padding-top:20px;overflow-y:auto;">
    <div style="background:white;border-radius:24px;width:92%;max-width:480px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.3);display:flex;flex-direction:column;">
        <div style="background:linear-gradient(135deg,#1a0033,#3d0070);border-radius:24px 24px 0 0;padding:22px 24px;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:14px;">
                <div id="pmAvatar" style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#ff4fa0,#8a2be2);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:20px;box-shadow:0 4px 14px rgba(255,46,122,0.4);">??</div>
                <div>
                    <div id="pmName" style="color:white;font-size:18px;font-weight:700;">Kullanıcı</div>
                    <div id="pmEmail" style="color:rgba(255,255,255,0.6);font-size:13px;margin-top:2px;">—</div>
                </div>
            </div>
            <button onclick="closeProfileModal()" style="background:rgba(255,255,255,0.15);border:none;border-radius:50%;width:36px;height:36px;color:white;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;">✕</button>
        </div>
        <div style="display:flex;border-bottom:2px solid #ffd0e8;background:#fff8fc;">
            <button id="pmTabInfo" onclick="pmSwitchTab('info')" style="flex:1;padding:13px;border:none;background:none;font-size:13px;font-weight:700;color:#ff2e7a;border-bottom:3px solid #ff2e7a;cursor:pointer;margin-bottom:-2px;">👤 Bilgilerim</button>
            <button id="pmTabEdit" onclick="pmSwitchTab('edit')" style="flex:1;padding:13px;border:none;background:none;font-size:13px;font-weight:600;color:#aaa;border-bottom:3px solid transparent;cursor:pointer;margin-bottom:-2px;">✏️ Bilgileri Düzenle</button>
        </div>
        <div id="pmPanelInfo" style="padding:22px 24px;display:flex;flex-direction:column;gap:0;">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #ffeaf3;font-size:13px;">
                <span style="color:#888;display:flex;align-items:center;gap:7px;"><i class="fas fa-user" style="color:#ff2e7a;"></i> Ad Soyad</span>
                <span id="pmInfoAd" style="font-weight:600;color:#1a0033;">—</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #ffeaf3;font-size:13px;">
                <span style="color:#888;display:flex;align-items:center;gap:7px;"><i class="fas fa-envelope" style="color:#ff2e7a;"></i> E-posta</span>
                <span id="pmInfoMail" style="font-weight:600;color:#1a0033;">—</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid #ffeaf3;font-size:13px;">
                <span style="color:#888;display:flex;align-items:center;gap:7px;"><i class="fas fa-phone" style="color:#ff2e7a;"></i> Telefon</span>
                <span id="pmInfoTel" style="font-weight:600;color:#1a0033;">—</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;font-size:13px;">
                <span style="color:#888;display:flex;align-items:center;gap:7px;"><i class="fas fa-lock" style="color:#ff2e7a;"></i> Şifre</span>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span id="pmInfoSifre" style="font-weight:600;color:#1a0033;letter-spacing:3px;">•••••</span>
                    <button id="pmEyeBtn" onclick="togglePmPassword()" style="background:none;border:none;cursor:pointer;color:#aaa;font-size:15px;padding:2px 6px;"><i class="fas fa-eye" id="pmEyeIcon"></i></button>
                </div>
            </div>
        </div>
        <div id="pmPanelEdit" style="padding:22px 24px;display:none;flex-direction:column;gap:12px;">
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label for="pm-ad" style="font-size:12px;color:#888;font-weight:600;">Ad Soyad</label>
                <input id="pm-ad" type="text" placeholder="Ad Soyad" style="padding:11px 14px;border:1.5px solid #ffd0e8;border-radius:12px;font-size:13px;outline:none;font-family:Arial;">
            </div>
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label for="pm-mail" style="font-size:12px;color:#888;font-weight:600;">E-posta</label>
                <input id="pm-mail" type="email" placeholder="ornek@mail.com" style="padding:11px 14px;border:1.5px solid #ffd0e8;border-radius:12px;font-size:13px;outline:none;font-family:Arial;">
            </div>
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label for="pm-tel" style="font-size:12px;color:#888;font-weight:600;">Telefon</label>
                <input id="pm-tel" type="tel" placeholder="0555 123 45 67" style="padding:11px 14px;border:1.5px solid #ffd0e8;border-radius:12px;font-size:13px;outline:none;font-family:Arial;">
            </div>
            <div style="display:flex;flex-direction:column;gap:5px;">
                <label for="pm-sifre" style="font-size:12px;color:#888;font-weight:600;">Şifre</label>
                <div style="position:relative;">
                    <input id="pm-sifre" type="password" placeholder="Yeni şifre (boş bırakırsan değişmez)" style="padding:11px 42px 11px 14px;border:1.5px solid #ffd0e8;border-radius:12px;font-size:13px;outline:none;font-family:Arial;width:100%;box-sizing:border-box;">
                    <button type="button" onclick="toggleEditPassword()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#aaa;font-size:15px;"><i class="fas fa-eye" id="editEyeIcon"></i></button>
                </div>
            </div>
            <button onclick="saveProfileModal()" style="padding:13px;border:none;border-radius:14px;background:linear-gradient(135deg,#ff2e7a,#ff6aa0);color:white;font-size:14px;font-weight:700;cursor:pointer;font-family:Arial;margin-top:6px;">
                <i class="fas fa-save"></i> Kaydet
            </button>
        </div>
    </div>
</div>

<!-- FOTOĞRAF BÜYÜK GÖRÜNTÜLEYICI -->
<div id="fotoBuyukOverlay" onclick="this.style.display='none'" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.92);z-index:99999;justify-content:center;align-items:center;cursor:zoom-out;">
    <button onclick="document.getElementById('fotoBuyukOverlay').style.display='none'" style="position:absolute;top:18px;right:22px;background:rgba(255,255,255,0.15);border:none;border-radius:50%;width:42px;height:42px;color:white;font-size:20px;cursor:pointer;z-index:10;">✕</button>
    <img id="fotoBuyukImg" src="" alt="Fotoğraf" style="max-width:92vw;max-height:88vh;border-radius:16px;object-fit:contain;box-shadow:0 20px 60px rgba(0,0,0,0.6);" onclick="event.stopPropagation()">
</div>

<div id="toast"></div>

<script>
/* =====================================================
   DASHBOARD KARTLARI — SES / FOTOĞRAF YÖNLENDİRİCİ
   ===================================================== */
function startRecording() {
    openMediaPanel('ses', null);
    setTimeout(() => {
        var btn = document.getElementById('mpStartBtn');
        if (btn && !btn.disabled) startRecording2();
    }, 350);
}
function stopRecording() { stopRecording2(); }

function startCamera() {
    openMediaPanel('foto', null);
    setTimeout(() => startCamera2(), 350);
}
function takePhoto() {
    if (document.getElementById('fotoPanel').classList.contains('show')) {
        takePhoto2();
    } else {
        openMediaPanel('foto', null);
        setTimeout(() => startCamera2(), 350);
    }
}

/* =====================================================
   HESAP MAKİNESİ GİZLEME MODU
   ===================================================== */
let calcDisplay = '0', calcExpression = '', calcNewInput = true;
let panicSequence = '';
const PANIC_CODE = '1155';

function enterCalcMode() {
    document.getElementById('calcOverlay').classList.add('show');
    document.getElementById('calcExitFab').classList.add('show');
    calcReset(); panicSequence = ''; updatePanicDots();
}
function exitCalcMode() {
    document.getElementById('calcOverlay').classList.remove('show');
    document.getElementById('calcExitFab').classList.remove('show');
    panicSequence = ''; updatePanicDots();
}
function calcInput(val) {
    const resultEl = document.getElementById('calcResult');
    const exprEl   = document.getElementById('calcExpression');
    if (/[0-9]/.test(val)) {
        panicSequence += val;
        if (panicSequence.length > PANIC_CODE.length) panicSequence = panicSequence.slice(-PANIC_CODE.length);
        updatePanicDots();
        if (panicSequence === PANIC_CODE) { exitCalcMode(); showToast('✅ Uygulamaya döndünüz.'); return; }
    }
    if (val === 'AC') { calcReset(); return; }
    if (val === '+/-') { calcDisplay = calcDisplay.startsWith('-') ? calcDisplay.slice(1) : '-' + calcDisplay; resultEl.textContent = calcDisplay; return; }
    if (val === '%') { calcDisplay = String(parseFloat(calcDisplay) / 100); resultEl.textContent = calcDisplay; return; }
    if (val === '=') {
        try {
            let expr = calcExpression + calcDisplay;
            expr = expr.replace(/÷/g,'/').replace(/×/g,'*').replace(/−/g,'-');
            let result = Function('"use strict"; return (' + expr + ')')();
            calcDisplay = parseFloat(result.toFixed(10)).toString();
            exprEl.textContent = ''; calcExpression = ''; calcNewInput = true; resultEl.textContent = calcDisplay;
        } catch(e) { resultEl.textContent = 'Hata'; calcReset(); }
        return;
    }
    if ('÷×−+'.includes(val)) { calcExpression += calcDisplay + ' ' + val + ' '; exprEl.textContent = calcExpression; calcNewInput = true; return; }
    if (val === '.') { if (!calcDisplay.includes('.')) calcDisplay += '.'; resultEl.textContent = calcDisplay; return; }
    if (calcNewInput) { calcDisplay = val; calcNewInput = false; }
    else { calcDisplay = calcDisplay === '0' ? val : calcDisplay + val; }
    resultEl.textContent = calcDisplay;
}
function calcReset() {
    calcDisplay = '0'; calcExpression = ''; calcNewInput = true;
    document.getElementById('calcResult').textContent = '0';
    document.getElementById('calcExpression').textContent = '';
}
function updatePanicDots() {
    for (let i = 0; i < 4; i++) {
        const dot = document.getElementById('pd' + i);
        if (dot) dot.classList.toggle('active', i < panicSequence.length);
    }
}

/* =====================================================
   PROFİL
   ===================================================== */
function saveProfile() {
    const ad = document.getElementById('pf-ad').value.trim();
    const tel = document.getElementById('pf-tel').value.trim();
    const dogum = document.getElementById('pf-dogum').value;
    const kan = document.getElementById('pf-kan').value;
    const hastalik = document.getElementById('pf-hastalik').value.trim();
    const ilac = document.getElementById('pf-ilac').value.trim();
    const alerji = document.getElementById('pf-alerji').value.trim();
    if (!ad) { showToast('⚠️ Ad Soyad boş olamaz!'); return; }
    const fd = new FormData();
    fd.append('ad_soyad', ad); fd.append('phone', tel); fd.append('dogum_tarihi', dogum);
    fd.append('kan_grubu', kan); fd.append('kronik_hastalik', hastalik);
    fd.append('ilac_kullanimi', ilac); fd.append('alerji', alerji);
    fetch('guncelle_profil.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'ok') { loadProfile(); showToast('✅ Profil kaydedildi!'); }
        else showToast('⚠️ ' + data.message);
    }).catch(() => showToast('⚠️ Bağlantı hatası'));
}

function loadProfile() {
    fetch('get_profile.php').then(r => r.json()).then(p => {
        if (p.status !== 'ok') return;
        if (p.ad_soyad) {
            document.getElementById('pf-ad').value = p.ad_soyad;
            document.getElementById('profileDisplayName').textContent = p.ad_soyad;
            const parts = p.ad_soyad.trim().split(' ');
            document.getElementById('profileAvatarBig').textContent = (parts[0][0] + (parts.length > 1 ? parts[parts.length-1][0] : '')).toUpperCase();
        }
        if (p.phone) { document.getElementById('pf-tel').value = p.phone; document.getElementById('profileDisplayPhone').textContent = p.phone; }
        if (p.dogum_tarihi) document.getElementById('pf-dogum').value = p.dogum_tarihi;
        if (p.kan_grubu) document.getElementById('pf-kan').value = p.kan_grubu;
        if (p.kronik_hastalik) document.getElementById('pf-hastalik').value = p.kronik_hastalik;
        if (p.ilac_kullanimi) document.getElementById('pf-ilac').value = p.ilac_kullanimi;
        if (p.alerji) document.getElementById('pf-alerji').value = p.alerji;
        document.getElementById('pil-kan').textContent      = p.kan_grubu       || '—';
        document.getElementById('pil-dogum').textContent    = p.dogum_tarihi    || '—';
        document.getElementById('pil-hastalik').textContent = p.kronik_hastalik || '—';
        document.getElementById('pil-ilac').textContent     = p.ilac_kullanimi  || '—';
        document.getElementById('pil-alerji').textContent   = p.alerji          || '—';
    }).catch(() => {});
}

/* =====================================================
   PROFİL MODAL
   ===================================================== */
let _pmPasswordVisible = false, _editPasswordVisible = false;

function openProfileModal() {
    document.getElementById('profileModal').style.display = 'flex';
    pmSwitchTab('info');
    ['pmAvatar','pmName','pmEmail','pmInfoAd','pmInfoMail','pmInfoTel'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.textContent = id === 'pmAvatar' ? '…' : '—';
    });
    _pmPasswordVisible = false;
    document.getElementById('pmInfoSifre').textContent = '•••••';
    document.getElementById('pmInfoSifre').setAttribute('data-real', '');
    document.getElementById('pmEyeIcon').className = 'fas fa-eye';
    _editPasswordVisible = false;
    if (document.getElementById('pm-sifre')) { document.getElementById('pm-sifre').value = ''; document.getElementById('pm-sifre').type = 'password'; }
    document.getElementById('editEyeIcon').className = 'fas fa-eye';
    fetch('get_profile.php').then(r => r.json()).then(data => {
        if (data.status !== 'ok') { showToast('⚠️ ' + (data.message || 'Profil yüklenemedi')); return; }
        const ad = data.ad_soyad || '', mail = data.email || '', tel = data.phone || '', sifre = data.sifre || '';
        const parts = ad.trim().split(' ');
        const initials = ad ? (parts[0][0] + (parts.length > 1 ? parts[parts.length-1][0] : '')).toUpperCase() : '??';
        document.getElementById('pmAvatar').textContent   = initials;
        document.getElementById('pmName').textContent     = ad   || 'Kullanıcı';
        document.getElementById('pmEmail').textContent    = mail || '—';
        document.getElementById('pmInfoAd').textContent   = ad   || '—';
        document.getElementById('pmInfoMail').textContent = mail || '—';
        document.getElementById('pmInfoTel').textContent  = tel  || '—';
        document.getElementById('pmInfoSifre').textContent = sifre ? '•••••' : '—';
        document.getElementById('pmInfoSifre').setAttribute('data-real', sifre);
        document.getElementById('pm-ad').value   = ad;
        document.getElementById('pm-mail').value = mail;
        document.getElementById('pm-tel').value  = tel;
        document.getElementById('pm-sifre').value = '';
    }).catch(() => showToast('⚠️ Sunucu bağlantı hatası'));
}

function closeProfileModal() { document.getElementById('profileModal').style.display = 'none'; }

function pmSwitchTab(tab) {
    if (tab === 'info') {
        document.getElementById('pmPanelInfo').style.display = 'flex';
        document.getElementById('pmPanelEdit').style.display = 'none';
        document.getElementById('pmTabInfo').style.cssText = 'flex:1;padding:13px;border:none;background:none;font-size:13px;font-weight:700;color:#ff2e7a;border-bottom:3px solid #ff2e7a;cursor:pointer;margin-bottom:-2px;';
        document.getElementById('pmTabEdit').style.cssText = 'flex:1;padding:13px;border:none;background:none;font-size:13px;font-weight:600;color:#aaa;border-bottom:3px solid transparent;cursor:pointer;margin-bottom:-2px;';
    } else {
        document.getElementById('pmPanelInfo').style.display = 'none';
        document.getElementById('pmPanelEdit').style.display = 'flex';
        document.getElementById('pmTabEdit').style.cssText = 'flex:1;padding:13px;border:none;background:none;font-size:13px;font-weight:700;color:#ff2e7a;border-bottom:3px solid #ff2e7a;cursor:pointer;margin-bottom:-2px;';
        document.getElementById('pmTabInfo').style.cssText = 'flex:1;padding:13px;border:none;background:none;font-size:13px;font-weight:600;color:#aaa;border-bottom:3px solid transparent;cursor:pointer;margin-bottom:-2px;';
    }
}

function togglePmPassword() {
    _pmPasswordVisible = !_pmPasswordVisible;
    const real = document.getElementById('pmInfoSifre').getAttribute('data-real') || '';
    document.getElementById('pmInfoSifre').textContent = _pmPasswordVisible ? (real || '—') : (real ? '•••••' : '—');
    document.getElementById('pmEyeIcon').className = _pmPasswordVisible ? 'fas fa-eye-slash' : 'fas fa-eye';
}

function toggleEditPassword() {
    _editPasswordVisible = !_editPasswordVisible;
    const input = document.getElementById('pm-sifre');
    input.type = _editPasswordVisible ? 'text' : 'password';
    document.getElementById('editEyeIcon').className = _editPasswordVisible ? 'fas fa-eye-slash' : 'fas fa-eye';
}

function saveProfileModal() {
    const ad = document.getElementById('pm-ad').value.trim();
    const mail = document.getElementById('pm-mail').value.trim();
    const tel = document.getElementById('pm-tel').value.trim();
    const sifre = document.getElementById('pm-sifre').value;
    if (!ad) { showToast('⚠️ Ad Soyad boş olamaz!'); return; }
    const fd = new FormData();
    fd.append('ad_soyad', ad); fd.append('email', mail); fd.append('phone', tel);
    if (sifre) fd.append('sifre', sifre);
    fetch('guncelle_profil.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.status !== 'ok') { showToast('⚠️ ' + data.message); return; }
        showToast('✅ Bilgiler güncellendi!');
        const parts = ad.trim().split(' ');
        const ini = (parts[0][0] + (parts.length > 1 ? parts[parts.length-1][0] : '')).toUpperCase();
        document.getElementById('pmAvatar').textContent   = ini;
        document.getElementById('pmName').textContent     = ad;
        document.getElementById('pmEmail').textContent    = mail || '—';
        document.getElementById('pmInfoAd').textContent   = ad;
        document.getElementById('pmInfoMail').textContent = mail || '—';
        document.getElementById('pmInfoTel').textContent  = tel  || '—';
        if (sifre) {
            document.getElementById('pmInfoSifre').setAttribute('data-real', sifre);
            _pmPasswordVisible = false;
            document.getElementById('pmInfoSifre').textContent = '•••••';
            document.getElementById('pmEyeIcon').className = 'fas fa-eye';
            document.getElementById('pm-sifre').value = '';
            document.getElementById('pm-sifre').type = 'password';
            _editPasswordVisible = false;
            document.getElementById('editEyeIcon').className = 'fas fa-eye';
        }
        const userNameEl = document.querySelector('.user-name');
        if (userNameEl) userNameEl.textContent = ad;
        const userAvatarEl = document.querySelector('.user-avatar');
        if (userAvatarEl) userAvatarEl.textContent = ini;
        if (document.getElementById('profileDisplayName')) document.getElementById('profileDisplayName').textContent = ad;
        if (document.getElementById('profileAvatarBig')) document.getElementById('profileAvatarBig').textContent = ini;
        if (document.getElementById('pf-ad')) document.getElementById('pf-ad').value = ad;
        pmSwitchTab('info');
    }).catch(() => showToast('⚠️ Sunucu bağlantı hatası'));
}

document.addEventListener('click', function(e) {
    const modal = document.getElementById('profileModal');
    if (modal && e.target === modal) closeProfileModal();
});

/* =====================================================
   ACİL DURUM KİŞİSİ
   ===================================================== */
function addContact() {
    const name = document.getElementById('ecName').value.trim();
    const phone = document.getElementById('ecPhone').value.trim();
    const rel = document.getElementById('ecRel').value.trim();
    if (!name || !phone) { showToast('⚠️ Ad ve telefon zorunludur!'); return; }
    let fd = new FormData();
    fd.append('name', name); fd.append('phone', phone); fd.append('rel', rel);
    fetch('add_contact_db.php', {method: 'POST', body: fd})
    .then(r => r.json())
    .then(data => {
        if (data.status === 'ok') {
            document.getElementById('ecName').value = '';
            document.getElementById('ecPhone').value = '';
            document.getElementById('ecRel').value = '';
            renderContacts();
            showToast('✅ ' + name + ' eklendi!');
        } else showToast('⚠️ ' + data.message);
    }).catch(() => showToast('⚠️ Bağlantı hatası'));
}

function renderContacts() {
    fetch('get_contacts_db.php').then(r => r.json()).then(data => {
        const list = data.contacts || [];
        const ul = document.getElementById('contactList');
        const noMsg = document.getElementById('noContactMsg');
        if (list.length === 0) { ul.innerHTML = ''; noMsg.style.display = 'block'; return; }
        noMsg.style.display = 'none';
        ul.innerHTML = list.map(c => `
            <li>
                <div class="c-info">
                    <div class="c-avatar">${initials(c.ad_soyad)}</div>
                    <div>
                        <div class="c-name">${esc(c.ad_soyad)} <small style="color:#8a2be2;font-size:10px;">${esc(c.yakinlik || '')}</small></div>
                        <div class="c-phone">${esc(c.telefon)}</div>
                    </div>
                </div>
                <div class="c-actions">
                    <button class="btn-call-ec" onclick="directCall('${esc(c.telefon)}')">📞 Ara</button>
                    <button class="btn-del-ec" onclick="deleteContact(${c.id})">🗑</button>
                </div>
            </li>`).join('');
    }).catch(() => document.getElementById('noContactMsg').style.display = 'block');
}

function deleteContact(id) {
    if (!confirm('Bu kişiyi silmek istediğinizden emin misiniz?')) return;
    fetch('delete_contact_db.php?contact_id=' + id).then(r => r.json()).then(data => {
        if (data.status === 'ok') { renderContacts(); showToast('🗑️ Kişi silindi.'); }
        else showToast('⚠️ ' + data.message);
    }).catch(() => showToast('⚠️ Bağlantı hatası'));
}

function initials(name) {
    const p = name.trim().split(' ');
    return (p[0][0] + (p.length > 1 ? p[p.length-1][0] : '')).toUpperCase();
}
function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* =====================================================
   ACİL BİLDİRİM
   ===================================================== */
function sendEmergencyAlert(type) {
    fetch('get_contacts_db.php').then(r => r.json()).then(data => {
        const contacts = data.contacts || [];
        if (contacts.length === 0) { showToast('⚠️ Önce bir acil durum kişisi ekleyin!'); return; }
        if (type === 'konum' || type === 'hepsi') {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    pos => sendByType(contacts, type, pos.coords.latitude.toFixed(5), pos.coords.longitude.toFixed(5)),
                    () => sendByType(contacts, type, null, null)
                );
            } else sendByType(contacts, type, null, null);
        } else sendByType(contacts, type, null, null);
    });
}

function sendByType(contacts, type, lat, lng) {
    const now = new Date().toLocaleString('tr-TR');
    if (type === 'konum') {
        let msg = `🚨 ACİL DURUM!\n\n📍 KONUMUM:\n`;
        msg += (lat && lng) ? `https://maps.google.com/?q=${lat},${lng}` : 'Konum alınamadı.';
        msg += `\n\n⏰ ${now}`;
        sendWhatsAppMessages(contacts, msg);
    } else if (type === 'ses') {
        fetch('ses_son.php').then(r=>r.json()).then(data => {
            let msg = `🚨 ACİL DURUM!\n\n🎤 SES KAYDI:\n`;
            msg += data.file_path ? `${window.location.origin}/${data.file_path}` : 'Ses kaydı bulunamadı.';
            msg += `\n\n⏰ ${now}`;
            sendWhatsAppMessages(contacts, msg);
        }).catch(() => sendWhatsAppMessages(contacts, `🚨 ACİL DURUM!\n\n🎤 Ses kaydı.\n\n⏰ ${now}`));
    } else if (type === 'foto') {
        fetch('foto_son.php').then(r=>r.json()).then(data => {
            let msg = `🚨 ACİL DURUM!\n\n📷 FOTOĞRAF:\n`;
            msg += data.file_path ? `${window.location.origin}/${data.file_path}` : 'Fotoğraf bulunamadı.';
            msg += `\n\n⏰ ${now}`;
            sendWhatsAppMessages(contacts, msg);
        }).catch(() => sendWhatsAppMessages(contacts, `🚨 ACİL DURUM!\n\n📷 Fotoğraf.\n\n⏰ ${now}`));
    } else if (type === 'hepsi') {
        let msg = `🚨 ACİL DURUM!\n\n`;
        if (lat && lng) msg += `📍 KONUM: https://maps.google.com/?q=${lat},${lng}\n`;
        msg += `🎤 Ses ve 📷 Fotoğraf kaydı da gönderildi.\n\n⏰ ${now}`;
        sendWhatsAppMessages(contacts, msg);
        fetch('ses_son.php').then(r=>r.json()).then(data => { if(data.file_path) sendWhatsAppMessages(contacts,`🎤 SES KAYDI:\n${window.location.origin}/${data.file_path}\n\n⏰ ${now}`); }).catch(()=>{});
        fetch('foto_son.php').then(r=>r.json()).then(data => { if(data.file_path) sendWhatsAppMessages(contacts,`📷 FOTOĞRAF:\n${window.location.origin}/${data.file_path}\n\n⏰ ${now}`); }).catch(()=>{});
    }
    const labels = { konum:'📍 Konum', ses:'🎤 Ses Kaydı', foto:'📷 Fotoğraf', hepsi:'📍🎤📷 Tümü' };
    document.getElementById('alertTitle').textContent = '🚨 Acil Bildirim Gönderildi!';
    document.getElementById('alertDesc').textContent  = (labels[type]||type) + ' → ' + contacts.map(c=>c.ad_soyad).join(', ') + ' kişilerine WP ile gönderildi.';
    document.getElementById('alertBanner').classList.add('show');
    showToast('✅ ' + contacts.length + ' kişiye gönderildi!');
}

function sendWhatsAppMessage(phone, message) { sendWhatsAppMessages([{ telefon: phone }], message); }

function sendWhatsAppMessages(contacts, message) {
    const logs = JSON.parse(localStorage.getItem('safenova_sent_logs') || '[]');
    logs.unshift({ id: Date.now(), type: 'acil_durum', created_at: new Date().toLocaleString('tr-TR') });
    localStorage.setItem('safenova_sent_logs', JSON.stringify(logs));
    renderSentLogs();
    contacts.forEach((c, i) => {
        setTimeout(() => {
            const clean = c.telefon.replace(/\D/g, '');
            window.open(`https://wa.me/${clean}?text=${encodeURIComponent(message)}`, '_blank');
        }, i * 1500);
    });
    fetch('save_log.php', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'type=acil_durum'}).catch(()=>{});
}

function closeAlert() { document.getElementById('alertBanner').classList.remove('show'); }

/* =====================================================
   GÖNDERIM GEÇMİŞİ
   ===================================================== */
function renderSentLogs() {
    const logs = JSON.parse(localStorage.getItem('safenova_sent_logs') || '[]');
    const typeLabel = { 'konum':'📍 Konum','ses':'🎤 Ses Kaydı','foto':'📷 Fotoğraf','hepsi':'📍🎤📷 Tümü','acil_durum':'🚨 Acil Durum' };
    let html = '';
    if (logs.length === 0) {
        html = "<li class='log-empty' id='logEmpty'>Henüz gönderim yapılmadı.</li>";
    } else {
        logs.forEach(log => {
            html += `<li>
                <div class="log-content"><strong>${typeLabel[log.type]||log.type}</strong><span>${log.created_at}</span></div>
                <div class="log-actions"><button class="btn-delete-log" onclick="deleteLog(${log.id})">🗑 Sil</button></div>
            </li>`;
        });
    }
    document.getElementById('sentLogList').innerHTML = html;
}

function deleteLog(id) {
    let logs = JSON.parse(localStorage.getItem('safenova_sent_logs') || '[]');
    logs = logs.filter(l => l.id !== id);
    localStorage.setItem('safenova_sent_logs', JSON.stringify(logs));
    renderSentLogs();
    showToast('🗑️ Gönderim silindi');
    fetch('delete_log.php?log_id=' + id).catch(()=>{});
}

/* =====================================================
   KONUM
   ===================================================== */
let _currentLat = null, _currentLng = null;

function loadInlineMap() {
    if (!navigator.geolocation) { document.getElementById('inlineMapLoading').textContent = '⚠️ Konum desteklenmiyor.'; return; }
    navigator.geolocation.getCurrentPosition(function(pos) {
        _currentLat = pos.coords.latitude;
        _currentLng = pos.coords.longitude;
        const iframe = document.getElementById('inlineMap');
        iframe.src = `https://www.google.com/maps?q=${_currentLat},${_currentLng}&z=15&output=embed`;
        iframe.onload = function() { document.getElementById('inlineMapLoading').style.display = 'none'; };
    }, function() { document.getElementById('inlineMapLoading').textContent = '⚠️ Konum alınamadı.'; });
}

function openLiveLocation() {
    if (_currentLat && _currentLng) openLocationMap(_currentLat, _currentLng);
    else if (navigator.geolocation) navigator.geolocation.getCurrentPosition(pos => openLocationMap(pos.coords.latitude, pos.coords.longitude), () => showToast('⚠️ Konum alınamadı!'));
    else showToast('⚠️ Tarayıcınız konum desteklemiyor!');
}

function openLocationMap(lat, lng) {
    document.getElementById('liveLocationMap').src = `https://www.google.com/maps?q=${lat},${lng}&z=16&output=embed`;
    document.getElementById('locationModal').classList.add('show');
}

function closeLiveLocation() { document.getElementById('locationModal').classList.remove('show'); }
function callPolice() { if (confirm('155 Emniyet hattını aramak istediğinizden emin misiniz?')) window.location.href = 'tel:155'; }
function directCall(phone) { window.location.href = 'tel:' + phone.replace(/\s/g,''); }

/* =====================================================
   TOAST
   ===================================================== */
let toastTimer;
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.style.display = 'block';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { t.style.display = 'none'; }, 3500);
}

/* =====================================================
   İHBAR
   ===================================================== */
function ihbarYap() {
    if (!navigator.geolocation) { alert('Tarayıcınız konum desteklemiyor.'); return; }
    navigator.geolocation.getCurrentPosition(function(pos) {
        let fd = new FormData();
        fd.append('lat', pos.coords.latitude); fd.append('lng', pos.coords.longitude);
        fetch('ihbar_kaydet.php',{method:'POST',body:fd}).then(r=>r.json()).then(data => {
            if (data.status==='ok') { showToast('✅ İhbarınız kaydedildi!'); ihbarSayisiniGuncelle(); }
            else showToast('Hata: '+data.message);
        });
    }, function(){ alert('Konum alınamadı. Lütfen konum iznini açın.'); });
}

function sonIhbarlariGoster() {
    const popup = document.getElementById('ihbarPopup');
    popup.classList.add('show');
    const listEl = document.getElementById('ihbarListesi');
    listEl.innerHTML = '<div style="text-align:center;color:#aaa;padding:20px;"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</div>';
    fetch('ihbar_listesi.php').then(r => r.json()).then(data => {
        if (!data.ihbarlar || data.ihbarlar.length === 0) {
            listEl.innerHTML = "<p style='color:#999;text-align:center;padding:20px 0;'>Henüz ihbar bulunmuyor.</p>";
        } else {
            const currentUserId = "<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>";
            listEl.innerHTML = data.ihbarlar.map(i => {
                const isMine = (i.user_id == currentUserId);
                return `<div class="report-item" style="${isMine ? 'border-left:4px solid #ff2e7a;' : 'border-left:4px solid #2e7aff;'}">
                    <div class="report-item-info">
                        <p style="font-weight:bold;color:${isMine ? '#ff2e7a' : '#2e7aff'};">${isMine ? '📌 İhbarım' : '⚠️ Çevre İhbarı'}</p>
                        <p class="time">${i.created_at}</p>
                    </div>
                    <div class="report-item-actions">
                        <button class="btn-view-report" onclick="closeIhbarPopup();openLocationMap(${i.lat},${i.lng});">🗺 Haritada Aç</button>
                        ${isMine ? `<button class="btn-delete-report" onclick="deleteReport(${i.id})">🗑 Sil</button>` : ''}
                    </div>
                </div>`;
            }).join('');
        }
    }).catch(() => { listEl.innerHTML = "<p style='color:#d32f2f;text-align:center;'>⚠️ Bir hata oluştu.</p>"; });
}

function closeIhbarPopup(){ document.getElementById('ihbarPopup').classList.remove('show'); }

function deleteReport(id){
    if(confirm('Bu ihbarı silmek istediğine emin misin?')){
        fetch('delete_report.php?report_id='+id).then(r=>r.json()).then(data => {
            showToast(data.status==='ok' ? '🗑️ İhbar silindi' : '⚠️ '+data.message);
            sonIhbarlariGoster();
        }).catch(()=>{ showToast('⚠️ Silme işlemi başarısız'); });
    }
}

function ihbarSayisiniGuncelle(){
    fetch('ihbar_listesi.php').then(r=>r.json()).then(data => {
        if(data.ihbarlar) document.getElementById('ihbar-sayi').innerText=data.ihbarlar.length;
    });
}

/* =====================================================
   MEDYA PANELİ
   ===================================================== */
function openMediaPanel(type, linkEl) {
    document.querySelectorAll('.menu li').forEach(li=>li.classList.remove('active'));
    if(linkEl && linkEl.closest && linkEl.closest('li')) linkEl.closest('li').classList.add('active');
    if (type === 'ses') {
        document.getElementById('sesPanel').classList.add('show');
        sesKayitlariniGoster();
    } else if (type === 'foto') {
        document.getElementById('fotoPanel').classList.add('show');
        fotograflariGoster();
    } else if (type === 'bulusma') {
        document.getElementById('bulusmaPanel').classList.add('show');
        bulusmaListele();
    }
}

function closeMediaPanel(type) {
    if (type === 'ses') {
        document.getElementById('sesPanel').classList.remove('show');
        if(mediaRecorder && mediaRecorder.state==='recording') mediaRecorder.stop();
    } else if (type === 'foto') {
        document.getElementById('fotoPanel').classList.remove('show');
        if(camStream2) { camStream2.getTracks().forEach(t=>t.stop()); camStream2=null; }
        document.getElementById('mp-camera-area').style.display = 'none';
    } else if (type === 'bulusma') {
        document.getElementById('bulusmaPanel').classList.remove('show');
    }
}

/* =====================================================
   SES KAYITLARI
   ===================================================== */
function sesKayitlariniGoster(){
    const listEl = document.getElementById('sesListesi');
    listEl.innerHTML = '<div class="mp-loading"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</div>';
    fetch('ses_listesi.php').then(r=>r.json()).then(data => {
        if(!data.kayitlar || data.kayitlar.length===0){
            listEl.innerHTML = "<div class='mp-empty'>🎤 Henüz ses kaydı bulunmuyor.<br><small style='color:#ccc;'>Yukarıdan yeni kayıt başlatabilirsiniz.</small></div>";
            return;
        }
        listEl.style.cssText = 'display:flex;flex-direction:column;gap:16px;';
        listEl.innerHTML = data.kayitlar.map((k,i) => `
            <div class="audio-item">
                <div class="audio-item-header">
                    <div class="info">
                        <p>🎤 Kayıt ${i+1}</p>
                        <p class="time">${k.created_at??''}</p>
                    </div>
                    <div class="actions">
                        <button class="btn-send-audio" onclick="sendAudioToContacts('${k.file_path}')"><i class="fas fa-share-alt"></i> Gönder</button>
                        <button class="btn-delete-audio" onclick="deleteAudio(${k.id})"><i class="fas fa-trash"></i> Sil</button>
                    </div>
                </div>
                <div class="audio-controls">
                    <audio id="player_${k.id}" controls style="width:100%;border-radius:8px;"></audio>
                </div>
            </div>`
        ).join('');

        // Ses dosyasını direkt file_path ile yükle
        data.kayitlar.forEach(k => {
            const audio = document.getElementById('player_' + k.id);
            if(!audio) return;
            const base = window.location.href.substring(0, window.location.href.lastIndexOf('/') + 1);
            const src  = k.file_path.startsWith('http') ? k.file_path : base + k.file_path.replace(/^\/+/, '');
            // Uzantıya göre doğru type belirle
            const ext = src.split('.').pop().toLowerCase();
            const typeMap = { mp4:'audio/mp4', m4a:'audio/mp4', webm:'audio/webm', ogg:'audio/ogg', mp3:'audio/mpeg', wav:'audio/wav' };
            const mime = typeMap[ext] || 'audio/mp4';
            // Direkt src ver - en güvenilir yöntem
            audio.src = src;
            audio.type = mime;
            audio.load();
        });
    }).catch(()=>{ listEl.innerHTML="<div class='mp-empty'>⚠️ Kayıtlar yüklenemedi.</div>"; });
}

let mediaRecorder, audioChunks=[];

function getSupportedMimeType(){
    // Önce mp4/m4a dene (Safari/iOS için), sonra webm (Chrome), sonra ogg
    const types=[
        'audio/mp4',
        'audio/webm;codecs=opus',
        'audio/webm',
        'audio/ogg;codecs=opus',
        'audio/ogg'
    ];
    for(const t of types){
        try{ if(MediaRecorder.isTypeSupported(t)) return t; }catch(e){}
    }
    return '';
}

let recorderStream = null;

function startRecording2(){
    const statusEl=document.getElementById('mpStatusText');
    const startBtn=document.getElementById('mpStartBtn');
    const stopBtn=document.getElementById('mpStopBtn');
    statusEl.textContent='🎙️ Kayıt yapılıyor...';
    startBtn.disabled=true; stopBtn.disabled=false;

    navigator.mediaDevices.getUserMedia({audio:true, video:false}).then(stream=>{
        recorderStream = stream;
        const mimeType = getSupportedMimeType();
        console.log('Kullanılan format:', mimeType || 'tarayıcı varsayılanı');

        try {
            mediaRecorder = mimeType ? new MediaRecorder(stream,{mimeType}) : new MediaRecorder(stream);
        } catch(e) {
            mediaRecorder = new MediaRecorder(stream);
        }

        audioChunks=[];
        mediaRecorder.ondataavailable=e=>{ if(e.data && e.data.size>0) audioChunks.push(e.data); };

        mediaRecorder.onstop=function(){
            // Stream'i durdur
            if(recorderStream){ recorderStream.getTracks().forEach(t=>t.stop()); recorderStream=null; }

            if(audioChunks.length===0){
                statusEl.textContent='⚠️ Ses verisi alınamadı!';
                startBtn.disabled=false; stopBtn.disabled=true; return;
            }

            const usedMime = mediaRecorder.mimeType || mimeType || 'audio/webm';
            const ext = usedMime.includes('mp4') ? 'mp4' : usedMime.includes('ogg') ? 'ogg' : 'webm';
            const blob = new Blob(audioChunks, {type: usedMime});

            statusEl.textContent='✅ Kaydedildi! (' + Math.round(blob.size/1024) + ' KB · ' + ext.toUpperCase() + ')';
            startBtn.disabled=false; stopBtn.disabled=true;

            // Anında önizleme — blob URL ile çal (format ne olursa olsun tarayıcı bilir)
            const blobUrl = URL.createObjectURL(blob);
            const preview = document.getElementById('kayitOnizleme');
            const onizDiv = document.getElementById('onizlemeDiv');
            if(preview){ preview.src=blobUrl; preview.style.display='block'; preview.load(); }
            if(onizDiv){ onizDiv.style.display='block'; }

            // Sunucuya gönder
            sendAudio(blob, ext, usedMime);

            // 2 sn sonra listeyi yenile
            setTimeout(()=>sesKayitlariniGoster(), 2000);
        };

        mediaRecorder.start(250); // her 250ms'de chunk al
    }).catch((err)=>{
        alert('Mikrofon izni verilmedi!\nHata: '+err.message);
        startBtn.disabled=false; stopBtn.disabled=true; statusEl.textContent='';
    });
}

function stopRecording2(){
    if(mediaRecorder && mediaRecorder.state==='recording') mediaRecorder.stop();
}

function sendAudio(blob, ext, mimeType){
    if(!ext) ext='webm';
    if(!mimeType) mimeType='audio/webm';
    const fd=new FormData();
    fd.append('audio', new File([blob], 'record.'+ext, {type: mimeType}));
    fetch('save_audio.php',{method:'POST',body:fd})
        .then(r=>r.json())
        .then(d=>{
            if(d.status!=='ok') showToast('⚠️ Ses kaydedilemedi: '+(d.message||''));
            else console.log('Ses kaydedildi:', d.file_path);
        })
        .catch(e=>{ showToast('⚠️ Ses sunucuya gönderilemedi!'); console.error(e); });
}

function deleteAudio(id){
    if(confirm('Bu ses kaydını silmek istediğine emin misin?')){
        fetch('delete_audio.php?audio_id='+id).then(r=>r.json()).then(data => {
            showToast(data.status==='ok' ? '🗑️ Ses kaydı silindi' : '⚠️ '+data.message);
            sesKayitlariniGoster();
        }).catch(()=>{ showToast('⚠️ Silme işlemi başarısız'); });
    }
}

function sendAudioToContacts(audioPath){
    fetch('get_contacts_db.php').then(r => r.json()).then(data => {
        const contacts = data.contacts || [];
        if(contacts.length===0){ showToast('⚠️ Önce bir acil durum kişisi ekleyin!'); return; }
        const now=new Date().toLocaleString('tr-TR');
        const fullUrl = audioPath.startsWith('http') ? audioPath : window.location.origin + '/' + audioPath;
        sendWhatsAppMessages(contacts, `🚨 ACİL DURUM!\n\n🎤 SES KAYDI:\n${fullUrl}\n\n⏰ ${now}`);
        showToast('✅ Ses kaydı ' + contacts.length + ' kişiye gönderildi!');
    }).catch(()=>{ showToast('⚠️ Kontaklar yüklenemedi'); });
}

/* =====================================================
   FOTOĞRAFLAR
   ===================================================== */
function fotoUrl(path) {
    if (!path) return '';
    if (path.startsWith('http')) return path;
    const base = window.location.href.substring(0, window.location.href.lastIndexOf('/') + 1);
    const clean = path.replace(/^\/+/, '');
    return base + clean;
}

function acFotoBuyuk(url) {
    const overlay = document.getElementById('fotoBuyukOverlay');
    document.getElementById('fotoBuyukImg').src = url;
    overlay.style.display = 'flex';
}

function fotograflariGoster(){
    const listEl = document.getElementById('fotoListesi');
    listEl.innerHTML = '<div class="mp-loading"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</div>';
    fetch('foto_listesi.php').then(r=>r.json()).then(data => {
        if(!data.fotograflar||data.fotograflar.length===0){
            listEl.innerHTML="<div class='mp-empty'>📷 Henüz fotoğraf bulunmuyor.<br><small style='color:#ccc;'>Yukarıdan kamerayı açıp fotoğraf çekebilirsiniz.</small></div>";
            return;
        }
        listEl.style.cssText='display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;';
        listEl.innerHTML = data.fotograflar.map(f => {
            const url = fotoUrl(f.file_path);
            return `
            <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 14px rgba(0,0,0,0.08);display:flex;flex-direction:column;border-left:4px solid #8a2be2;">
                <div style="position:relative;cursor:pointer;" onclick="acFotoBuyuk('${url}')">
                    <img src="${url}" alt="Fotoğraf"
                        style="width:100%;height:200px;object-fit:cover;display:block;background:#f0f0f0;"
                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                    <div style="display:none;width:100%;height:200px;background:#f5f0ff;align-items:center;justify-content:center;flex-direction:column;gap:8px;color:#aaa;font-size:13px;">
                        <i class="fas fa-image" style="font-size:32px;color:#d0c0e8;"></i>
                        Fotoğraf yüklenemedi
                    </div>
                    <div style="position:absolute;top:8px;right:8px;background:rgba(0,0,0,0.45);border-radius:8px;padding:4px 8px;color:white;font-size:11px;display:flex;align-items:center;gap:4px;">
                        <i class="fas fa-expand"></i> Büyüt
                    </div>
                </div>
                <div style="padding:12px 16px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-size:11px;color:#999;"><i class="fas fa-clock"></i> ${f.created_at??''}</span>
                    <div style="display:flex;gap:6px;">
                        <button class="btn-send-photo" onclick="sendPhotoToContacts('${f.file_path}')" style="padding:6px 12px;font-size:11px;"><i class="fas fa-share-alt"></i> Gönder</button>
                        <button class="btn-delete-photo" onclick="deletePhoto(${f.id})" style="padding:6px 12px;font-size:11px;"><i class="fas fa-trash"></i> Sil</button>
                    </div>
                </div>
            </div>`;
        }).join('');
    }).catch(()=>{ listEl.innerHTML="<div class='mp-empty'>⚠️ Fotoğraflar yüklenemedi.</div>"; });
}

let camStream2 = null;

function startCamera2(){
    document.getElementById('mp-camera-area').style.display='block';
    navigator.mediaDevices.getUserMedia({video:true})
    .then(s=>{ camStream2=s; document.getElementById('mp-camera').srcObject=camStream2; })
    .catch(()=>alert('Kamera açılmadı!'));
}

function takePhoto2(){
    let video=document.getElementById('mp-camera');
    let canvas=document.getElementById('mp-canvas');
    canvas.width=video.videoWidth||640;
    canvas.height=video.videoHeight||480;
    canvas.getContext('2d').drawImage(video,0,0);
    canvas.toBlob(function(blob){
        let fd=new FormData();
        fd.append('photo',blob,'photo.png');
        fetch('save_photo.php',{method:'POST',body:fd}).then(r=>r.json()).then(()=>{
            document.getElementById('mpPhotoStatus').textContent='✅ Fotoğraf kaydedildi!';
            setTimeout(()=>{ document.getElementById('mpPhotoStatus').textContent=''; fotograflariGoster(); }, 1000);
        }).catch(()=>{
            document.getElementById('mpPhotoStatus').textContent='⚠️ Fotoğraf kaydedilemedi!';
        });
    },'image/png');
}

function deletePhoto(id){
    if(confirm('Bu fotoğrafı silmek istediğine emin misin?')){
        fetch('delete_photo.php?photo_id='+id).then(r=>r.json()).then(data => {
            showToast(data.status==='ok' ? '🗑️ Fotoğraf silindi' : '⚠️ '+data.message);
            fotograflariGoster();
        }).catch(()=>{ showToast('⚠️ Silme işlemi başarısız'); });
    }
}

function sendPhotoToContacts(photoPath){
    fetch('get_contacts_db.php').then(r => r.json()).then(data => {
        const contacts = data.contacts || [];
        if(contacts.length===0){ showToast('⚠️ Önce bir acil durum kişisi ekleyin!'); return; }
        const now=new Date().toLocaleString('tr-TR');
        const fullUrl = photoPath.startsWith('http') ? photoPath : window.location.origin + '/' + photoPath;
        sendWhatsAppMessages(contacts, `🚨 ACİL DURUM!\n\n📷 FOTOĞRAF:\n${fullUrl}\n\n⏰ ${now}`);
        showToast('✅ Fotoğraf ' + contacts.length + ' kişiye gönderildi!');
    }).catch(()=>{ showToast('⚠️ Kontaklar yüklenemedi'); });
}

/* =====================================================
   SAHTE ARAMA
   ===================================================== */
let callInterval, callSecs=0;
let _muteOn=false, _speakerOn=false, _realTimeInterval=null;

function startFakeCall(){
    const overlay = document.getElementById('phoneCallOverlay');
    overlay.style.display = 'flex';
    function updatePhoneTime(){
        const now=new Date();
        document.getElementById('phoneRealTime').textContent=now.getHours().toString().padStart(2,'0')+':'+now.getMinutes().toString().padStart(2,'0');
    }
    updatePhoneTime();
    _realTimeInterval=setInterval(updatePhoneTime,10000);
    document.getElementById('phoneCallState').textContent='Arıyor...';
    document.getElementById('phoneCallTimer').style.display='none';
    let ring=document.getElementById('ringtone');
    ring.play(); callSecs=0;
    setTimeout(()=>{
        ring.pause(); ring.currentTime=0;
        document.getElementById('fakeCallAudio').play();
        document.getElementById('phoneCallState').textContent='Bağlandı';
        document.getElementById('phoneCallTimer').style.display='block';
        callInterval=setInterval(()=>{
            callSecs++;
            let m=String(Math.floor(callSecs/60)).padStart(2,'0');
            let s=String(callSecs%60).padStart(2,'0');
            document.getElementById('phoneCallTimer').textContent=m+':'+s;
        },1000);
    },3000);
}

function endCall(){
    document.getElementById('phoneCallOverlay').style.display='none';
    clearInterval(callInterval); clearInterval(_realTimeInterval);
    document.getElementById('ringtone').pause(); document.getElementById('ringtone').currentTime=0;
    document.getElementById('fakeCallAudio').pause(); document.getElementById('fakeCallAudio').currentTime=0;
    _muteOn=false; _speakerOn=false;
}

function toggleMute(){
    _muteOn=!_muteOn;
    const el=document.getElementById('muteIcon');
    el.style.background=_muteOn ? 'rgba(255,255,255,0.85)' : 'rgba(255,255,255,0.18)';
    el.querySelector('i').style.color=_muteOn ? '#1c1c2e' : 'white';
}

function toggleSpeaker(){
    _speakerOn=!_speakerOn;
    const el=document.getElementById('speakerIcon');
    el.style.background=_speakerOn ? 'rgba(255,255,255,0.85)' : 'rgba(255,255,255,0.18)';
    el.querySelector('i').style.color=_speakerOn ? '#1c1c2e' : 'white';
}

/* =====================================================
   GÜVENLİ NOKTALAR
   ===================================================== */
function openGuvenliPopup() { document.getElementById('guvenliPopup').style.display = 'flex'; }
function closeMap() { document.getElementById('guvenliPopup').style.display = 'none'; }

function acGuvenliNokta(tip) {
    const aramalar = { polis:'polis karakolu', hastane:'hastane', eczane:'eczane' };
    const arama = aramalar[tip] || tip;
    if (navigator.geolocation) {
        showToast('📍 Konum alınıyor...');
        navigator.geolocation.getCurrentPosition(function(pos) {
            const lat=pos.coords.latitude, lng=pos.coords.longitude;
            window.open('https://www.google.com/maps/search/' + encodeURIComponent(arama) + '/@' + lat + ',' + lng + ',14z', '_blank');
            closeMap();
        }, function() {
            window.open('https://www.google.com/maps/search/' + encodeURIComponent(arama), '_blank');
            showToast('⚠️ Konum alınamadı, genel arama açıldı.'); closeMap();
        });
    } else {
        window.open('https://www.google.com/maps/search/' + encodeURIComponent(arama), '_blank');
        closeMap();
    }
}

function scrollToSection(id, linkEl){
    document.querySelectorAll('.menu li').forEach(li=>li.classList.remove('active'));
    if(linkEl && linkEl.closest) linkEl.closest('li').classList.add('active');
    const target=document.getElementById(id);
    if(!target) return;
    target.scrollIntoView({behavior:'smooth',block:'start'});
}

function showSection(section, linkEl) {
    document.querySelectorAll('.mobile-nav a').forEach(a=>a.classList.remove('active'));
    if(linkEl) linkEl.classList.add('active');
    if(section==='dashboard')  scrollToSection('top-section', null);
    else if(section==='emergency') scrollToSection('acil-section', null);
    else if(section==='profile')   scrollToSection('profil-section', null);
}

/* =====================================================
   BULUŞMA TAKİBİ
   ===================================================== */
function bulusmaKaydet() {
    const id       = document.getElementById('bf-id').value;
    const kisi_adi = document.getElementById('bf-kisi').value.trim();
    const yakinlik = document.getElementById('bf-yakinlik').value;
    const adres    = document.getElementById('bf-adres').value.trim();
    const tarih    = document.getElementById('bf-tarih').value;
    const saat     = document.getElementById('bf-saat').value;
    const suphe    = document.getElementById('bf-suphe').value.trim();

    if (!kisi_adi || !adres || !tarih || !saat) {
        showToast('⚠️ Kişi adı, adres, tarih ve saat zorunludur!');
        return;
    }

    const fd = new FormData();
    if (id) fd.append('id', id);
    fd.append('kisi_adi', kisi_adi);
    fd.append('yakinlik', yakinlik);
    fd.append('adres',    adres);
    fd.append('tarih',    tarih);
    fd.append('saat',     saat);
    fd.append('suphe',    suphe);

    const url = id ? 'bulusma_guncelle.php' : 'bulusma_kaydet.php';

    fetch(url, { method: 'POST', body: fd })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'ok') {
            showToast(id ? '✅ Buluşma güncellendi!' : '✅ Buluşma kaydedildi!');
            bulusmaFormTemizle();
            bulusmaListele();
        } else {
            showToast('⚠️ ' + (data.message || 'Hata oluştu'));
        }
    })
    .catch(() => showToast('⚠️ Bağlantı hatası'));
}

function bulusmaListele() {
    const listEl = document.getElementById('bulusmaListesi');
    listEl.innerHTML = '<div class="mp-loading"><i class="fas fa-spinner fa-spin"></i> Yükleniyor...</div>';
    fetch('bulusma_listele.php')
    .then(r => r.json())
    .then(data => {
        if (!data.bulusmalar || data.bulusmalar.length === 0) {
            listEl.innerHTML = "<div class='mp-empty'>📅 Henüz buluşma kaydı yok.</div>";
            return;
        }
        const yakinlikRenk = { 'Arkadaş':'#2e7aff', 'Aile':'#43a047', 'İş Arkadaşı':'#ff9800', 'Tanıdık':'#9c27b0', 'Yabancı':'#d32f2f', 'Diğer':'#888' };
        listEl.innerHTML = data.bulusmalar.map(b => {
            const renk = yakinlikRenk[b.yakinlik] || '#888';
            const tarihGoster = b.bulusma_tarihi ? b.bulusma_tarihi.split('-').reverse().join('.') : '—';
            const saatGoster  = b.bulusma_saati  ? b.bulusma_saati.substring(0,5) : '—';
            return `<div style="background:white;border-radius:16px;padding:16px 18px;margin-bottom:12px;box-shadow:0 4px 14px rgba(0,0,0,0.07);border-left:4px solid ${renk};position:relative;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;">
                    <div style="flex:1;">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;flex-wrap:wrap;">
                            <span style="font-size:15px;font-weight:700;color:#1a0033;">${escHtml(b.kisi_adi)}</span>
                            ${b.yakinlik ? `<span style="background:${renk}22;color:${renk};border-radius:20px;padding:2px 10px;font-size:11px;font-weight:700;">${escHtml(b.yakinlik)}</span>` : ''}
                        </div>
                        <div style="font-size:12px;color:#555;display:flex;flex-direction:column;gap:4px;">
                            <span><i class="fas fa-map-marker-alt" style="color:#ff2e7a;width:14px;"></i> ${escHtml(b.adres)}</span>
                            <span><i class="fas fa-calendar-alt" style="color:#8a2be2;width:14px;"></i> ${tarihGoster} · ${saatGoster}</span>
                            ${b.suphe_nedeni ? `<span style="margin-top:4px;background:#fff3cd;border-radius:8px;padding:5px 9px;color:#856404;font-size:11px;"><i class="fas fa-exclamation-triangle" style="color:#ff9800;"></i> ${escHtml(b.suphe_nedeni)}</span>` : ''}
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        <button onclick="bulusmaDetay(${JSON.stringify(b).replace(/"/g,'&quot;')})" style="border:none;border-radius:9px;padding:6px 11px;background:#e8f5e9;color:#388e3c;cursor:pointer;font-size:11px;font-family:Arial;"><i class="fas fa-eye"></i></button>
                        <button onclick="bulushaDuzenle(${JSON.stringify(b).replace(/"/g,'&quot;')})" style="border:none;border-radius:9px;padding:6px 11px;background:#e3f2fd;color:#1565c0;cursor:pointer;font-size:11px;font-family:Arial;"><i class="fas fa-edit"></i></button>
                        <button onclick="bulushaSil(${b.id})" style="border:none;border-radius:9px;padding:6px 11px;background:#fde8e8;color:#d32f2f;cursor:pointer;font-size:11px;font-family:Arial;"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>`;
        }).join('');
    })
    .catch(() => { listEl.innerHTML = "<div class='mp-empty'>⚠️ Yüklenemedi.</div>"; });
}

function bulushaSil(id) {
    if (!confirm('Bu buluşma kaydını silmek istediğine emin misin?')) return;
    fetch('bulusma_sil.php?id=' + id)
    .then(r => r.json())
    .then(data => {
        showToast(data.status === 'ok' ? '🗑️ Buluşma silindi' : '⚠️ ' + data.message);
        bulusmaListele();
    })
    .catch(() => showToast('⚠️ Silme başarısız'));
}

function bulushaDuzenle(b) {
    document.getElementById('bf-id').value            = b.id;
    document.getElementById('bf-kisi').value          = b.kisi_adi   || '';
    document.getElementById('bf-yakinlik').value      = b.yakinlik   || '';
    document.getElementById('bf-adres').value         = b.adres      || '';
    document.getElementById('bf-tarih').value         = b.bulusma_tarihi || '';
    document.getElementById('bf-saat').value          = b.bulusma_saati  ? b.bulusma_saati.substring(0,5) : '';
    document.getElementById('bf-suphe').value         = b.suphe_nedeni || '';
    document.getElementById('bulusmaFormBaslik').innerHTML = '<i class="fas fa-edit"></i> Buluşmayı Düzenle';
    document.getElementById('bfBtnText').textContent  = 'Güncelle';
    document.getElementById('bfIptalBtn').style.display = 'block';
    document.querySelector('#bulusmaPanel .media-panel-body').scrollTo({ top: 0, behavior: 'smooth' });
}

function bulusmaFormTemizle() {
    document.getElementById('bf-id').value       = '';
    document.getElementById('bf-kisi').value     = '';
    document.getElementById('bf-yakinlik').value = '';
    document.getElementById('bf-adres').value    = '';
    document.getElementById('bf-tarih').value    = '';
    document.getElementById('bf-saat').value     = '';
    document.getElementById('bf-suphe').value    = '';
    document.getElementById('bulusmaFormBaslik').innerHTML = '<i class="fas fa-plus-circle"></i> Yeni Buluşma Ekle';
    document.getElementById('bfBtnText').textContent       = 'Kaydet';
    document.getElementById('bfIptalBtn').style.display    = 'none';
}

function bulusmaDetay(b) {
    const tarihGoster = b.bulusma_tarihi ? b.bulusma_tarihi.split('-').reverse().join('.') : '—';
    const saatGoster  = b.bulusma_saati  ? b.bulusma_saati.substring(0,5) : '—';
    const yakinlikRenk = { 'Arkadaş':'#2e7aff', 'Aile':'#43a047', 'İş Arkadaşı':'#ff9800', 'Tanıdık':'#9c27b0', 'Yabancı':'#d32f2f', 'Diğer':'#888' };
    const renk = yakinlikRenk[b.yakinlik] || '#888';
    document.getElementById('bulusmaDetayIcerik').innerHTML = `
        <div style="display:flex;align-items:center;gap:12px;padding-bottom:14px;border-bottom:1px solid #ffeaf3;">
            <div style="width:54px;height:54px;border-radius:50%;background:linear-gradient(135deg,#ff4fa0,#8a2be2);display:flex;align-items:center;justify-content:center;color:white;font-size:20px;font-weight:800;">
                ${(b.kisi_adi||'?')[0].toUpperCase()}
            </div>
            <div>
                <div style="font-size:17px;font-weight:800;color:#1a0033;">${escHtml(b.kisi_adi)}</div>
                ${b.yakinlik ? `<span style="background:${renk}22;color:${renk};border-radius:20px;padding:2px 10px;font-size:12px;font-weight:700;">${escHtml(b.yakinlik)}</span>` : ''}
            </div>
        </div>
        ${detayRow('fas fa-map-marker-alt','#ff2e7a','Adres', b.adres)}
        ${detayRow('fas fa-calendar-alt','#8a2be2','Tarih & Saat', tarihGoster + ' · ' + saatGoster)}
        ${b.suphe_nedeni ? `<div style="background:#fff3cd;border-radius:12px;padding:12px 14px;">
            <div style="font-size:11px;color:#856404;font-weight:700;margin-bottom:4px;"><i class="fas fa-exclamation-triangle"></i> Şüphe Nedeni</div>
            <div style="font-size:13px;color:#5d4000;">${escHtml(b.suphe_nedeni)}</div>
        </div>` : ''}
        <div style="display:flex;gap:10px;margin-top:6px;">
            <button onclick="closeBulusmaDetay();bulushaDuzenle(${JSON.stringify(b).replace(/"/g,"'")})" style="flex:1;padding:12px;border:none;border-radius:12px;background:linear-gradient(135deg,#1565c0,#1e88e5);color:white;font-size:14px;font-weight:700;cursor:pointer;font-family:Arial;"><i class="fas fa-edit"></i> Düzenle</button>
            <button onclick="closeBulusmaDetay();bulushaSil(${b.id})" style="flex:1;padding:12px;border:none;border-radius:12px;background:#fde8e8;color:#d32f2f;font-size:14px;font-weight:700;cursor:pointer;font-family:Arial;"><i class="fas fa-trash"></i> Sil</button>
        </div>`;
    document.getElementById('bulusmaDetayModal').style.display = 'flex';
}

function detayRow(icon, renk, label, value) {
    return `<div style="display:flex;align-items:flex-start;gap:10px;padding:10px 0;border-bottom:1px solid #ffeaf3;">
        <i class="${icon}" style="color:${renk};margin-top:2px;width:16px;"></i>
        <div><div style="font-size:11px;color:#aaa;font-weight:700;">${label}</div><div style="font-size:13px;color:#1a0033;font-weight:600;margin-top:2px;">${escHtml(String(value||'—'))}</div></div>
    </div>`;
}

function closeBulusmaDetay() {
    document.getElementById('bulusmaDetayModal').style.display = 'none';
}

function escHtml(s) {
    return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

document.addEventListener('click', function(e) {
    const m = document.getElementById('bulusmaDetayModal');
    if (m && e.target === m) closeBulusmaDetay();
});

/* =====================================================
   BAŞLANGIÇ
   ===================================================== */
window.onload = function(){
    ihbarSayisiniGuncelle();
    renderContacts();
    renderSentLogs();
    loadInlineMap();
    loadProfile();
};
</script>

<!-- MOBIL ALT NAV -->
<nav class="mobile-nav">
    <a onclick="showSection('dashboard',this)"><i class="fas fa-home"></i><span>Ana Sayfa</span></a>
    <a onclick="openMediaPanel('ses',this)"><i class="fas fa-microphone"></i><span>Ses</span></a>
    <a onclick="showSection('emergency',this)"><i class="fas fa-shield-alt"></i><span>Acil</span></a>
    <a onclick="openMediaPanel('foto',this)"><i class="fas fa-camera"></i><span>Foto</span></a>
    <a onclick="openMediaPanel('bulusma',this)"><i class="fas fa-calendar-alt"></i><span>Buluşma</span></a>
    <a onclick="showSection('profile',this)"><i class="fas fa-user"></i><span>Profil</span></a>
</nav>

</body>
</html>
