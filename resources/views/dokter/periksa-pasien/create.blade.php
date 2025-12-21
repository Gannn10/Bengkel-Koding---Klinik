<x-layouts.app title="Periksa Pasien">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Periksa Pasien</h1>

                <div class="card">
                    <div class="card-body">
                        {{-- PERBAIKAN: Gunakan titik (periksa.pasien.store) --}}
                        <form action="{{ route('periksa.pasien.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_daftar_poli" value="{{ $id }}">

                            <div class="form-group mb-3">
                                <label for="select-obat" class="form-label">Pilih Obat</label>
                                <select id="select-obat" class="form-select">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}"
                                            data-harga="{{ $obat->harga }}">
                                            {{ $obat->nama_obat }} - Rp{{ number_format($obat->harga) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control" required>{{ old('catatan') }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Obat Terpilih</label>
                                <ul id="obat-terpilih" class="list-group mb-2"></ul>
                                <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                                <input type="hidden" name="obat_json" id="obat_json">
                            </div>

                            <div class="form-group mb-3">
                                <label>Total Harga (Biaya Periksa + Obat)</label>
                                <p id="total-harga" class="fw-bold fs-4">Rp 150.000</p>
                            </div>

                            <button type="submit" class="btn btn-success">Simpan</button>
                            {{-- PERBAIKAN: Gunakan titik (periksa.pasien.index) --}}
                            <a href="{{ route('periksa.pasien.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const selectObat = document.getElementById('select-obat');
        const listObat = document.getElementById('obat-terpilih');
        const inputBiaya = document.getElementById('biaya_periksa');
        const inputObatJson = document.getElementById('obat_json');
        const totalHargaEl = document.getElementById('total-harga');
        const BIAYA_JASA_DOKTER = 150000; // Biaya dasar

        let daftarObat = [];

        // Inisialisasi awal
        updateTotal();

        selectObat.addEventListener('change', () => {
            const selectedOption = selectObat.options[selectObat.selectedIndex];
            const id = selectedOption.value;
            const nama = selectedOption.dataset.nama;
            const harga = parseInt(selectedOption.dataset.harga || 0);

            if (!id) return;
            if (daftarObat.some(o => o.id == id)) {
                alert('Obat sudah dipilih!');
                selectObat.selectedIndex = 0;
                return;
            }

            daftarObat.push({ id, nama, harga });
            renderObat();
            selectObat.selectedIndex = 0;
        });

        function renderObat() {
            listObat.innerHTML = '';
            daftarObat.forEach((obat, index) => {
                const item = document.createElement('li');
                item.className = 'list-group-item d-flex justify-content-between align-items-center';
                item.innerHTML = `
                    ${obat.nama} - Rp ${obat.harga.toLocaleString()}
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusObat(${index})">Hapus</button>
                `;
                listObat.appendChild(item);
            });
            updateTotal();
        }

        window.hapusObat = function(index) {
            daftarObat.splice(index, 1);
            renderObat();
        }

        function updateTotal() {
            // Hitung total harga obat
            let totalObat = daftarObat.reduce((sum, obat) => sum + obat.harga, 0);
            
            // Total Akhir = Harga Obat + Jasa Dokter
            let totalAkhir = totalObat + BIAYA_JASA_DOKTER;

            // Simpan ke input hidden biaya_periksa (hanya harga obatnya saja atau totalnya, tergantung kebijakan sistem Anda)
            // Di Controller Anda: $request->biaya_periksa + 150000. 
            // Jadi di sini kita kirim TOTAL HARGA OBAT saja.
            inputBiaya.value = totalObat; 
            
            // Tampilkan ke user total yang harus dibayar
            totalHargaEl.textContent = `Rp ${totalAkhir.toLocaleString()}`;
            
            // Update JSON untuk dikirim ke controller
            inputObatJson.value = JSON.stringify(daftarObat.map(o => o.id));
        }
    </script>
</x-layouts.app>