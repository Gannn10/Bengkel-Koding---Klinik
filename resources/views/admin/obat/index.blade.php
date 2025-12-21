<x-layouts.app title="Data Obat">
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-12">

                {{-- Alert flash message --}}
                @if (session('message'))
                <div class="alert alert-{{ session('type', 'success') }} alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Data Obat</h1>
                    <a href="{{ route('obat.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Obat
                    </a>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                {{-- KEMBALI KE ASAL: Menggunakan class 'table-dark' (Abu-abu Gelap) --}}
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nama Obat</th>
                                        <th>Kemasan</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($obats as $obat )
                                    <tr>
                                        <td>{{ $obat->nama_obat }}</td>
                                        <td>{{ $obat->kemasan }}</td>
                                        <td>{{ 'Rp ' . number_format($obat->harga, 0, ',', '.') }}</td>
                                        
                                        {{-- Menampilkan Stok dengan Warna --}}
                                        <td>
                                            @if($obat->stok <= 0)
                                                <span class="badge bg-danger">Habis</span>
                                            @elseif($obat->stok < 10)
                                                <span class="badge bg-warning text-dark">{{ $obat->stok }} (Menipis)</span>
                                            @else
                                                <span class="badge bg-success">{{ $obat->stok }}</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('obat.edit', $obat->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('obat.destroy', $obat->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus Data Obat ini ?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center" colspan="5">
                                            Belum ada data obat
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 2000);
    </script>
</x-layouts.app>