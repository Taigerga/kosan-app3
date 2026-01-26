<script>
    document.addEventListener('DOMContentLoaded', function () {
        const exportBtn = document.getElementById('exportPdf');

        if (exportBtn) {
            exportBtn.addEventListener('click', async function () {
                // Tampilkan loading
                const loadingDiv = document.createElement('div');
                loadingDiv.id = 'pdfLoading';
                loadingDiv.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.7);
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                    color: white;
                `;

                loadingDiv.innerHTML = `
                    <div style="border: 0.25em solid currentColor; border-right-color: transparent; border-radius: 50%; width: 2rem; height: 2rem; animation: spin 0.75s linear infinite; margin-bottom: 1rem;"></div>
                    <h4>Membuat PDF...</h4>
                    <p>Harap tunggu sebentar</p>
                `;

                document.body.appendChild(loadingDiv);

                try {
                    // Load required libraries dynamically
                    await loadScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js');
                    await loadScript('https://html2canvas.hertzen.com/dist/html2canvas.min.js');
                    await loadScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js');

                    // Buat PDF
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF('p', 'mm', 'a4');
                    let yPosition = 20;

                    // Header PDF
                    pdf.setFontSize(20);
                    pdf.setTextColor(0, 0, 0);
                    pdf.text('Laporan Analisis Kos - AyoKos', 105, 15, { align: 'center' });

                    pdf.setFontSize(12);
                    pdf.setTextColor(0, 0, 0);
                    pdf.text(`Tanggal: ${new Date().toLocaleDateString('id-ID')}`, 105, 25, { align: 'center' });

                    // Data pemilik dari server (dikirim melalui data attributes)
                    const pemilikData = document.getElementById('pemilikData');
                    const namaPemilik = pemilikData?.dataset.nama || 'Pemilik';
                    const tanggalLaporan = pemilikData?.dataset.tanggal || new Date().toLocaleDateString('id-ID');

                    yPosition = 35;
                    pdf.text(`Pemilik: ${namaPemilik}`, 20, yPosition);
                    yPosition += 7;
                    pdf.text(`Periode: ${tanggalLaporan}`, 20, yPosition);
                    yPosition += 15;

                    // Statistik Ringkasan
                    pdf.setFontSize(14);
                    pdf.setTextColor(0, 0, 0);
                    pdf.text('Statistik Ringkasan', 20, yPosition);
                    yPosition += 10;

                    // Data statistik dari halaman
                    const totalPendapatan = document.querySelector('[data-total-pendapatan]')?.innerText || 'Rp 0';
                    const totalPenghuni = document.querySelector('[data-total-penghuni]')?.innerText || '0';

                    pdf.setFontSize(11);
                    pdf.text(`Total Pendapatan: ${totalPendapatan}`, 25, yPosition);
                    yPosition += 7;
                    pdf.text(`Total Penghuni Aktif: ${totalPenghuni}`, 25, yPosition);
                    yPosition += 7;

                    // Hitung okupansi
                    const statusKamarData = @json($statusKamar);
                    let terisi = 0;
                    let totalKamar = 0;

                    if (statusKamarData && statusKamarData.length > 0) {
                        statusKamarData.forEach(item => {
                            totalKamar += item.jumlah;
                            if (item.status_kamar === 'terisi') {
                                terisi = item.jumlah;
                            }
                        });
                    }

                    const okupansi = totalKamar > 0 ? ((terisi / totalKamar) * 100).toFixed(1) : 0;
                    pdf.text(`Rata-rata Okupansi: ${okupansi}%`, 25, yPosition);
                    yPosition += 15;

                    // Fungsi helper untuk konversi canvas ke gambar dengan latar belakang
                    const getChartImage = (canvasId, backgroundColor) => {
                        const originalCanvas = document.getElementById(canvasId);
                        if (!originalCanvas) return null;

                        // Buat canvas sementara untuk menambahkan background
                        const tempCanvas = document.createElement('canvas');
                        tempCanvas.width = originalCanvas.width;
                        tempCanvas.height = originalCanvas.height;
                        const ctx = tempCanvas.getContext('2d');

                        // Isi background
                        ctx.fillStyle = backgroundColor;
                        ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

                        // Gambar canvas asli di atas background
                        ctx.drawImage(originalCanvas, 0, 0);

                        return tempCanvas.toDataURL('image/png');
                    };

                    // Konversi chart ke gambar
                    const charts = [
                        { id: 'pendapatanChart', title: 'Trend Pendapatan (12 Bulan Terakhir)' },
                        { id: 'statusKamarChart', title: 'Distribusi Status Kamar' },
                        { id: 'jenisKosChart', title: 'Distribusi Jenis Kos' },
                        { id: 'statusKontrakChart', title: 'Status Kontrak' },
                        { id: 'reviewChart', title: 'Distribusi Rating' },
                        { id: 'tipeKamarChart', title: 'Distribusi Tipe Kamar' }
                    ];

                    for (const chart of charts) {
                        const chartImage = getChartImage(chart.id, '#ffffff');
                        if (!chartImage) continue;

                        // Tambah halaman baru jika diperlukan
                        if (yPosition > 200) {
                            pdf.addPage();
                            yPosition = 20;
                        }

                        // Tambahkan judul chart
                        pdf.setFontSize(12);
                        pdf.setTextColor(0, 0, 0);
                        pdf.text(chart.title, 20, yPosition);
                        yPosition += 7;

                        // Tambahkan gambar chart
                        const imgWidth = 170;
                        const originalCanvas = document.getElementById(chart.id);
                        const imgHeight = (originalCanvas.height / originalCanvas.width) * imgWidth;

                        pdf.addImage(chartImage, 'PNG', 20, yPosition, imgWidth, imgHeight);
                        yPosition += imgHeight + 8;

                        // Tambahkan penjelasan/legend per tipe chart
                        if (chart.id === 'statusKamarChart') {
                            const statusKamarData = @json($statusKamar);
                            pdf.setFontSize(10);
                            pdf.setTextColor(0, 0, 0);
                            let legendY = yPosition;
                            const colors = [
                                { name: 'Tersedia', rgb: [34, 197, 94] },
                                { name: 'Terisi', rgb: [59, 130, 246] },
                                { name: 'Maintenance', rgb: [234, 179, 8] }
                            ];
                            statusKamarData.forEach((item, index) => {
                                pdf.setFillColor(...colors[index].rgb);
                                pdf.rect(20, legendY - 3, 5, 5, 'F');
                                pdf.text(`${colors[index].name}: ${item.jumlah} kamar`, 28, legendY);
                                legendY += 6;
                            });
                            yPosition = legendY + 5;
                        } else if (chart.id === 'jenisKosChart') {
                            const jenisKosData = @json($jenisKos);
                            pdf.setFontSize(10);
                            pdf.setTextColor(0, 0, 0);
                            let legendY = yPosition;
                            const colors = [
                                { name: 'Putra', rgb: [59, 130, 246] },
                                { name: 'Putri', rgb: [244, 63, 94] },
                                { name: 'Campuran', rgb: [139, 92, 246] }
                            ];
                            jenisKosData.forEach((item, index) => {
                                pdf.setFillColor(...colors[index].rgb);
                                pdf.rect(20, legendY - 3, 5, 5, 'F');
                                pdf.text(`${colors[index].name}: ${item.jumlah} kos`, 28, legendY);
                                legendY += 6;
                            });
                            yPosition = legendY + 5;
                        } else if (chart.id === 'statusKontrakChart') {
                            const statusKontrakData = @json($statusKontrak);
                            pdf.setFontSize(10);
                            pdf.setTextColor(0, 0, 0);
                            let legendY = yPosition;
                            const colors = [
                                { name: 'Aktif', rgb: [34, 197, 94] },
                                { name: 'Pending', rgb: [234, 179, 8] },
                                { name: 'Selesai', rgb: [59, 130, 246] },
                                { name: 'Ditolak', rgb: [239, 68, 68] }
                            ];
                            statusKontrakData.forEach((item, index) => {
                                pdf.setFillColor(...colors[index].rgb);
                                pdf.rect(20, legendY - 3, 5, 5, 'F');
                                pdf.text(`${colors[index].name}: ${item.jumlah} kontrak`, 28, legendY);
                                legendY += 6;
                            });
                            yPosition = legendY + 5;
                        } else if (chart.id === 'pendapatanChart') {
                            const pendapatanData = @json($pendapatanPerBulan);
                            const totalPendapatan = pendapatanData.reduce((sum, item) => sum + item.total, 0);
                            const maxPendapatan = Math.max(...pendapatanData.map(d => d.total));
                            const maxBulan = pendapatanData.find(d => d.total === maxPendapatan);
                            pdf.setFontSize(10);
                            pdf.setTextColor(0, 0, 0);
                            pdf.text(`Total Pendapatan 12 Bulan: Rp ${formatNumber(totalPendapatan)}`, 20, yPosition);
                            yPosition += 5;
                            pdf.text(`Pendapatan Tertinggi: Rp ${formatNumber(maxPendapatan)}`, 20, yPosition);
                            yPosition += 5;
                        } else if (chart.id === 'reviewChart') {
                            const reviewData = @json($reviewData);
                            const totalReviews = reviewData.reduce((sum, item) => sum + item.jumlah, 0);
                            const avgRating = reviewData.reduce((sum, item) => sum + (item.rating * item.jumlah), 0) / totalReviews || 0;
                            pdf.setFontSize(10);
                            pdf.setTextColor(0, 0, 0);
                            pdf.text(`Total Review: ${totalReviews} review`, 20, yPosition);
                            yPosition += 5;
                            pdf.text(`Rating Rata-rata: ${avgRating.toFixed(1)} `, 20, yPosition);
                            yPosition += 5;
                        } else if (chart.id === 'tipeKamarChart') {
                            const tipeKamarData = @json($tipeKamar);
                            pdf.setFontSize(10);
                            pdf.setTextColor(0, 0, 0);
                            let legendY = yPosition;
                            const colors = [
                                { name: 'Cyan', rgb: [6, 182, 212] },
                                { name: 'Purple', rgb: [168, 85, 247] },
                                { name: 'Pink', rgb: [236, 72, 153] },
                                { name: 'Teal', rgb: [20, 184, 166] },
                                { name: 'Orange', rgb: [251, 146, 60] }
                            ];
                            tipeKamarData.forEach((item, index) => {
                                if (colors[index]) {
                                    pdf.setFillColor(...colors[index].rgb);
                                    pdf.rect(20, legendY - 3, 5, 5, 'F');
                                    pdf.text(`${item.tipe_kamar}: ${item.jumlah} kamar`, 28, legendY);
                                    legendY += 6;
                                }
                            });
                            yPosition = legendY + 5;
                        }
                        yPosition += 5;
                    }

                    // Data Pendapatan per Kos
                    if (yPosition > 250) {
                        pdf.addPage();
                        yPosition = 20;
                    }

                    pdf.setFontSize(14);
                    pdf.text('Pendapatan per Kos', 20, yPosition);
                    yPosition += 10;

                    const pendapatanPerKosData = @json($pendapatanPerKosFull);
                    const pendapatanTableData = pendapatanPerKosData.map((kos, index) => [
                        index + 1,
                        kos.nama_kos,
                        `Rp ${formatNumber(kos.total_pendapatan)}`
                    ]);

                    pdf.autoTable({
                        startY: yPosition,
                        head: [['No', 'Nama Kos', 'Total Pendapatan']],
                        body: pendapatanTableData,
                        theme: 'grid',
                        headStyles: { fillColor: [59, 130, 246], textColor: 255, fontSize: 10 },
                        bodyStyles: { fontSize: 9, textColor: [0, 0, 0] },
                        alternateRowStyles: { fillColor: [240, 240, 240] },
                        margin: { left: 20, right: 20 },
                        styles: { cellPadding: 3 }
                    });

                    yPosition = pdf.lastAutoTable.finalY + 15;

                    // Data Penghuni per Kos
                    if (yPosition > 250) {
                        pdf.addPage();
                        yPosition = 20;
                    }

                    pdf.setFontSize(14);
                    pdf.text('Penghuni per Kos', 20, yPosition);
                    yPosition += 10;

                    const penghuniPerKosData = @json($penghuniPerKosFull);
                    const penghuniTableData = penghuniPerKosData.map((kos, index) => [
                        index + 1,
                        kos.nama_kos,
                        kos.jumlah_penghuni,
                        `${round((kos.jumlah_penghuni / (penghuniPerKosData.reduce((sum, k) => sum + k.jumlah_penghuni, 0) || 1)) * 100, 1)}%`
                    ]);

                    pdf.autoTable({
                        startY: yPosition,
                        head: [['No', 'Nama Kos', 'Jumlah Penghuni', 'Persentase']],
                        body: penghuniTableData,
                        theme: 'grid',
                        headStyles: { fillColor: [34, 197, 94], textColor: 255, fontSize: 10 },
                        bodyStyles: { fontSize: 9, textColor: [0, 0, 0] },
                        alternateRowStyles: { fillColor: [240, 240, 240] },
                        margin: { left: 20, right: 20 },
                        styles: { cellPadding: 3 }
                    });

                    // Footer
                    const totalPages = pdf.internal.getNumberOfPages();
                    for (let i = 1; i <= totalPages; i++) {
                        pdf.setPage(i);
                        pdf.setFontSize(8);
                        pdf.setTextColor(0, 0, 0);
                        pdf.text(`Halaman ${i} dari ${totalPages}`, 105, 290, { align: 'center' });
                        pdf.text('© AyoKos - Sistem Manajemen Kos', 105, 293, { align: 'center' });
                    }

                    // Simpan PDF
                    const fileName = `laporan-analisis-${namaPemilik.replace(/\s+/g, '-').toLowerCase()}-${new Date().toISOString().split('T')[0]}.pdf`;
                    pdf.save(fileName);

                    // Hapus loading
                    document.body.removeChild(loadingDiv);

                } catch (error) {
                    console.error('Error generating PDF:', error);
                    const loadingDiv = document.getElementById('pdfLoading');
                    if (loadingDiv) {
                        loadingDiv.innerHTML = `
                            <div style="color: #ef4444; font-size: 3rem; margin-bottom: 1rem;">✕</div>
                            <h4>Gagal membuat PDF</h4>
                            <p>${error.message}</p>
                            <button onclick="this.parentElement.remove()" style="margin-top: 1rem; padding: 0.5rem 1rem; background: #ef4444; color: white; border: none; border-radius: 0.5rem; cursor: pointer;">
                                Tutup
                            </button>
                        `;
                    }
                }
            });
        }

        // Helper function untuk load script
        function loadScript(src) {
            return new Promise((resolve, reject) => {
                if (document.querySelector(`script[src="${src}"]`)) {
                    resolve();
                    return;
                }

                const script = document.createElement('script');
                script.src = src;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        // Helper function untuk format number
        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Helper function untuk round
        function round(value, decimals) {
            return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
        }
    });
</script>

<style>
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>