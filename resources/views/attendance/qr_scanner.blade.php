@extends('layouts.app')

@section('title', 'ماسح QR Code لتسجيل الحضور')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 bg-white p-3 rounded-4 shadow-sm border border-light">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                <i class="bi bi-qr-code-scan fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1">تسجيل الحضور الفوري عبر QR Code</h4>
                <p class="text-muted mb-0 small">امسح كود الطالب بكاميرا الموبايل أو الكومبيوتر لتسجيل حضوره وإضافة نقاطه فوراً</p>
            </div>
        </div>
        <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary rounded-3">
            <i class="bi bi-table me-1"></i> جدول الحضور الكلاسيكي
        </a>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- Scanner Box -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 text-center overflow-hidden">
                <div class="card-header bg-dark text-white p-3 d-flex align-items-center justify-content-between">
                    <span class="fw-bold"><i class="bi bi-camera-fill text-warning me-2"></i> كاميرا الماسح الضوئي</span>
                    <span class="badge bg-success rounded-pill" id="cameraStatus"><i class="bi bi-circle-fill me-1 small"></i> جاهز</span>
                </div>
                <div class="card-body p-4 bg-light">
                    <!-- Scanner Container -->
                    <div id="reader" class="rounded-4 border-2 border-primary border overflow-hidden mb-3 bg-black" style="min-height: 280px;"></div>

                    <!-- Manual Input Fallback -->
                    <div class="mt-3">
                        <label class="form-label small fw-bold text-muted">أو أدخل كود الطالب يدويًا / ماسح باركود خارجي:</label>
                        <form id="manualScanForm" class="d-flex gap-2">
                            <input type="text" id="studentCodeInput" class="form-control form-control-lg rounded-3 text-center fw-bold fs-4" placeholder="STU-101" autofocus required>
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 px-4 fw-bold">
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Result Box -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-3 rounded-top-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-person-check-fill text-success me-2"></i> نتيجة المسح الأخيرة</h5>
                </div>
                <div class="card-body p-4 text-center d-flex flex-column justify-content-center align-items-center" id="scanResultContainer">
                    <div id="initialState" class="py-4">
                        <div class="bg-light p-4 rounded-circle d-inline-block mb-3 text-muted">
                            <i class="bi bi-qr-code fs-1"></i>
                        </div>
                        <h6 class="fw-bold text-secondary">في انتظار مسح كود طالب...</h6>
                        <p class="text-muted small">قم بتوجيه الكاميرا نحو كود QR الخاص بالطالب</p>
                    </div>

                    <!-- Dynamic Success Result (Hidden by default) -->
                    <div id="successResult" class="d-none w-100">
                        <div class="alert alert-success rounded-4 p-3 mb-3 d-flex align-items-center gap-3 text-start">
                            <i class="bi bi-check-circle-fill fs-2"></i>
                            <div>
                                <h6 class="fw-bold mb-0" id="resMsg">تم تسجيل الحضور بنجاح!</h6>
                                <small id="resTime" class="text-muted"></small>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-4 border">
                            <img id="resAvatar" src="" class="rounded-circle mb-2 border border-3 border-success shadow-sm" width="90" height="90" alt="Avatar">
                            <h4 class="fw-bold text-dark mb-1" id="resName">--</h4>
                            <p class="badge bg-primary text-white rounded-pill px-3 py-1 mb-2 fs-6" id="resClass">--</p>
                            <div>
                                <small class="text-muted d-block">كود الطالب: <strong id="resCode">--</strong></small>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 mt-2">
                                    <i class="bi bi-star-fill text-dark me-1"></i> تم إضافة <strong id="resPoints">10</strong> نقاط حضور
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HTML5 QR Code Library CDN -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const html5QrCode = new Html5Qrcode("reader");

    function onScanSuccess(decodedText, decodedResult) {
        processStudentScan(decodedText);
    }

    // Start HTML5 Camera
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 220, height: 220 } },
        onScanSuccess
    ).catch(err => {
        console.log("Camera access not available or blocked, manual scanner ready.");
        document.getElementById('cameraStatus').innerText = 'يدوي فقط';
        document.getElementById('cameraStatus').className = 'badge bg-secondary rounded-pill';
        document.getElementById('reader').innerHTML = '<div class="text-white py-5 px-3"><i class="bi bi-camera-video-off fs-1 text-secondary mb-2 d-block"></i> الكاميرا غير مفعلة، استخدم إدخال الكود يدويًا بأعلى.</div>';
    });

    // Handle Manual Input Submit
    document.getElementById('manualScanForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const code = document.getElementById('studentCodeInput').value;
        if (code) {
            processStudentScan(code);
            document.getElementById('studentCodeInput').value = '';
        }
    });

    function processStudentScan(code) {
        fetch("{{ route('attendance.qr_scan') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('initialState').classList.add('d-none');
                document.getElementById('successResult').classList.remove('d-none');

                document.getElementById('resMsg').innerText = data.message;
                document.getElementById('resTime').innerText = data.time;
                document.getElementById('resAvatar').src = data.student.avatar;
                document.getElementById('resName').innerText = data.student.name;
                document.getElementById('resClass').innerText = data.student.class;
                document.getElementById('resCode').innerText = data.student.code;
                document.getElementById('resPoints').innerText = data.student.points_awarded;
            } else {
                alert(data.message || 'كود طالب غير صحيح.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('حدث خطأ أثناء الاتصال بالسيرفر.');
        });
    }
});
</script>
@endsection
