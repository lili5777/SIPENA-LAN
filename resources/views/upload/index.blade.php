<!DOCTYPE html>
<html>

<head>
    <title>GDrive Upload</title>
</head>

<body>
    <h1>Upload ke Google Drive</h1>

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <ul style="color:red">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form enctype="multipart/form-data" method="POST" action="{{ route('upload.store') }}">
        @csrf
        <input type="text" name="nama" placeholder="Nama Dokumen">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>


</body>

</html>