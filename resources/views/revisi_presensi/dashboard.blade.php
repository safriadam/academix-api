
<!DOCTYPE html>
<html>
<head>
    <title>Upload File</title>
</head>
<body>
    <h1>Upload File Revisi Presensi</h1>

    <!-- Form untuk upload file -->
    <form action="{{ url('/api/upload-revisi-presensi') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="file">Pilih file untuk diupload:</label>
        <input type="file" name="file" id="file" required>
        <button type="submit">Upload</button>
    </form>

    <!-- Menampilkan data revisi presensi -->
    <h2>Daftar Revisi Presensi</h2>
    <table border="1">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Mata Kuliah</th>
                <th>Keterangan</th>
                <th>File</th>
            </tr>
        </thead>
        <tbody>
            @foreach($RevisiPresensi as $item)
                <tr>
                    <td>{{ $item->nim }}</td>
                    <td>{{ $item->Nama_mahasiswa }}</td>
                    <td>{{ $item->Mata_kuliah }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td>
                        @if($item->file_path)
                            <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank">Lihat File</a>
                        @else
                            Tidak ada file
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
