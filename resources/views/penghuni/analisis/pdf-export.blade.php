<script>
    document.addEventListener('DOMContentLoaded', function () {
        const exportBtn = document.getElementById('exportPdfPenghuni');

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
                    pdf.text('Laporan Analisis Penghuni - AyoKos', 105, 15, { align: 'center' });

                    pdf.setFontSize(12);
                    pdf.setTextColor(0, 0, 0);
                    pdf.text(`Tanggal: ${new Date().toLocaleDateString('id-ID')}`, 105, 25, { align: 'center' });

                    // Data penghuni dari server
                    const penghuniData = document.getElementById('penghuniData');
                    const namaPenghuni = penghuniData?.dataset.nama || 'Penghuni';

                    yPosition = 35;
                    pdf.text(`Nama Penghuni: ${namaPenghuni}`, 20, yPosition);
                    yPosition += 10;

                    // Statistik Ringkasan
                    pdf.setFontSize(14);
                    pdf.setTextColor(0, 0, 0);
                    pdf.text('Statistik Ringkasan', 20, yPosition);
                    yPosition += 10;

                    // Data statistik dari PHP (langsung dari controller)
                    const statistikData = @json($statistikRingkasan);

                    pdf.setFontSize(11);
                    pdf.text(`Total Kontrak: ${statistikData.total_kontrak || 0}`, 25, yPosition);
                    yPosition += 7;
                    pdf.text(`Kontrak Aktif: ${statistikData.kontrak_aktif || 0}`, 25, yPosition);
                    yPosition += 7;
                    pdf.text(`Total Pembayaran: Rp ${formatNumber(statistikData.total_pembayaran || 0)}`, 25, yPosition);
                    yPosition += 7;
                    pdf.text(`Jumlah Review: ${statistikData.jumlah_review || 0}`, 25, yPosition);
                    yPosition += 7;
                    pdf.text(`Rata-rata Rating: ${statistikData.rata_rata_rating ? parseFloat(statistikData.rata_rata_rating).toFixed(1) : 0}/5`, 25, yPosition);
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
                        { id: 'pengeluaranChart', title: 'Riwayat Pengeluaran' },
                        { id: 'statusPembayaranChart', title: 'Status Pembayaran' },
                        { id: 'jenisKosChart', title: 'Preferensi Jenis Kos' },
                        { id: 'ratingChart', title: 'Distribusi Rating' }
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
                        if (chart.id === 'statusPembayaranChart') {
                            const statusPembayaranData = @json($statusPembayaran);
                            pdf.setFontSize(10);
                            pdf.setTextColor(0, 0, 0);
                            let legendY = yPosition;
                            const colors = [
                                { name: 'Lunas', rgb: [16, 185, 129] },
                                { name: 'Pending', rgb: [245, 158, 11] },
                                { name: 'Terlambat', rgb: [239, 68, 68] },
                                { name: 'Lainnya', rgb: [107, 114, 128] }
                            ];
                            statusPembayaranData.forEach((item, index) => {
                                pdf.setFillColor(...colors[index].rgb);
                                pdf.rect(20, legendY - 3, 5, 5, 'F');
                                pdf.text(`${item.status_pembayaran.charAt(0).toUpperCase() + item.status_pembayaran.slice(1)}: ${item.jumlah} transaksi`, 28, legendY);
                                legendY += 6;
                            });
                            yPosition = legendY + 5;
                        } else if (chart.id === 'jenisKosChart') {
                            const jenisKosData = @json($jenisKosDisewa);
                            pdf.setFontSize(10);
                            pdf.setTextColor(0, 0, 0);
                            let legendY = yPosition;
                            const colors = [
                                { name: 'Putra', rgb: [139, 92, 246] },
                                { name: 'Putri', rgb: [244, 63, 94] },
                                { name: 'Campuran', rgb: [16, 185, 129] }
                            ];
                            jenisKosData.forEach((item, index) => {
                                const jenis = item.jenis_kos === 'putra' ? 'Putra' : 
                                             item.jenis_kos === 'putri' ? 'Putri' : 'Campuran';
                                pdf.setFillColor(...colors[index].rgb);
                                pdf.rect(20, legendY - 3, 5, 5, 'F');
                                pdf.text(`${jenis}: ${item.jumlah_sewa} kali sewa`, 28, legendY);
                                legendY += 6;
                            });
                            yPosition = legendY + 5;
                        } else if (chart.id === 'ratingChart') {
                            const reviewData = @json($reviewStats);
                            pdf.setFontSize(10);
                            pdf.setTextColor(0, 0, 0);
                            let legendY = yPosition;
                            const stars = ['★', '★★', '★★★', '★★★★', '★★★★★'];
                            const colors = [
                                [239, 68, 68],
                                [245, 158, 11],
                                [234, 179, 8],
                                [34, 197, 94],
                                [34, 197, 94]
                            ];
                            stars.forEach((star, index) => {
                                const review = reviewData.find(r => r.rating_bulat === index + 1);
                                const count = review ? review.jumlah : 0;
                                pdf.setFillColor(...colors[index]);
                                pdf.rect(20, legendY - 3, 5, 5, 'F');
                                pdf.text(`${star}: ${count} review`, 28, legendY);
                                legendY += 6;
                            });
                            yPosition = legendY + 5;
                        } else if (chart.id === 'pengeluaranChart') {
                            const pembayaranPerBulanData = @json($pembayaranPerBulan);
                            const totalPengeluaran = pembayaranPerBulanData.reduce((sum, item) => sum + item.total, 0);
                            const avgPengeluaran = totalPengeluaran / pembayaranPerBulanData.length;
                            const maxPengeluaran = Math.max(...pembayaranPerBulanData.map(d => d.total));
                            const maxBulan = pembayaranPerBulanData.find(d => d.total === maxPengeluaran);
                            pdf.setFontSize(10);
                            pdf.setTextColor(0, 0, 0);
                            pdf.text(`Total Pengeluaran 6 Bulan: Rp ${formatNumber(totalPengeluaran)}`, 20, yPosition);
                            yPosition += 5;
                            pdf.text(`Rata-rata Pengeluaran: Rp ${formatNumber(Math.round(avgPengeluaran))} per bulan`, 20, yPosition);
                            yPosition += 5;
                        }
                        yPosition += 5;
                    }

                    // Data Riwayat Kontrak
                    if (yPosition > 250) {
                        pdf.addPage();
                        yPosition = 20;
                    }

                    pdf.setFontSize(14);
                    pdf.text('Riwayat Kontrak Terbaru', 20, yPosition);
                    yPosition += 10;

                    const riwayatKontrakData = @json($riwayatKontrak);
                    const riwayatTableData = riwayatKontrakData.map((kontrak, index) => [
                        index + 1,
                        kontrak.kos?.nama_kos || '-',
                        kontrak.kamar?.nomor_kamar || '-',
                        `${kontrak.durasi_sewa} bulan`,
                        kontrak.status_kontrak
                    ]);

                    pdf.autoTable({
                        startY: yPosition,
                        head: [['No', 'Nama Kos', 'Kamar', 'Durasi', 'Status']],
                        body: riwayatTableData,
                        theme: 'grid',
                        headStyles: { fillColor: [59, 130, 246], textColor: 255, fontSize: 10 },
                        bodyStyles: { fontSize: 9, textColor: [0, 0, 0] },
                        alternateRowStyles: { fillColor: [240, 240, 240] },
                        margin: { left: 20, right: 20 },
                        styles: { cellPadding: 3 }
                    });

                    // Insight - Tabel Tipe Kamar Disewa
                    const tipeKamarDisewaData = @json($tipeKamarDisewa);
                    if (tipeKamarDisewaData.length > 0) {
                        const insightY = pdf.lastAutoTable ? pdf.lastAutoTable.finalY + 15 : yPosition;
                        
                        if (insightY > 250) {
                            pdf.addPage();
                            yPosition = 20;
                        }

                        pdf.setFontSize(14);
                        pdf.text('Preferensi Tipe Kamar', 20, insightY);
                        const tipeTableData = tipeKamarDisewaData.map((tipe, index) => [
                            index + 1,
                            tipe.tipe_kamar,
                            `${tipe.jumlah_sewa} kali`,
                            `Rp ${formatNumber(Math.round(tipe.rata_rata_harga))}`
                        ]);

                        pdf.autoTable({
                            startY: insightY + 10,
                            head: [['No', 'Tipe Kamar', 'Jumlah Sewa', 'Harga Rata-rata']],
                            body: tipeTableData,
                            theme: 'grid',
                            headStyles: { fillColor: [139, 92, 246], textColor: 255, fontSize: 10 },
                            bodyStyles: { fontSize: 9, textColor: [0, 0, 0] },
                            alternateRowStyles: { fillColor: [240, 240, 240] },
                            margin: { left: 20, right: 20 },
                            styles: { cellPadding: 3 }
                        });
                    }

                    // Footer
                    const totalPages = pdf.internal.getNumberOfPages();
                    for (let i = 1; i <= totalPages; i++) {
                        pdf.setPage(i);
                        pdf.setFontSize(8);
                        pdf.setTextColor(0, 0, 0);
                        pdf.text(`Halaman ${i} dari ${totalPages}`, 105, 290, { align: 'center' });
                        pdf.text('© AyoKos - Analisis Data Penghuni', 105, 293, { align: 'center' });
                    }

                    // Simpan PDF
                    const fileName = `analisis-penghuni-${namaPenghuni.replace(/\s+/g, '-').toLowerCase()}-${new Date().toISOString().split('T')[0]}.pdf`;
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
            // Pastikan number adalah angka, bukan string
            const num = typeof number === 'string' ? parseFloat(number) : number;
            
            // Format dengan pemisah ribuan tanpa desimal kecuali jika ada
            return num.toLocaleString('id-ID');
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