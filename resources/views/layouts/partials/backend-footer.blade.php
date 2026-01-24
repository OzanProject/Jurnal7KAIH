<footer class="footer">
    <div class="d-flex align-items-center justify-content-between">
        <p class="fs-11 text-muted fw-medium text-uppercase font-family-secondary mb-0">
            {{ \App\Models\Setting::get('footer_text', 'Copyright © ' . date('Y') . ' Jurnal 7 Kebiasaan.') }}
        </p>
    </div>
</footer>
