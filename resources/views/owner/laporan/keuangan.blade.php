<div class="row">
    <div class="col-md-12">
        <form method="GET" action="{{ route('owner.laporan.keuangan') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Filter Laporan</button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header">
                <h4>Laporan Laba Rugi</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <td class="fw-bold">Penjualan Bersih (Omset)</td>
                        <td class="text-end text-success">+ Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Harga Pokok Penjualan (HPP)</td>
                        <td class="text-end text-danger">- Rp {{ number_format($totalHPP, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="table-secondary">
                        <td class="fw-bold">LABA KOTOR</td>
                        <td class="text-end fw-bold">Rp {{ number_format($labaKotor, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Biaya Operasional (Pengeluaran)</td>
                        <td class="text-end text-danger">- Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="table-primary">
                        <td class="fw-bold">LABA BERSIH</td>
                        <td class="text-end fw-bold fs-4">Rp {{ number_format($labaBersih, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
